<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ImagemModel;
use App\Models\PostagemModel;
use App\Models\UsuarioModel;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Request;
use Config\Paths;
use Exception;

use function PHPUnit\Framework\isEmpty;

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

    
    public function show($bgStyle, $indice='ultimo')
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

            $tabelaUsuario->transException(true)->transStart();
            $tabelaPostagem->transException(true)->transStart();
            $tabelaImagem->transException(true)->transStart();

            $userEmail = session('user')->email;

            $userId = $tabelaUsuario->where('email', '$userEmail')->select('id_usuario')->get()->getRow()->id_usuario;

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

            $tabelaUsuario->transComplete();
            $tabelaImagem->transComplete();
            $tabelaPostagem->transComplete();

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
        $tabelaUsuario = new UsuarioModel();
        $tabelaPostagem = new PostagemModel();
        $tabelaImagem = new ImagemModel();

        try {

            $tabelaUsuario->transException(true)->transStart();
            $tabelaPostagem->transException(true)->transStart();
            $tabelaImagem->transException(true)->transStart();

            $postagem = $tabelaPostagem->where('id_postagem', $indice)->select()->limit(1)->get()->getRow();
            $idImagemCapa = $postagem->id_imagem_capa;
            $objetoImagemCapa = $tabelaImagem->where('id_imagem', $idImagemCapa)->select()->get()->getRow();
            $criadoPor = $tabelaUsuario->where('id_usuario', $postagem->criado_por)->select('nome')->get()->getRow()->nome;
            $tabelaUsuario->transComplete();
            $tabelaPostagem->transComplete();
            $tabelaImagem->transComplete();
            return view('Admin/editarNoticia', [
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
        } else {
            if (! $validated) {
                return redirect()->back()->with('errors', $this->validator->getErrors());
            }
            $tabelaUsuario = new UsuarioModel();
            // $tabelaImagem = new ImagemModel();
            $tabelaPostagem = new PostagemModel();

            $tabelaUsuario->transBegin();
            // $tabelaImagem->transBegin();
            $tabelaPostagem->transBegin();

            $userEmail = session('user')->email;

            $userId = $tabelaUsuario->where('email', $userEmail)->select('id_usuario')->get()->getRow()->id_usuario;

            // $img = $this->request->getFile('img-capa');
            // $idImagemCapa = $tabelaImagem->insert([
            //     'id_imagem'  => new RawSql('DEFAULT'),
            //     'nome'       => $img->getName(),
            //     'tipo'       => $img->getExtension(),
            //     'dados'      => file_get_contents($img->getTempName())
            // ]);
            var_dump($indice);
            $tabelaPostagem->where('id_postagem', $indice)->update($indice, [
                'titulo'         => $this->request->getPost('txt-titulo'),
                'corpo_noticia'  => $this->request->getPost('txt-noticia'),
                'criado_por'     => $userId
            ]);

            if (
                ! $tabelaUsuario->transStatus() || 
                // ! $tabelaImagem->transStatus() || 
                ! $tabelaPostagem->transStatus()
            ) {
                $tabelaUsuario->transRollback();
                // $tabelaImagem->transRollback();
                $tabelaPostagem->transRollback();
                session()->setTempdata(
                    'msgNoticiaShowResultado', 'Ocorreu um erro ao atualizar a imagem', 5
                );
                return redirect()->route('admin.noticias.show.2p', [
                    'error', $indice
                ]);
            } else {
                $tabelaUsuario->transCommit();
                // $tabelaImagem->transCommit();
                $tabelaPostagem->transCommit();
                session()->setTempdata(
                    'msgNoticiaShowResultado', 'A imagem foi atualizada com sucesso', 5
                );
                return redirect()->route('admin.noticias.show.1p', [
                    'success', $indice
                ]);
            }
        }
die();
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
