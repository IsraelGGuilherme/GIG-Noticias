<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ElFinderController extends BaseController
{
    public function index()
    {
        return view('ElFinder');
    }
}
