<header>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url_to('home') ?>">GIG Noticias</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse gap-3" id="navbarTogglerDemo02">
                <ul class="navbar-nav justify-content-end ms-auto pe-0">
                    <li class="nav-item d-flex align-items-center">
                        <a <?= ($url == 'home' ? 'class="nav-link active"' : 'class="nav-link"') ?> aria-current="page" href="<?= url_to('home') ?>">Home</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a <?= ($url == 'sobre' ? 'class="nav-link active"' : 'class="nav-link"') ?> href="<?= url_to('sobre') ?>">Sobre</a>
                    </li>
                    <li class="nav-item <?= session()->has('user') ? '' : 'd-none' ?>  d-flex align-items-center">
                        <a <?= ($url == 'painel adm.' ? 'class="nav-link active"' : 'class="nav-link"') ?> href="<?= url_to('admin') ?>">Painel Adm.</a>
                  </li>
                </ul>
                <form class="" role="search">
                    <div class="input-group my-2 my-sm-0">
                        <input class="form-control " type="search" placeholder="Search" aria-label="Search"/>
                        <button style="background-color: white;" class="btn btn-light border-0" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <a class="d-grid gap-2 text-decoration-none" href="<?= (session()->has('user') ? (url_to('login.destroy')) : (url_to('login'))) ?>">
                    <button style="background-color: white; display: block;" class="btn btn-light " type="submit" <?= session()->has('user') ? 'onclick="return resposta = confirm(\'Tem certeza de que deseja sair?\')"' : ''  ?>>
                        <?= session()->has('user') ? 'Logout' : 'Login'  ?>
                    </button>
                </a>               
            </div>
        </div>
    </nav>
</header>
