<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <?= $this->renderSection('head') ?>
</head>
<body style="min-width: 320px; min-height: 550;">

<div class="min-vh-100">

    <?php if ($url == 'painel adm.'): ?>

        <div class="min-vh-100 d-flex flex-column">
            <?= $this->include('partials/header') ?>
            <div style="max-width: 100%;" class="row flex-grow-1">
                <main class="col-12 col-md-9 order-1 ">
                    <?= $this->renderSection('content') ?>
                </main>
                <?= $this->include('partials/adminAside') ?>
            </div>

    <?php else: ?>

        <div class="min-vh-100 d-flex flex-column">
            <?= $this->include('partials/header') ?>
            <?= $this->renderSection('content') ?>
        </div>

    <?php endif ?>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
</body>
</html>