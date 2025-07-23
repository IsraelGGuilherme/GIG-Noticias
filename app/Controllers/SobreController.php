<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SobreController extends BaseController
{
    public function index()
    {
        return view('sobre', [
            'title' => 'Sobre Nós', 'url' => 'sobre'
        ]);
    }
}
