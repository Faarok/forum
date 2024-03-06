<?php

use App\User;

$title = 'Mon blog';

$query = new User();

dump($query
    ->select(array('id', 'mail'))
    ->where('id', 'IN', array(1,2,3))
    ->run()
    ->fetchAll(PDO::FETCH_ASSOC)
);
// ->fetchAll(PDO::FETCH_ASSOC)

?>

<h1>Post</h1>