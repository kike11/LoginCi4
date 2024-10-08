<?php

namespace App\Controllers;

use App\Models\UsersModel; 
use CodeIgniter\HTTP\Message;

class Home extends BaseController
{
    public function index(){
      return view('Home');
    }

 
}

?>