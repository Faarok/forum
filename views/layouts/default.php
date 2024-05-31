<?php

use Core\CallJs;

require_once(__ROOT__ . 'function.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= CSS_PATH_URL . 'toastr.min.css'; ?>">
    <link rel="stylesheet" href="<?= CSS_PATH_URL . 'main.css'; ?>">
    <title><?= $title ?? 'Mon site'; ?></title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>

            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-dark" type="submit">Search</button>
            </form>
        </nav>
    </header>

    <div>
        <?= $content; ?>
    </div>

    <script src="<?= JS_PATH_URL . 'node_modules' . SLASH . 'jquery' . SLASH . 'dist' . SLASH . 'jquery.min.js'; ?>"></script>
    <script src="<?= JS_PATH_URL . 'node_modules' . SLASH . 'toastr' . SLASH . 'build' . SLASH . 'toastr.min.js'; ?>"></script>
    <script type="module" src="<?= JS_PATH_URL . 'core.js'; ?>"></script>

    <?php foreach(CallJs::listFiles() as $script): ?>
        <?= $script; ?>
    <?php endforeach; ?>
</body>
</html>