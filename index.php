<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 04/11/2017
 * Time: 10:24
 */

session_start();
include 'server/config/config.php';

mb_internal_encoding("UTF-8");

function autoloadFunction($class)
{
    // Ends with a string "Controller"?
    if (preg_match('/Controller$/', $class))
        require(Config::SERVER_PATH . "controllers/" . $class . ".php");
    else
        require(Config::SERVER_PATH . "models/" . $class . ".php");
}

spl_autoload_register("autoloadFunction");

// Connects to the database
Db::connect("127.0.0.1", Config::DATABASE_USER_NAME, Config::DATABASE_PASSWORD, Config::DATABASE_NAME);

$userManager = new UserManager();

$router = new Router($_SERVER['REQUEST_URI']);
$router->process();
