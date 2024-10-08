<?php

namespace App\Controllers;

use App\Models\UsersModel; 
use CodeIgniter\HTTP\Message;

class Users extends BaseController
{
    public function index()//: string
    {
        return view('register');
    }
    public function create()//: string
    {
        $rules=[
            'user' => 'required|max_length[30]|is_unique[users.user]|',
            'password' => 'required|max_length[50]|min_length[5]',
            'repassword' => 'matches[password]',
            'name' => 'required|max_length[145]',
            'email' => 'required|max_length[145]|valid_email|is_unique[users.email]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }
        $userModel = new UsersModel();
        $post=$this->request->getPost(['user','password','name','email']);
       
        $token= bin2hex(random_bytes(20));
        $userModel->insert([
            'user' => $post['user'],
            'password' => password_hash($post['password'],PASSWORD_DEFAULT),
            'name' => $post['name'],
            'email' => $post['email'],
            'active' => 0,
            'activation_token' => $token
        ]);
        $email = \Config\Services::email();
    
        $email->setTo($post['email']);
        $email->setSubject('Activa tu cuenta');
        $url=base_url('activate-user/'.$token);
        $body='<p>Hola '.$post['name'].'</p>';
        $body.='<p>Para continuar con el proceso de registro, has clic en la siguiete liga : </p>';
        $body.="<p><strong><a href='$url' >Activar cuenta</a></strong></p>";
        $body.="<strong>Gracias</strong>";

        $email->setMessage($body);
        //$email->send();
        if ($email->send()) {
   // echo 'Correo enviado correctamente';
} else {
    echo $email->printDebugger(['headers']);
}
        $title="Registro exitoso";
        $message="Revisa tu correo de registro para activar tu cuenta";
        return $this->showMessage($message,$title);
    }

    private function showMessage($message,$title){
        $data=[
            'title' =>$title,
            'message' =>$message
        ];
        return view('message',$data);
    }

    public function activateUser($token)  {
        $userModel= new UsersModel();
        $user = $userModel->where(['activation_token' => $token , 'active' => 0])->first();
        if ($user) {
            $userModel->update(
                $user['id'],
                [
                    'active' => 1,
                    'activation_token' => '',
                ]
            );
            return $this->showMessage('Tu cuenta ha sido activada .','Cuenta activada');
        }
        return $this->showMessage('Por favor, intente nuevamente mas tarde','Ocurrio un error.');

    }

    public function requestPasswordForm()  {
        return view('requestPasswordForm');
    }

    public function resetPasswordForm($token){
        $userModel=new UsersModel();
        $user= $userModel->where(['reset_token'=>$token])->first();
        if ($user) {
            $currentDateTime= new \DateTime();
            $currentDateTimeStr= $currentDateTime->format('Y-m-d H:i:s');
            if($currentDateTimeStr <= $user['reset_token_expires_at']) {
                return view('reset_password',['token'=>$token]);
            }else{
                return $this->showMessage('Por favor, solicita un nuevo enlace para restablecer tu contraseña','El enlace ha expirado.');
            }
        }
        return $this->showMessage('Por Favor, intenta nuevamente más tarde','Ocurrio un error');
    }

    public function resetPassword(){
        $rules=[
            'password' => 'required|max_length[50]|min_length[5]',
            'repassword' => 'matches[password]',            
        ];
        if(!$this->validate($rules)){
            return redirect()->back()->withInput()->with('errors',$this->validator->listErrors());
        }
        $userModel= new UsersModel();
        $post= $this->request->getPost(['token','password']);
        $user = $userModel->where(['reset_token' => $post['token'],'active'=>1])->first();
        if($user){
            $userModel->update($user['id'],[
                'password' =>password_hash($post['password'],PASSWORD_DEFAULT),
                'reset_token' =>null,
                'reset_token_experes_at' =>'',
            ]);
            return $this->showMessage('Hemos modificado la contraseña.','Contraseña modificada');
        }
        return $this->showMessage('Por Favor, intenta nuevamente más tarde','Ocurrio un error');
    }

    public function sendResetLinkEmail(){
        $rules=[
            'email' => 'required|min_length[4]|valid_email',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }
        $userModel= new UsersModel();
        $post= $this->request->getPost(['email']);
        $user = $userModel->where(['email'=>$post['email'],'active'=>1])->first();
        if ($user){
            $token=bin2hex(random_bytes(21));
            $expiresAt = new \DateTime();
            $expiresAt->modify('+1 hour');
            $userModel->update($user['id'],[
                'reset_token' => $token,
                'reset_token_expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            ]);

            $email= \Config\Services::email();
            $email->setTo($post['email']);
            $email->setSubject('Recuperar contraseña');
            $url =base_url('password-reset/' . $token);
            $body='<p>Estimad@'.$user['name'].'</p>';
            $body.="<p>Se ha solicitado un restauracion de contraseña.<br>Para restaurar la contraseña, visita la sigiente direccion: <a href='$url'>Restablecer contraseña</a></p>";
            $body.='!Gracias¡';
            $email->setMessage($body);
            if ($email->send()) {
             // echo 'Correo enviado correctamente';
            } else {
                // echo $email->printDebugger(['headers']);
            }

            $title='Correo de recuperacion Enviado';
            $message='Revisa tu correo electronico para restablecer tu contraseña';
            return $this->showMessage($message,$title);
        }
        return redirect()->back()->withInput()->with('errors', 'El correo ingresado no se encuentra en nuestra base de datos.<br> Favor de verificarlo');
    }
}
