<?php

// Récupère le code de réponse HTTP
$httpStatusCode = http_response_code();
$httpMessage;

switch($httpStatusCode)
{
    case 404:
        $httpMessage = 'Page non trouvée';
        break;
}

$title = $httpStatusCode . ' - ' . $httpMessage;

?>

<div>
    <h2><?= $title; ?></h2>
</div>