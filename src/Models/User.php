<?php

namespace App\Models;

use Core\Entity;

/**
 * User
 * @author Svein SAMSON
 */
class User extends Entity
{

    public $username, $mail, $password;

    protected $table = 'user';
    protected $columns = array(
        'username' => array('type' => 'text', 'label' => 'Pseudo de l\'utilisateur'),
        'mail' => array('type' => 'text', 'label' => 'Mail de l\'utilisateur'),
        'password' => array('type' => 'password', 'label' => 'Mot de passe de l\'utilisateur')
    );
}