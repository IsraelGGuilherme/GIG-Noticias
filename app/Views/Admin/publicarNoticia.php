<?= $this->extend('master') ?>

<?= $this->section('head') ?>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>

    <form action="<?= url_to('admin.noticias.store') ?>" enctype="multipart/form-data" method="post">

        <div class="d-flex flex-column gap-3">

            <div>
                <label for="txt-titulo" class="form-label">Título da noticia</label>
                <input class="form-control" type="text" id="txt-titulo" name="txt-titulo">
                <p class="text-danger"><?= session()->getFlashdata('errors')['txt-titulo'] ?? '' ?></p>
            </div>
            <div>
                <label for="img-capa" class="form-label">Capa da noticia</label>
                <input class="form-control" type="file" id="img-capa"  name="img-capa" accept="image/*">
                <p class="text-danger"><?= session()->getFlashdata('errors')['img-capa'] ?? '' ?></p>
            </div>
            <div>
                <label for="txt-noticia" class="form-label">Texto da noticia</label>
                <textarea name="txt-noticia" id="txt-noticia" rows="10" cols="80">
                    This is my textarea to be replaced with CKEditor 4.
                </textarea>
                <script>
                    CKEDITOR.replace('txt-noticia');
                </script>
            </div>
            <p class="text-danger"><?= session()->getFlashdata('errors')['txt-noticia'] ?? '' ?></p>
            <div>
                <label for="imgs-corpo" class="form-label">Imagens no fim da notícia</label>
                <input class="form-control" type="file" id="imgs-corpo" name="imgs-corpo" accept="image/*" multiple>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Publicar notícia</button>
            </div>

        </div>

    </form>

<?= $this->endSection('content') ?>