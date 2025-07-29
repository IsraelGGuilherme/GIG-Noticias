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
        try {

            $tabelaPostagem = new PostagemModel();

            $postagem = $tabelaPostagem->where('id_postagem', $indice);
            $postagem->select('postagem.titulo, postagem.corpo_noticia, usuario.nome, imagem.tipo, imagem.dados');
            $postagem->join('usuario', 'criado_por = id_usuario');
            $postagem->join('imagem', 'id_imagem_capa = id_imagem');
            $postagem = $postagem->limit(1)->get()->getRow();

            return view('noticia', [
                'title' => $postagem->titulo,
                'url' => 'home', 
                'postagem' => $postagem, 
            ]);

        } catch (Exception $e) {

            throw new PageNotFoundException();

        }

    }
}
