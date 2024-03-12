<?php

use App\User;
use App\ApiCurl;

$title = 'Mon blog';

// $query = new User();

// dump($query
//     ->select(array('id', 'mail'))
//     ->where('id', 'IN', array(1,2,3))
//     // ->orWhere('mail', '=', 'test1@gmail.com')
//     ->whereSub('AND', function($query) {
//         $query
//             ->where('id', '=', 1)
//             ->orWhere('created_at', 'BETWEEN', array(
//                 (new DateTime())->modify('-1 month')->format('Y-m-d'),
//                 (new DateTime())->format('Y-m-d')
//             ))
//         ;
//     })
//     ->whereSub('AND', function($query) {
//         $query
//             ->where('mail', '=', 1)
//             ->whereSub('OR', function($query) {
//                 $query
//                     ->where('id', '>', 3)
//                     ->orWhere('id', '!=', 4)
//                 ;
//             })
//         ;
//     })
//     ->groupBy(array('id', 'mail'))
//     ->run()
//     // ->fetchAll(PDO::FETCH_ASSOC)
// );

$cluster = '5f0a74338ed94d809fdac53ef72049b0';
$apiKey = 'da76abaa5c7a2cf1b756f5e4ed5fef92fea5badf';
$runId = '1de2a46091534734ad4ca0815c09a287';

$agencies = array(
    'https://www.google.com/maps/place/Cabinet+Bedin+Immobilier+(M%C3%A9rignac)/@44.8415023,-0.6459224,17z/data=!3m1!4b1!4m6!3m5!1s0xd54d9d19f407bad:0x7f2ee6ca13e66d76!8m2!3d44.8415023!4d-0.6459224!16s%2Fg%2F1tnpgs1h?entry=ttu',
    'https://www.google.com/maps/place/Cabinet+Bedin+Immobilier+(Pessac)/@44.8058666,-0.6369288,17z/data=!3m1!4b1!4m6!3m5!1s0xd54d8fa33ddfadb:0x39e7334d9ddb8f2e!8m2!3d44.8058629!4d-0.6320579!16s%2Fg%2F1tdkt3lp?entry=ttu'
);

$tasks = array();
foreach($agencies as $agency)
    $tasks[] = array('url' => $agency);

$api = new ApiCurl('https://api.lobstr.io/v1/results');

$postJson = array(
    'run' => '1de2a46091534734ad4ca0815c09a287',
    'page_size' => 500
);

// CURLOPT_POST => true,
$curlOptions = array(
    CURLOPT_HEADER => false,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => json_encode($postJson),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey,
    )
);

$api->setOptions($curlOptions);
$apiResponse = $api->exec();

$datas = array();

dump($apiResponse);

?>

<h1>Post</h1>