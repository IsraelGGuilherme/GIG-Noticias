<?php

namespace App\Controllers;

use App\Models\ImagemModel;
use App\Models\PostagemModel;

class HomeController extends BaseController
{
    public function index()
    {
        $tabelaPostagem = new PostagemModel();
        $tabelaImagemCapa = new ImagemModel();

        $noticiasCarrossel = $tabelaPostagem->select('id_postagem,titulo,id_imagem_capa')->orderBy('id_postagem', 'DESC')->limit(5)->get()->getResult();
        $listaImagemNoticiaCarrossel = [];

        foreach($noticiasCarrossel as $noticia) {
            $objetoImagemCapa = $tabelaImagemCapa->where('id_imagem', $noticia->id_imagem_capa)->select()->limit(1)->get()->getRow();
            array_push($listaImagemNoticiaCarrossel, $objetoImagemCapa);     
        }
        // var_dump($listaImagemNoticiaCarrossel);
        // die();

        $listaNoticias = [
            'noticias' => $tabelaPostagem->select('id_postagem,titulo,id_imagem_capa')->orderBy('id_postagem', 'DESC')->paginate(5),
            'pager' => $tabelaPostagem->pager,
        ];

        $listaImagemNoticia = [];

        foreach($listaNoticias['noticias'] as $noticia) {
            $objetoImagemCapa = $tabelaImagemCapa->where('id_imagem', $noticia->id_imagem_capa)->select()->limit(1)->get()->getRow();
            array_push($listaImagemNoticia, $objetoImagemCapa);     
        }

        // return redirect()->route('noticia.show', ['ultimo']);
        return view('homePage', [
            'title' => 'GIG Blog - Página Inicial', 
            'url' => 'home',
            'noticiasCarrossel' => $noticiasCarrossel,
            'listaImagemNoticiaCarrossel' => $listaImagemNoticiaCarrossel,
            'listaNoticias' => $listaNoticias['noticias'],
            'listaImagemNoticia' => $listaImagemNoticia,
            'pager' => $tabelaPostagem->pager
        ]);
    }
}
