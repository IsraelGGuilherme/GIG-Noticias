<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TesteController extends BaseController
{
    public function index()
    {
        var_dump('Teste');
    }
}
