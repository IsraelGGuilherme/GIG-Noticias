<?php

namespace App\Controllers;

use App\Models\ImagemModel;
use App\Models\PostagemModel;

class HomeController extends BaseController
{
    public function index()
    {
        $tabelaPostagem = new PostagemModel();

        $noticiasCarrossel = $tabelaPostagem->select('id_postagem,titulo,tipo,dados');
        $noticiasCarrossel->join('imagem', 'id_imagem_capa = id_imagem');
        $noticiasCarrossel->orderBy('id_postagem', 'DESC');
        $noticiasCarrossel = $noticiasCarrossel->limit(5)->get()->getResult();

        $listaNoticias = [
            'noticias' => $tabelaPostagem->select('id_postagem,titulo,tipo,dados')->join('imagem', 'id_imagem_capa = id_imagem')->orderBy('id_postagem', 'DESC')->paginate(5),
            'pager' => $tabelaPostagem->pager,
        ];

        return view('homePage', [
            'title' => 'GIG Blog - Página Inicial', 
            'url' => 'home',
            'noticiasCarrossel' => $noticiasCarrossel,
            'listaNoticias' => $listaNoticias['noticias'],
            'pager' => $tabelaPostagem->pager
        ]);
    }
}
