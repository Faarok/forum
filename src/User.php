<?php

namespace App;

use App\Exception\UserException;

class User extends Entity
{

    public $username, $mail, $password;

    protected $table = 'user';
    protected $columns = array(
        'username' => array('type' => 'text', 'label' => 'Pseudo de l\'utilisateur'),
        'mail' => array('type' => 'text', 'label' => 'Mail de l\'utilisateur'),
        'password' => array('type' => 'password', 'label' => 'Mot de passe de l\'utilisateur')
    );

    public static function createUser(string $username, string $mail, string $password, string $passwordVerify)
    {
        if(!validateUsername($username))
            throw new UserException('Caractères non-autorisés dans le nom d\'utilisateur');

        if(!validateEmail($mail))
            throw new UserException('Format de l\'adresse e-mail invalide');

        if(!validatePassword($password))
            throw new UserException('Le mot de passe doit contenir entre 8 et 127 caractères.');

        if(!validatePaswordVerify($password, $passwordVerify))
            throw new UserException('Les deux mots de passe ne sont pas identiques');

        $user = new User();
        $user->id = 3;
        $user->username = $username;
        $user->mail = $mail;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();
    }
}