<?php

// Autoload
require '../vendor/autoload.php';

// Settings
require '../app/settings.php';

// Initialise Slim
$app = new \Slim\App(['settings' => $settings]);

// Services
require '../app/services.php';

// Routes
require '../app/serverRoutes.php';

// Run Slim
$app->run();