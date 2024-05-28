<div>
    <form id="sign-up-form" action="/user/create-user" method="POST">
        <h1 id="test">S'inscrire</h1>

        <div>
            <label for="username">Pseudo</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
            <label for="mail">Adresse mail</label>
            <input type="text" id="mail" name="mail">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
            <p>
                Doit contenir 8 à 20 caractères.
            </p>
        </div>
        <div>
            <label for="passwordVerify">Confirmation du mot de passe</label>
            <input type="password" id="passwordVerify" name="passwordVerify">
            <p>
                Doit contenir 8 à 20 caractères.
            </p>
        </div>
        <div>
            <button type="submit">S'inscrire</button>
        </div>
    </form>

    <div id="sign-up-result"></div>
</div>