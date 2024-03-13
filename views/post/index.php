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

$crawler = 'e625ce52e98a6d4d1b53ef36dff7dacb';
$apiKey = '38a1db67f8a3ff748f8376d14c07c1a852235981';


// Create cluster
$api = new ApiCurl('https://api.lobstr.io/v1/clusters/save');

$api
    ->setHeaderOutput(false)
    ->setHeader(array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey
    ))
    ->setPostFields(array(
        'crawler' => $crawler
    ))
;

$cluster = $api->exec()['id'];

// Setting cluster
$api = new ApiCurl('https://api.lobstr.io/v1/clusters/' . $cluster);

$api
    ->setHeaderOutput(false)
    ->setHeader(array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey
    ))
    ->setPostFields(array(
        'name' => 'Vision Google Reviews',
        'concurrency' => 1,
        'export_unique_results' => true,
        'no_line_breaks' => true,
        'to_complete' => false,
        'params' => array(
            'language' => 'Français (France)',
            'max_results' => 500,
            'sort_by' => 'newest'
        ),
        'accounts' => null,
        'run_notify' => 'on_success'
    ))
;

$api->exec();

// Add tasks
$agencies = array(
    'https://www.google.com/maps/place/Cabinet+Bedin+Immobilier+(M%C3%A9rignac)/@44.8415023,-0.6459224,17z/data=!3m1!4b1!4m6!3m5!1s0xd54d9d19f407bad:0x7f2ee6ca13e66d76!8m2!3d44.8415023!4d-0.6459224!16s%2Fg%2F1tnpgs1h?entry=ttu',
    'https://www.google.com/maps/place/Cabinet+Bedin+Immobilier+(Pessac)/@44.8058666,-0.6369288,17z/data=!3m1!4b1!4m6!3m5!1s0xd54d8fa33ddfadb:0x39e7334d9ddb8f2e!8m2!3d44.8058629!4d-0.6320579!16s%2Fg%2F1tdkt3lp?entry=ttu'
);

$tasks = array();
foreach($agencies as $agency)
    $tasks[] = array('url' => $agency);

$api = new ApiCurl('https://api.lobstr.io/v1/tasks');

$api
    ->setHeaderOutput(false)
    ->setHeader(array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey
    ))
    ->setPostFields(array(
        'cluster' => $cluster,
        'tasks' => $tasks
    ))
;

// Get tasks ID
$apiResponse = $api->exec();

$tasksId = array();
foreach($apiResponse['tasks'] as $data)
    $tasksId = $data['id'];


// Launch run
$api = new ApiCurl('https://api.lobstr.io/v1/runs');

$api
    ->setHeaderOutput(false)
    ->setHeader(array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey
    ))
    ->setPostFields(array(
        'cluster' => $cluster
    ))
;

$runId = $api->exec()['id']; // à echo + à ajouter à $_ sur Vision lors du CRON

// Get results
$api = new ApiCurl('https://api.lobstr.io/v1/results');

$api
    ->setHeaderOutput(false)
    ->setCustomRequest('GET')
    ->setPostFields(array(
        'run' => $runId,
        'page' => 1,
        'page_size' => 100000
    ))
    ->setHeader(array(
        'Content-Type: application/json',
        'Authorization: Token ' . $apiKey,
    ))
;

$apiResponse = $api->exec();

dd($apiResponse);

?>

<h1>Post</h1>