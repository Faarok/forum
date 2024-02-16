<?php

/**
 * dd
 * Die & Dump
 *
 * @author          SAMSON Svein
 *
 * @param mixed     Tous les paramètres que vous souahitez.
 * @return die(var_dump());
 */
function dd()
{
    if(func_num_args() > 0)
    {
        foreach(func_get_args() as $data)
            var_dump($data);
        die();
    }
}