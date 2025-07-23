<?= $this->extend('master') ?>

<?= $this->section('head') ?>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <a href="<?= url_to('admin.noticias.create') ?>">Criar Noticia</a>
    
<?= $this->endSection('content') ?>