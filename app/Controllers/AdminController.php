<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Paths;

class AdminController extends BaseController
{
    public function index()
    {
        return view('Admin/dashboard', [
            'title' => 'Painel Administrativo - Dashboard', 'url' => 'painel adm.', 'paths' => new Paths
        ]);
    }
}
