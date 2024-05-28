<div class="login-led-container">
    <div class="login-form">
        <form action="/user/create" class="row g-2">
            <h1>S'inscrire</h1>

            <div class="col-md-6">
                <label for="mail" class="form-label">Adresse mail</label>
                <input type="email" class="form-control" id="mail" placeholder="name@example.com">
            </div>
            <div class="col-md-6">
                <label for="pseudo" class="form-label">Username</label>
                <div class="input-group">
                    <div class="input-group-text">@</div>
                    <input type="text" class="form-control" id="pseudo" placeholder="Pseudo">
                </div>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" class="form-control" aria-describedby="passwordHelpBlock">
                <div id="passwordHelpBlock" class="form-text">
                    Doit contenir 8 à 20 caractères.
                </div>
            </div>
            <div class="col-md-6">
                <label for="password-confirmation" class="form-label">Confirmer votre mot de passe</label>
                <input type="password" id="password-confirmation" class="form-control">
            </div>
            <div class="col text-center">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>
    </div>
</div>