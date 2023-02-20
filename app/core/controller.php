<?php

class Controller
{
    /**
     * Return file from views folder to run
     *
     * @param [type] $path
     * @param array $data
     * @return View
     */
    public function view($path, $data = [])
    {
        // Extracting array to make data accessible using its keys
        if (is_array($data)) {
            extract($data);
        }

        if (str_contains($path, 'dashboard/')) {
            if (file_exists("app/views/" . $path . ".php")) {
                include "app/views/" . $path . ".php";
            } else {
                include "app/views/" . $path . "404.php";
            }
        } else {
            if (file_exists("app/views/" . THEME . $path . ".php")) {
                include "app/views/" . THEME . $path . ".php";
            } else {
                include "app/views/" . THEME . "404.php";
            }
        }
    }

    /**
     * Return file from models
     * 
     */
    public function load_model($model)
    {
        if (file_exists("app/models/" . strtolower($model) . ".model.php")) {
            include "app/models/" . strtolower($model) . ".model.php";
            return $model = new $model();
        }
        return false;
    }
}
