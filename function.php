<?php

/**
 * validateUsername
 * Vérifie la validité du nom d'utilisateur
 * @author Svein SAMSON
 *
 * @param  string $username
 * @return bool
 */
function validateUsername(string $username):bool
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
function validateEmail(string $email):bool
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
 * validatePassword
 * Vérifie la validité du mot de passe
 * @author Svein SAMSON
 *
 * @param  string $password
 * @return bool
 */
function validatePassword(string $password):bool
{
    $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).*$/';

    // Vérification de la longueur du mot de passe
    if(strlen($password) < 8 || strlen($password) > 127)
        return false;

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
function validatePaswordVerify(string $password, string $passwordVerify):bool
{
    if($password !== $passwordVerify)
        return false;

    return true;
}

/**
 * getCurrentDateHour
 * Renvoie la date et l'heure du jour
 *
 * @return string
 */
function getCurrentDateHour():string
{
    return (new DateTime())->format('Y-m-d H:i:s');
}