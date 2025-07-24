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
        $tabelaUsuario = new UsuarioModel();
        $tabelaPostagem = new PostagemModel();
        $tabelaImagem = new ImagemModel();

        try {

            $tabelaUsuario->transException(true)->transStart();
            $tabelaPostagem->transException(true)->transStart();
            $tabelaImagem->transException(true)->transStart();

            $listaPostagem = $tabelaPostagem->select()->get()->getResult();

            $listaCriadoPor = [];
        
            foreach ($listaPostagem as $postagem) {
                if (mb_strlen($postagem->titulo, 'UTF-8') > 30) {
                    $postagem->titulo = mb_substr($postagem->titulo, 0, 30, 'UTF-8') . '...';
                }
                if (mb_strlen($postagem->corpo_noticia, 'UTF-8') > 60) {
                    $postagem->corpo_noticia = mb_substr($postagem->corpo_noticia, 0, 60, 'UTF-8') . '...';
                }
                $criadoPor = $tabelaUsuario->where('id_usuario', $postagem->criado_por)->select('nome')->get()->getRow()->nome;
                array_push($listaCriadoPor, $criadoPor);
            }
            $tabelaUsuario->transComplete();
            $tabelaPostagem->transComplete();
            return view('Admin/tabelaNoticias', [
                'title' => 'Painel Administrativo - Noticias', 
                'url' => 'painel adm.',
                'listaPostagem' => $listaPostagem, 
                'listaCriadoPor' => $listaCriadoPor
            ]);

        } catch (Exception $e) {

            var_dump($e);
            // throw new PageNotFoundException();

        }
    }

    
    public function show($msg)
    {
        return view('Admin/showResultado', [
            'title' => 'Painel Administrativo - Noticias', 'url' => 'painel adm.', 'msg' => $msg
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

        $tabelaUsuario->transBegin();
        $tabelaImagem->transBegin();
        $tabelaPostagem->transBegin();

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

        if (
            ! $tabelaUsuario->transStatus() || 
            ! $tabelaImagem->transStatus() || 
            ! $tabelaPostagem->transStatus()
        ) {
            $tabelaUsuario->transRollback();
            $tabelaImagem->transRollback();
            $tabelaPostagem->transRollback();
            return redirect()->route('admin.noticias.show.error');
        } else {
            $tabelaUsuario->transCommit();
            $tabelaImagem->transCommit();
            $tabelaPostagem->transCommit();
            return redirect()->route('admin.noticias.show.success');
        }

    }

        public function destroy($indice)
    {
        $tabelaPostagem = new PostagemModel();
        $tabelaImagem = new ImagemModel();

        try {
            $tabelaPostagem->transException(true)->transStart();
            $tabelaImagem->transException(true)->transStart();

            $idImagemCapa = $tabelaPostagem->where('id_postagem', $indice)->select('id_imagem_capa')->get()->getRow()->id_imagem_capa;

            $deletado = $tabelaImagem->where('id_imagem', $idImagemCapa)->delete();
        
            $tabelaPostagem->transComplete();
            $tabelaImagem->transComplete();

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
