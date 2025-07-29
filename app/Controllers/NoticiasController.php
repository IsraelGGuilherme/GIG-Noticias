<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ImagemModel;
use App\Models\PostagemModel;
use App\Models\UsuarioModel;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Paths;
use Exception;

class NoticiasController extends BaseController
{
    public function index()
    {
        $tabelaPostagem = new PostagemModel();

        try {

            $listaPostagem = $tabelaPostagem->select('postagem.id_postagem, postagem.titulo, postagem.corpo_noticia, usuario.nome');
            $listaPostagem->join('usuario', 'criado_por = id_usuario');
            $listaPostagem = $listaPostagem->get()->getResult();
        
            foreach ($listaPostagem as $postagem) {
                if (mb_strlen($postagem->titulo, 'UTF-8') > 30) {
                    $postagem->titulo = mb_substr($postagem->titulo, 0, 30, 'UTF-8') . '...';
                }
                if (mb_strlen($postagem->corpo_noticia, 'UTF-8') > 60) {
                    $postagem->corpo_noticia = mb_substr($postagem->corpo_noticia, 0, 60, 'UTF-8') . '...';
                }
            }
            return view('Admin/tabelaNoticias', [
                'title' => 'Painel Administrativo - Noticias', 
                'url' => 'painel adm.',
                'listaPostagem' => $listaPostagem, 
            ]);

        } catch (Exception $e) {

            throw new PageNotFoundException();

        }
    }

    
    public function show($bgStyle, $indice)
    {
        return view('Admin/showResultado', [
            'title' => 'Painel Administrativo - Noticias', 
            'url' => 'painel adm.',
            'bgStyle' => $bgStyle,
            'indice' => $indice, 
        ]);
    }

    public function create()
    {
        return view('Admin/publicarNoticia', [
            'title' => 'Painel Administrativo - Publicar noticia', 'url' => 'painel adm.', 'paths' => new Paths
        ]);
    }

    public function store()
    {
        $validated = $this->validate([
            'txt-titulo' => 'required|max_length[100]',
            'txt-noticia' => 'required',
        ], 
        [
            'txt-titulo' => [
                'required' => 'O título é obrigatório',
                'max_length' => 'O título não pode passar de 100 caracteres'
            ],
            'txt-noticia' => [
                'required' => 'A texto da notícia é obrigatório',
            ]
        ]);

        $validationData = $this->validateData([], [
            'img-capa' => [
                'label' => 'Este',
                'rules' => [
                    'uploaded[img-capa]',
                    'is_image[img-capa]',
                    'mime_in[img-capa,image/jpg,image/jpeg,image/png]',
                    'max_size[img-capa,50000]',
                ],
            ],
        ]);

        if (! $validated || ! $validationData) {
            return redirect()->route('admin.noticias.create')->with('errors', $this->validator->getErrors());
        }

        $tabelaUsuario = new UsuarioModel();
        $tabelaImagem = new ImagemModel();
        $tabelaPostagem = new PostagemModel();

        try {

            $tabelaImagem->transException(true)->transStart();

            $userEmail = session('user')->email;

            $userId = $tabelaUsuario->where('email', $userEmail)->select('id_usuario')->get()->getRow()->id_usuario;

            $img = $this->request->getFile('img-capa');
            $idImagemCapa = $tabelaImagem->insert([
                'id_imagem'  => new RawSql('DEFAULT'),
                'nome'       => $img->getName(),
                'tipo'       => $img->getExtension(),
                'dados'      => file_get_contents($img->getTempName())
            ]);

            $idPostagem = $tabelaPostagem->insert([
                'id_postagem'    => new RawSql('DEFAULT'),
                'titulo'         => $this->request->getPost('txt-titulo'),
                'corpo_noticia'  => $this->request->getPost('txt-noticia'),
                'criado_por'     => $userId,
                'id_imagem_capa' => $idImagemCapa
            ]);

            $tabelaImagem->transComplete();

            session()->setTempdata(
                'msgNoticiaShowResultado', 'Noticia salva com sucesso', 5
            );
            return redirect()->route('admin.noticias.show.2p', [
                'success', $idPostagem
            ]);
            
        } catch (Exception $e) {

            session()->setTempdata(
                'msgNoticiaShowResultado', 'Ocorreu um erro ao salvar a notícia', 5
            );
            return redirect()->route('admin.noticias.show.1p', [
                'error'
            ]);
        }

    }

    public function edit($indice)
    {
        $tabelaPostagem = new PostagemModel();

        try {

            $postagem = $tabelaPostagem->where('id_postagem', $indice);
            $postagem->select('postagem.id_postagem, postagem.titulo,postagem.corpo_noticia, usuario.nome, imagem.tipo, imagem.dados');
            $postagem->join('usuario', 'criado_por = id_usuario');
            $postagem->join('imagem', 'id_imagem_capa = id_imagem');
            $postagem->limit(1);
            $postagem = $postagem->get()->getRow();
            return view('Admin/editarNoticia', [
                'title' => $postagem->titulo,
                'url' => 'painel adm.', 
                'postagem' => $postagem, 
            ]);

        } catch (Exception $e) {

            throw new PageNotFoundException();

        }
    }

    public function update($indice)
    {
        $validated = $this->validate([
            'txt-titulo' => 'required|max_length[100]',
            'txt-noticia' => 'required',
        ], 
        [
            'txt-titulo' => [
                'required' => 'O título é obrigatório',
                'max_length' => 'O título não pode passar de 100 caracteres'
            ],
            'txt-noticia' => [
                'required' => 'A texto da notícia é obrigatório',
            ]
        ]);

        $imagemCapaTrocada = request()->getFile('img-capa')->getError() == 4 ? false : true;

        try {
            if ($imagemCapaTrocada) {
                $validationData = $this->validateData([], [
                    'img-capa' => [
                        'label' => 'Este',
                        'rules' => [
                            'uploaded[img-capa]',
                            'is_image[img-capa]',
                            'mime_in[img-capa,image/jpg,image/jpeg,image/png]',
                            'max_size[img-capa,50000]',
                        ],
                    ],
                ]);

                if (! $validated || ! $validationData) {
                    return redirect()->back()->with('errors', $this->validator->getErrors());
                }

                $tabelaUsuario = new UsuarioModel();
                $tabelaPostagem = new PostagemModel();
                $tabelaImagem = new ImagemModel();

                $tabelaPostagem->transException(true)->transStart();

                $userEmail = session('user')->email;

                $userId = $tabelaUsuario->where('email', $userEmail)->select('id_usuario')->get()->getRow()->id_usuario;

                $tabelaPostagem->update($indice, [
                    'titulo'         => $this->request->getPost('txt-titulo'),
                    'corpo_noticia'  => $this->request->getPost('txt-noticia'),
                    'criado_por'     => $userId
                ]);

                $idImagemCapa = $tabelaPostagem->where('id_postagem', $indice)->select('id_imagem_capa')->get()->getRow()->id_imagem_capa;

                $img = $this->request->getFile('img-capa');

                $idImagemCapa = $tabelaImagem->update($idImagemCapa, [
                    'nome'       => $img->getName(),
                    'tipo'       => $img->getExtension(),
                    'dados'      => file_get_contents($img->getTempName())
                ]);

                $tabelaPostagem->transComplete();

                session()->setTempdata(
                    'msgNoticiaShowResultado', 'A notícia foi atualizada com sucesso', 5
                );
                return redirect()->route('admin.noticias.show.2p', [
                    'success', $indice
                ]);
            } else {
                if (! $validated) {
                    return redirect()->back()->with('errors', $this->validator->getErrors());
                }
                $tabelaUsuario = new UsuarioModel();
                $tabelaPostagem = new PostagemModel();

                $tabelaPostagem->transException(true)->transStart();

                $userEmail = session('user')->email;

                $userId = $tabelaUsuario->where('email', $userEmail)->select('id_usuario')->get()->getRow()->id_usuario;

                $tabelaPostagem->where('id_postagem', $indice)->update($indice, [
                    'titulo'         => $this->request->getPost('txt-titulo'),
                    'corpo_noticia'  => $this->request->getPost('txt-noticia'),
                    'criado_por'     => $userId
                ]);

                $tabelaPostagem->transComplete();

                session()->setTempdata(
                    'msgNoticiaShowResultado', 'A notícia foi atualizada com sucesso', 5
                );
                return redirect()->route('admin.noticias.show.2p', [
                    'success', $indice
                ]);
            }

        } catch (Exception $e) {

            session()->setTempdata(
                'msgNoticiaShowResultado', 'Ocorreu um erro ao atualizar a notícia', 5
            );
            return redirect()->route('admin.noticias.show.2p', [
                'error', $indice
            ]);

        }
    }

    public function destroy($indice)
    {
        $tabelaPostagem = new PostagemModel();
        $tabelaImagem = new ImagemModel();

        try {
            $tabelaPostagem->transException(true)->transStart();

            $idImagemCapa = $tabelaPostagem->where('id_postagem', $indice)->select('id_imagem_capa')->get()->getRow()->id_imagem_capa;

            $deletado = $tabelaImagem->where('id_imagem', $idImagemCapa)->delete();
        
            $tabelaPostagem->transComplete();

            if ($deletado) {
                $resultadoDelete = [
                    'resultado' => 'success',
                    'msg' => 'Registro Apagado com sucesso'
                ];
            } else {
                $resultadoDelete = [
                    'resultado' => 'danger',
                    'msg' => 'Ocorreu um erro ao apagar o registro'
                ];
            }
            session()->setTempdata('resultadoDelete', $resultadoDelete, 5);
            var_dump(session('resultadoDelete'));
            return redirect()->route('admin.noticias');

        } catch (Exception $e) {

            session()->setTempdata(
                'resultadoDelete', [
                    'resultado' => 'danger',
                    'msg' => 'Ocorreu um erro ao apagar o registro'
                ], 5
            );
            return redirect()->route('admin.noticias');

        }
    }
}
