<?php

require '../vendor/autoload.php';

use Slim\Factory\AppFactory;
use src\Controllers\LoanController;

$app = AppFactory::create();

$app->get('/', [LoanController::class, 'home']);
$app->post('/apply', [LoanController::class, 'apply']);
$app->get('/loan/list/{personal_id}', [LoanController::class, 'list']);

$app->run();
