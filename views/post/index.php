<?php

use App\User;
use App\ApiCurl;

$title = 'Mon blog';
// dd(User::createUser('test1', 'test1@gmail.com', 'Azerty1234/', 'Azerty1234/'));

// dd(User::getById(1));

// dd(
//     User::createUser('test1', 'test1@gmail.com', 'Azerty1234/', 'Azerty1234/'),
//     User::createUser('test2', 'test2@gmail.com', 'Azerty1234/', 'Azerty1234/'),
//     User::createUser('test3', 'test3@gmail.com', 'Azerty1234/', 'Azerty1234/'),
//     User::createUser('test4', 'test4@gmail.com', 'Azerty1234/', 'Azerty1234/')
// );

// User::getBydId(1);

$instance = new User();
$query = $instance
    ->select()
    ->where('id', 'IN', array(1, 2))
    ->run()
;

dump(User::loadAll($query));
    // ->where('id', 'IN', array(1,2,3))
    // ->subWhere('AND', function($query) {
    //     $query
    //         ->where('id', '=', 1)
    //         ->orWhere('created_at', 'BETWEEN', array(
    //             (new DateTime())->modify('-1 month')->format('Y-m-d'),
    //             (new DateTime())->format('Y-m-d')
    //         ))
    //     ;
    // })
    // ->subWhere('AND', function($query) {
    //     $query
    //         ->where('mail', '=', 1)
    //         ->subWhere('OR', function($query) {
    //             $query
    //                 ->where('id', '>', 3)
    //                 ->orWhere('id', '!=', 4)
    //             ;
    //         })
    //     ;
    // })
    // ->groupBy(array('id', 'mail'))
    // ->orderBy(array(
    //     'id' => 'desc',
    //     'mail' => 'asc',
    //     'created_at' => ''
    // ))
    // ->limit(20)
    // ->run()
    // ->fetchAll(PDO::FETCH_ASSOC)
// );

?>

<h1>Post</h1>