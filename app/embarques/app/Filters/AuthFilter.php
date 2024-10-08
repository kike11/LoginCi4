<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
         service('auth');

        if (!session()->get('logged_in')) {
            return redirect()->to(base_url());
        }
    }
    public function after(RequestInterface $request,ResponseInterface $response, $arguments = null){

    }
}