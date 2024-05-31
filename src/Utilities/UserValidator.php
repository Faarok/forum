<?php

namespace App\Utilities;

use App\Models\User;

/**
 * UserValidator
 * @author Svein SAMSON
 */
class UserValidator
{
    /**
     * validateUsername
     * Vérifie la validité du nom d'utilisateur
     * @author Svein SAMSON
     *
     * @param  string $username
     * @return bool
     */
    public static function validateUsername(string $username):bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $username);
    }

    /**
     * checkExistingUsername
     * Vérifie la disponibilité d'un nom d'utilisateur
     * @author Svein SAMSON
     *
     * @param  string $username
     * @return bool
     */
    public static function checkExistingUsername(string $username):bool
    {
        $query = (new User())
            ->select(array('username'))
            ->where('username', '=', $username)
            ->run()
        ;

        if(User::load($query))
            return true;

        return false;
    }

    /**
     * validateEmail
     * Vérifie la validité de l'adresse mail
     * @author Svein SAMSON
     *
     * @param  string $email
     * @return bool
     */
    public static function validateEmail(string $email):bool
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
     * checkExistingEmail
     * Vérifie la disponibilité d'une adresse mail
     * @author Svein SAMSON
     *
     * @param  string $email
     * @return bool
     */
    public static function checkExistingEmail(string $email):bool
    {
        $query = (new User())
            ->select(array('mail'))
            ->where('mail', '=', $email)
            ->run()
        ;

        if(User::load($query))
            return true;

        return false;
    }

    /**
     * validatePasswordLength
     * Vérifie la longueur du mot de passe
     * @author Svein SAMSON
     *
     * @param  string $password
     * @return bool
     */
    public static function validatePasswordLength(string $password):bool
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
    public static function validatePassword(string $password):bool
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
}