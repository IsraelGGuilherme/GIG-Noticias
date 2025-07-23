<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Teste extends BaseController
{
    public function index()
    {
        var_dump('Teste');
    }
}
