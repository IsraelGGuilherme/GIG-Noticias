<?= $this->extend('master') ?>

<?= $this->section('content') ?>

    <div class="container-fluid">

        <section id="carousel" class="carousel slide pb-3">
            <div class="carousel-indicators">
                <?php for ($i=0; $i<sizeof($noticiasCarrossel); $i++): ?>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="<?= $i ?>" <?= $i == 0 ? 'class="active"' : '' ?> aria-current="true" aria-label="Slide <?= $i + 1 ?>"></button>
                <?php endfor ?>
            </div>

            <div class="carousel-inner">

                <?php foreach ($noticiasCarrossel as $indice => $noticia): ?>
                
                    <a href="<?= url_to('noticia.show', $noticia->id_postagem) ?>">
                        <div class="carousel-item <?= $indice == 0 ? 'active' : ''?>">
                            <div class="ratio ratio-16x9">
                                <img src="<?='data:',  $noticia->tipo , ';base64,', base64_encode($noticia->dados) ?>" class="d-block w-100">
                            </div>
                            
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?= $noticia->titulo ?></h5>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>

            </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

        </section>
        <section>
            <?php foreach ($listaNoticias as $indice => $noticia): ?>
                <article class="py-3 <?= sizeof($listaNoticias) - 1 > $indice ? 'border border-secondary border-start-0 border-end-0 border-top-0' : '' ?> ">
                    <a href="<?= url_to('noticia.show', $noticia->id_postagem) ?>" class="row text-decoration-none text-reset">
                        <div class="col-12 col-md-7 order-1 order-md-2">
                            <h3 class="h2 d-none d-sm-block">
                                <?= $noticia->titulo ?>
                            </h3>
                        </div>
                        <div class="col-12 col-md-5 order-2 order-sm-1">

                            <div class="ratio ratio-16x9">
                                <img class="object-fit-cover w-100 h-100" src="<?= 'data:' , $noticia->tipo , ';base64,', base64_encode($noticia->dados) ?>">
                            </div>
                                                        
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>

            <div class="d-flex justify-content-center">
                <?= $pager->links() ?>  
            </div>
            
        </section>

    </div>
<?= $this->endSection('content') ?>