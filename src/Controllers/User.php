<?php

namespace App\Controllers;

use Core\View;
use Core\Entity;
use Core\Response;

class User extends Entity
{

    public $username, $mail, $password;

    protected $table = 'user';
    protected $columns = array(
        'username' => array('type' => 'text', 'label' => 'Pseudo de l\'utilisateur'),
        'mail' => array('type' => 'text', 'label' => 'Mail de l\'utilisateur'),
        'password' => array('type' => 'password', 'label' => 'Mot de passe de l\'utilisateur')
    );

    public function signUp()
    {
        View::render('auth.sign-up');
    }

    public static function createUser(string $username, string $mail, string $password, string $passwordVerify)
    {
        if(!self::validateUsername($username))
            return Response::handleError(401, 'Nom d\'utilisateur invalide');

        if(!self::validateEmail($mail))
            return Response::handleError(401, 'Format de l\'adresse mail invalide');

        if(!self::validatePasswordLength($password))
            return Response::handleError(401, 'Le mot de passe doit contenir entre 8 et 127 caractères');

        if(!self::validatePaswordVerify($password, $passwordVerify))
            return Response::handleError(401, 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre ainsi qu\'un caractère spécial.');

        $user = new User();
        $user->username = $username;
        $user->mail = $mail;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        return Response::handleSuccess(201, 'OK');
    }

    /**
     * validateUsername
     * Vérifie la validité du nom d'utilisateur
     * @author Svein SAMSON
     *
     * @param  string $username
     * @return bool
     */
    private static function validateUsername(string $username):bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $username);
    }

    /**
     * validateEmail
     * Vérifie la validité de l'adresse mail
     * @author Svein SAMSON
     *
     * @param  string $email
     * @return bool
     */
    private static function validateEmail(string $email):bool
    {
        // Vérification de la syntaxe de base de l'adresse e-mail
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
            return false;

        // Vérification supplémentaire avec une expression régulière pour s'assurer qu'elle est conforme à la RFC
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if(!preg_match($regex, $email))
            return false;

        return true;
    }

    /**
     * validatePasswordLength
     * Vérifie la longueur du mot de passe
     * @author Svein SAMSON
     *
     * @param  string $password
     * @return bool
     */
    private static function validatePasswordLength(string $password):bool
    {
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).*$/';

        // Vérification de la longueur du mot de passe
        if(strlen($password) < 8 || strlen($password) > 127)
            return false;

        return true;
    }

    /**
     * validatePassword
     * Vérifie la validité du mot de passe
     * @author Svein SAMSON
     *
     * @param  string $password
     * @return bool
     */
    private static function validatePassword(string $password):bool
    {
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).*$/';

        /**
         * Vérifie si le mot de passe contient
         *      - Une majuscule
         *      - Une minuscule
         *      - Un chiffre
         *      - Un caractère spécial (non alphabétique, non numérique, non espace)
         */
        if(!preg_match($regex, $password))
            return false;

        return true;
    }

    /**
     * validatePaswordVerify
     * Vérifie que le mot de passe soit identique avec le mot de passe choisi
     * @author Svein SAMSON
     *
     * @param  string $password
     * @param  string $passwordVerify
     * @return bool
     */
    private static function validatePaswordVerify(string $password, string $passwordVerify):bool
    {
        if($password !== $passwordVerify)
            return false;

        return true;
    }
}