<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT_URL . 'css' . SLASH . 'main.css'; ?>">
    <title><?= $title ?? 'Mon site'; ?></title>
</head>
<body>
    <nav class="navbar bg-light">
        <div class="container-fluid">
            <div class="nav">
                <a href="#" class="nav-link active" aria-current="page">Active</a>
                <a href="#" class="nav-link">Link</a>
                <a href="#" class="nav-link">Link</a>
                <a href="/sign-up" class="nav-link">S'inscrire</a>
                <a href="/sign-in" class="nav-link">Se connecter</a>
            </div>

            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-dark" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <?= $content; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>