<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ImagemModel;
use App\Models\PostagemModel;
use App\Models\UsuarioModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class NoticiaController extends BaseController
{
    public function show($indice)
    {
        $tabelaUsuario = new UsuarioModel();
        $tabelaPostagem = new PostagemModel();
        $tabelaImagem = new ImagemModel();

        try {

            $tabelaUsuario->transException(true)->transStart();
            $tabelaPostagem->transException(true)->transStart();
            $tabelaImagem->transException(true)->transStart();
            if ($indice == 'ultimo' && isset(session()->user->email) ) {
                $userEmail = session()->user->email;
                $userId = $tabelaUsuario->where('email', $userEmail)->select('id_usuario')->get()->getRow()->id_usuario;
                $postagem = $tabelaPostagem->where('criado_por', $userId)->select()->orderBy('id_postagem', 'DESC')->limit(1)->get()->getRow();
            } elseif ($indice == 'ultimo') {
                $postagem = $tabelaPostagem->select()->orderBy('id_postagem', 'DESC')->limit(1)->get()->getRow();
            } else {
                $postagem = $tabelaPostagem->where('id_postagem', $indice)->select()->limit(1)->get()->getRow();
            }
            $idImagemCapa = $postagem->id_imagem_capa;
            $objetoImagemCapa = $tabelaImagem->where('id_imagem', $idImagemCapa)->select()->get()->getRow();
            $criadoPor = $tabelaUsuario->where('id_usuario', $postagem->criado_por)->select('nome')->get()->getRow()->nome;
            $tabelaUsuario->transComplete();
            $tabelaPostagem->transComplete();
            return view('noticia', [
                'title' => $postagem->titulo,
                'url' => 'home', 
                'postagem' => $postagem, 
                'img' => $objetoImagemCapa,
                'criadoPor' => $criadoPor
            ]);

        } catch (Exception $e) {

            throw new PageNotFoundException();

        }

    }
}
