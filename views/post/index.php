<?php

use App\User;

$title = 'Mon blog';

$query = new User();

dump($query
    ->select(array('id', 'mail'))
    ->where('id', 'IN', array(1,2,3))
    // ->orWhere('mail', '=', 'test1@gmail.com')
    ->whereSub('AND', function($query) {
        $query
            ->where('id', '=', 1)
            ->orWhere('created_at', 'BETWEEN', array(
                (new DateTime())->modify('-1 month')->format('Y-m-d'),
                (new DateTime())->format('Y-m-d')
            ))
        ;
    })
    ->whereSub('AND', function($query) {
        $query
            ->where('mail', '=', 1)
            ->whereSub('OR', function($query) {
                $query
                    ->where('id', '>', 3)
                    ->orWhere('id', '!=', 4)
                ;
            })
        ;
    })
    ->run()
    // ->fetchAll(PDO::FETCH_ASSOC)
);

?>

<h1>Post</h1>