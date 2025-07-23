<?= $this->extend('master') ?>

<?= $this->section('content') ?>

 <div class="container-fluid d-flex flex-column gap-3">

    <div>
        <h1><?= $postagem->titulo ?></h1>
    </div>
    <div>
        <img class="w-100" src="<?= 'data:' , $img->tipo , ';base64,', base64_encode( $img->dados) ?>" alt="">
    </div>
    <div>
        <?= $postagem->corpo_noticia ?>
        <p><strong>Por <?= $criadoPor ?></strong></p>
    </div>

 </div>

<?= $this->endSection('content') ?>