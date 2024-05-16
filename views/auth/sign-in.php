<?php

use App\User;

dd(User::createUser('testupdate', 'testupdate@gmail.com', 'Azerty1234/', 'Azerty1234/'));

?>

<div class="login-led-container">
    <div class="login-form">
        <form action="/user/create" class="row g-2">
            <h1>Se connecter</h1>

            <div class="col-md-12">
                <label for="mail" class="form-label">Adresse mail ou pseudo</label>
                <input type="text" class="form-control" id="mail">
            </div>
            <div class="col-md-12">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" class="form-control" aria-describedby="passwordHelpBlock">
                <div id="passwordHelpBlock" class="form-text">
                    Doit contenir 8 à 20 caractères.
                </div>
            </div>
            <div class="col text-center">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>
    </div>
</div>