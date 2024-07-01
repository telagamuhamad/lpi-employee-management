<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function logout()
    {
        // Destroy session
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('http://localhost:8000/login.php');
    }
}
