<?= $this->extend('master') ?>

<?= $this->section('content') ?>

 <div class="container-fluid d-flex flex-column gap-3">

    <div>
        <h1><?= $postagem->titulo ?></h1>
    </div>
    <div>
        <img class="w-100" src="<?= 'data:' , $postagem->tipo , ';base64,', base64_encode($postagem->dados) ?>" alt="">
    </div>
    <div>
        <?= $postagem->corpo_noticia ?>
        <p><strong>Por <?= $postagem->nome ?></strong></p>
    </div>

 </div>

<?= $this->endSection('content') ?>