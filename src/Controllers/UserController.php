<?php

namespace App\Controllers;

use Core\View;
use Core\Response;
use App\Models\User;
use App\Utilities\UserValidator;

/**
 * UserController
 * @author Svein SAMSON
 */
class UserController
{
    // Messages d'erreur
    private const ERR_INVALID_USERNAME = 'Nom d\'utilisateur invalide';
    private const ERR_USERNAME_TAKEN = 'Nom d\'utilisateur indisponible';
    private const ERR_INVALID_EMAIL = 'Format de l\'adresse mail invalide';
    private const ERR_EMAIL_TAKEN = 'Un compte existe déjà pour cette adresse mail';
    private const ERR_PASSWORD_LENGTH = 'Le mot de passe doit contenir entre 8 et 127 caractères';
    private const ERR_PASSWORD_COMPLEXITY = 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre ainsi qu\'un caractère spécial';
    private const ERR_PASSWORD_MISMATCH = 'Les deux mots de passe doivent être identiques';

    /**
     * signUp
     * Affiche la page d'inscription
     * @author Svein SAMSON
     *
     * @return void
     */
    public function signUp()
    {
        View::render('auth.sign-up');
    }

    public function testMail()
    {
        View::render('auth.test');
    }

    /**
     * createUser
     * Permet de créer un utilisateur
     * @author Svein SAMSON
     *
     * @param  string $username
     * @param  string $mail
     * @param  string $password
     * @param  string $passwordVerify
     * @return Response
     */
    public static function createUser(string $username, string $mail, string $password, string $passwordVerify):Response
    {
        // Vérification du nom d'utilisateur
        if(!UserValidator::validateUsername($username))
            return Response::handleError(400, self::ERR_INVALID_USERNAME, __FILE__, __LINE__);

        if(UserValidator::checkExistingUsername($username))
            return Response::handleError(409, self::ERR_USERNAME_TAKEN, __FILE__, __LINE__);

        // Vérification de l'adresse mail
        if(!UserValidator::validateEmail($mail))
            return Response::handleError(400, self::ERR_INVALID_EMAIL, __FILE__, __LINE__);

        if(UserValidator::checkExistingEmail($mail))
            return Response::handleError(409, self::ERR_EMAIL_TAKEN, __FILE__, __LINE__);

        // Vérification du mot de passe
        if(!UserValidator::validatePasswordLength($password))
            return Response::handleError(400, self::ERR_PASSWORD_LENGTH, __FILE__, __LINE__);

        if(!UserValidator::validatePassword($password))
            return Response::handleError(400, self::ERR_PASSWORD_COMPLEXITY, __FILE__, __LINE__);

        if($password !== $passwordVerify)
            return Response::handleError(400, self::ERR_PASSWORD_MISMATCH, __FILE__, __LINE__);

        // Création de l'objet User
        $user = new User();
        $user->username = $username;
        $user->mail = $mail;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        // $user->save();

        return Response::handleSuccess(201, 'OK');
    }
}