<?php

namespace App\Controllers;

use App\Models\UsersModel; 
use CodeIgniter\HTTP\Message;

class Login extends BaseController
{
    public function index(){
      return view('login');
    }

        public function auth(){
        $rules=[
            'user'=> 'required',
            'password'=>'required',
        ];
        if(!$this->validate($rules)){
            return redirect()->back()->withInput()->with('errors',$this->validator->listErrors());
        }

        $userModel= new UsersModel();
        $post = $this->request->getPost(['user','password']);
        $user= $userModel->validateUser($post['user'],$post['password']);

        if($user !== null){
            $this->setSession($user);
            return redirect()->to(base_url('home'));
        }
        return redirect()->back()->withInput()->with('errors','The user or password not match');
    }

    private function setSession($userData)
    {
        $data=[
            'logged_in'=>true,
            'userId'=>$userData['id'],
            'userName'=>$userData['name'],
        ];
        $this->session->set($data);
    }

    public function logout(){
      if($this->session->get('logged_in')){
        $this->session->destroy();
      }
      return redirect()->to(base_url());
    }
}

?>