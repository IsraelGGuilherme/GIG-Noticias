<?= $this->extend('master') ?>

<?= $this->section('head') ?>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <form action="<?= url_to('admin.noticias.update', $postagem->id_postagem) ?>" enctype="multipart/form-data" method="post">

        <div class="d-flex flex-column gap-3">

            <div>
                <label for="txt-titulo" class="form-label">Título da noticia</label>
                <input class="form-control" type="text" id="txt-titulo" name="txt-titulo" value="<?= $postagem->titulo ?>">
                <p class="text-danger"><?= session()->getFlashdata('errors')['txt-titulo'] ?? '' ?></p>
            </div>
            <div>
                <label for="img-capa" class="form-label">Capa da noticia</label>
                <img id="img-capa-salvada" class="w-100" src="<?= 'data:' , $img->tipo , ';base64,', base64_encode( $img->dados) ?>" alt="">
                <div class="d-grid gap-2">
                    <button id="btn-trocar-imagem-capa" class="btn btn-danger" type="button" onclick="trocarImagemCapa()">Trocar Imagem da Capa da notícia</button>
                    <button id="btn-voltar-imagem-capa" class="btn btn-primary d-none" type="button" onclick="voltarImagemCapa()">Cancelar e voltar imagem da Capa da notícia</button>
                </div>
                <input class="form-control d-none" type="file" id="img-capa"  name="img-capa" accept="image/*" value="<?= 'data:' , $img->tipo , ';base64,', base64_encode( $img->dados) ?>">
                <p class="text-danger"><?= session()->getFlashdata('errors')['img-capa'] ?? '' ?></p>
            </div>
            <div>
                <label for="txt-noticia" class="form-label">Texto da noticia</label>
                <textarea name="txt-noticia" id="txt-noticia" rows="10" cols="80">
                    <?= $postagem->corpo_noticia ?>
                </textarea>
                <script>
                    CKEDITOR.replace('txt-noticia');
                </script>
            </div>
            <p class="text-danger"><?= session()->getFlashdata('errors')['txt-noticia'] ?? '' ?></p>
            <!-- <div class="d-none">
                <label for="imgs-corpo" class="form-label">Imagens no fim da notícia</label>
                <input class="form-control" type="file" id="imgs-corpo" name="imgs-corpo" accept="image/*" multiple>
            </div> -->
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Publicar notícia</button>
            </div>

        </div>

    </form>

    <script>
        function trocarImagemCapa () {
            document.querySelector('#btn-trocar-imagem-capa').classList.add('d-none');
            document.querySelector('#btn-voltar-imagem-capa').classList.remove('d-none');
            document.querySelector('#img-capa').classList.remove('d-none'); 
            document.querySelector('#img-capa-salvada').classList.add('d-none'); 
            document.querySelector('#img-capa').classList.remove('d-none');
        }
        function voltarImagemCapa () {
            document.querySelector('#btn-trocar-imagem-capa').classList.remove('d-none');
            document.querySelector('#btn-voltar-imagem-capa').classList.add('d-none');
            document.querySelector('#img-capa').classList.add('d-none'); 
            document.querySelector('#img-capa-salvada').classList.remove('d-none'); 
            document.querySelector('#img-capa').classList.add('d-none');
            document.querySelector('#img-capa').value = ''; 
        }
    </script>

<?= $this->endSection('content') ?>