<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login', [
            'title' => 'Login', 'url' => 'login'
        ]);
    }

    public function store()
    {
        // var_dump($this->request->getPost('email'));
        $validated = $this->validate([
            'email' => 'required|valid_email',
            'senha' => 'required',
        ]);

        if (!$validated) {
            return redirect()->route('login')->with('errors', $this->validator->getErrors());
        }

        $user = new UsuarioModel();

        $userFound = $user->where('email', $this->request->getPost('email'))->select('nome, email, password')->first();

        if (!$userFound) {
            return redirect()->route('login')->with('error', 'Email ou senha inválidos');
        }

        if (!password_verify($this->request->getPost('senha'), $userFound->password)){
            return redirect()->route('login')->with('error', 'Email ou senha inválidos');
        }

        unset($userFound->password);
        session()->set('user', $userFound);
        return redirect()->route('admin');
    }

    public function destroy()
    {
        if (session()->has('user')) {
            session()->destroy();
        }

        return redirect()->route('home');
    }
}
