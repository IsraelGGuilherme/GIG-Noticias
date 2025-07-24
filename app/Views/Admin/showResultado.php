<?= $this->extend('master') ?>

<?= $this->section('head') ?>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <?php if (session()->has('msgNoticiaShowResultado')): ?>

        <div class="alert alert-<?= $bgStyle == 'success' ? 'success': 'danger' ?> mt-3" role="alert">
            <?= session()->get('msgNoticiaShowResultado') ?>
        </div>

    <?php endif; ?>

    <div class="d-flex">

            <div class="flex-grow-1 text-center d-grid p-3 ps-0">
        <a href="<?= url_to('admin')?>" class="btn btn-secondary" type="button">Página Inicial</a>
        </div>
        <div class="flex-grow-1 text-center d-grid p-3 ps-0">
            <a href="<?= url_to('admin.noticias')?>" class="btn btn-secondary" type="button">Voltar</a>
        </div>
        <div <?= $bgStyle == 'error' ? 'class="flex-grow-1 text-center d-grid py-3 d-none"' : 'class="flex-grow-1 text-center d-grid py-3"'?> >
            <a href="<?= url_to('noticia.show', $indice)?>" class="btn btn-secondary" type="button">Visualizar notícia</a>
        </div>
        <div class="flex-grow-1 text-center d-grid p-3 pe-0">
            <a href="<?= url_to('admin.noticias.create')?>" class="btn btn-primary" type="button">Cadastrar Nova Notícia</a>
        </div>

    </div>
    
<?= $this->endSection('content') ?>