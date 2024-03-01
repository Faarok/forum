<?php

namespace App;

class User extends Entity
{

    protected $table = 'user';
    protected $columns = array(
        'mail' => array('type' => 'text', 'label' => 'Mail de l\'utilisateur'),
        'password' => array('type' => 'password', 'label' => 'Mot de passe de l\'utilisateur'),
        'state' => array('type' => 'text', 'label' => 'État logique de la donnée')
    );
}