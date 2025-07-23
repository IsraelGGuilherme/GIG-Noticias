<?= $this->extend('master') ?>

<?= $this->section('content') ?>

    <div class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div style="max-width: 768px;" class="w-75 bg-primary rounded-4 p-3 p-sm-5">
            <section>
                <form action="<?= url_to('login.store') ?>" method="POST" class="d-flex flex-column gap-3 text-white">
                    <h3 class="h3">Fazer login</h3>
                    <div class="mb-3">
                        <p class="text-danger"><?= session()->getFlashdata('error') ?? '' ?></p>

                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                        <p class="text-danger"><?= session()->getFlashdata('errors')['email'] ?? '' ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha">
                        <p class="text-danger"><?= session()->getFlashdata('errors')['senha'] ?? '' ?></p>
                    </div>
                    <button type="submit" class="btn btn-light">Entrar</button>
                </form>
            </section> 
        </div>
    </div>

<?= $this->endSection('content') ?>