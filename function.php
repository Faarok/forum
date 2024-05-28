<?php

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