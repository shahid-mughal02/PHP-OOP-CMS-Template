<?php

/**
 * Data view function
 *
 * @param [type] $data
 * @return Data
 */
function show($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Session Error
 *
 * @return Error
 */
function check_error()
{
    if (isset($_SESSION["error"]) && $_SESSION["error"] != "") {
        echo $_SESSION["error"];
        unset($_SESSION["error"]);
    }
}

/**
 * Special characters remover from data
 *
 * @param [type] $data
 * @return void
 */
function esc($data)
{
    return addslashes(($data));
}
