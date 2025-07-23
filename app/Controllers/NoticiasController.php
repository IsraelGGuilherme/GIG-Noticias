<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ImagemModel;
use App\Models\PostagemModel;
use App\Models\UsuarioModel;
use CodeIgniter\Database\RawSql;
use Config\Paths;

class NoticiasController extends BaseController
{
    public function index()
    {
        return view('Admin/tabelaNoticias', [
            'title' => 'Painel Administrativo - Noticias', 'url' => 'painel adm.', 'paths' => new Paths
        ]);
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

        // var_dump($tabelaImagem->select()->get()->getResult());
        // $resultado = $tabelaImagem->where('id_imagem', 9)->select()->get()->getRow();

        // header("Content-Type: {$resultado->tipo}");
        // header("Content-Disposition: attachment; filename=\"{$resultado[0]->nome}\"");

        // echo $resultado->dados;

        // return view('teste', ['img' => base64_encode( $resultado->dados), 'tipo' => $resultado->tipo]);

        // exit;

        // var_dump($resultado[0]->tipo);

        // var_dump($_FILES);

        // echo $_FILES['name']
        // echo "<img src='" . file_get_contents($img->getTempName()) . ".jpg'>";
        // header("Content-Type: {$img->getExtension()}");
        // header("Content-Disposition: attachment; filename=\"{$img->getName()}\"");
        // echo img_data(file_get_contents($img->getTempName()), 'jpg');

        // var_dump($tabelaImagem->select()->get()->getResult());

        // $tabelaUsuario->transStart();
        // var_dump($tabelaUsuario->select()->get()->getResult());
        // var_dump($tabelaUsuario->transStatus());
        // var_dump($this->request->getPost('img-capa'));
        // $img = $this->request->getFile('img-capa');
        // // var_dump($img->getName());
        // // var_dump($img);
        // // var_dump(getcwd());

        // $files = $this->request->getFiles();
        // var_dump($files);
        // // var_dump($img->get());
        // echo $img;
        // 
        // // var_dump($img->getExtension());
        // // echo file($this->request->getPost('img-capa'));
        // // filesize($this->request->getPost('img-capa'));
        // // var_dump(is_file($this->request->getFile('img-capa')));
        // $tabelaUsuario->query('ANOTHER QUERY...');
        // $tabelaUsuario->transComplete();

        // if ($tabelaUsuario->transStatus() === false) {
        //     // generate an error... or use the log_message() function to log your error
        // }
    }
}
