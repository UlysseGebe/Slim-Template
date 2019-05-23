<?php

/**
 * Configuration
 */
define('URL', 'http://localhost:8888/login2/public/');

/**
 * Routing
 */
// Get 'q' param
$q = !empty($_GET['q']) ? $_GET['q'] : 'Login';

// Define controller
$controller = '404';
if ($q == 'Login') {
    $controller = 'login';
}
else if ($q == 'My-Space') {
    $controller = 'blog';
}
else if ($q == 'Register') {
    $controller = 'inscription';
}
else if ($q == 'Logout') {
    $controller = 'logout';
}
else if ($q == 'Trash') {
    $controller = 'delete';
}
else if ($q == 'Add-Categorie') {
    $controller = 'categorie';
}
else if ($q == 'Add-Video') {
    $controller = 'video';
}

// Include controller
include '../controllers/'.$controller.'.php';
