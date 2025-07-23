<?= $this->extend('master') ?>

<?= $this->section('head') ?>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <?php if ($msg == 'error'): ?>

        <div class="alert alert-danger mt-3" role="alert">
            Ocorreu um erro ao enviar a notícia
        </div>

    <?php else: ?>

        <div class="alert alert-success mt-3" role="alert">
            Notícia enviada com sucesso
        </div>

    <?php endif; ?>

    <div class="d-flex">

        <div class="flex-grow-1 text-center d-grid p-3 ps-0">
            <a href="<?= url_to('admin')?>" class="btn btn-secondary" type="button">Página Inicial</a>
        </div>
        <div <?= $msg == 'error' ? 'class="flex-grow-1 text-center d-grid p-3 d-none"' : 'class="flex-grow-1 text-center d-grid p-3"'?> >
            <a href="<?= url_to('noticia.show', 'ultimo')?>" class="btn btn-secondary" type="button">Visualizar notícia</a>
        </div>
        <div class="flex-grow-1 text-center d-grid p-3 pe-0">
            <a href="<?= url_to('admin.noticias.create')?>" class="btn btn-primary" type="button">Cadastrar Nova Notícia</a>
        </div>

    </div>
    
<?= $this->endSection('content') ?>