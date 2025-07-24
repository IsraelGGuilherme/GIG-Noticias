<?= $this->extend('master') ?>

<?= $this->section('head') ?>

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <?php if (session()->has('resultadoDelete')): ?>

        <div class="alert alert-<?= session('resultadoDelete')['resultado'] ?> my-3 alert-dismissible fade show" role="alert">
            <?= session('resultadoDelete')['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php endif; ?>


    <a href="<?= url_to('admin.noticias.create') ?>">Criar Noticia</a>

    <table id="tabela-noticias" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Corpo da notícia</th>
                <th>Criado por</th>
                <th>Editar</th>
                <th>excluir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaPostagem as $i => $postagem): ?>
                <a href="<?= url_to('noticia.show', $postagem->id_postagem) ?>">
                    <tr>
                        <td>
                            <a class="text-decoration-none text-black" href="<?= url_to('noticia.show', $postagem->id_postagem) ?>">
                                <?= $postagem->id_postagem ?>
                            </a>
                        </td>
                        <td>
                            <a class="text-decoration-none text-black" href="<?= url_to('noticia.show', $postagem->id_postagem) ?>">
                                <?= $postagem->titulo ?>
                            </a>
                        </td>
                        <td>
                            <a class="text-decoration-none text-black" href="<?= url_to('noticia.show', $postagem->id_postagem) ?>">
                                <?= $postagem->corpo_noticia ?>
                            </a>                            
                        </td>
                        <td>
                            <a class="text-decoration-none text-black" href="<?= url_to('noticia.show', $postagem->id_postagem) ?>">
                                <?= $listaCriadoPor[$i] ?>
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-warning text-light" href="<?= url_to('admin.noticias.destroy', $postagem->id_postagem) ?>">
                                Editar
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="<?= url_to('admin.noticias.destroy', $postagem->id_postagem) ?>" onclick="return resposta = confirm('Tem certeza de que deseja excluir essa notícia?')">
                                <i class="bi bi-trash-fill text-light"></i>
                            </a>
                        </td>
                    </tr>
                </a>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Corpo da notícia</th>
                <th>Criado por</th>
                <th>Editar</th>
                <th>excluir</th>
            </tr>
        </tfoot>
    </table>

    <script>
        $(document).ready(function () {
            $('#tabela-noticias').DataTable({
                responsive: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json",
                },
                paging: true,
                pageLength: 10
            });
        });
    </script>
    
<?= $this->endSection('content') ?>