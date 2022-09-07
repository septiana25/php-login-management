<?php
require_once __DIR__ . '/../vendor/autoload.php';

use IanSeptiana\PHP\MVC\LOGIN\App\Router;
use IanSeptiana\PHP\MVC\LOGIN\Controller\HomeController;
use IanSeptiana\PHP\MVC\LOGIN\Controller\UserController;
use IanSeptiana\PHP\MVC\LOGIN\Middleware\MustLoginMiddelware;
use IanSeptiana\PHP\MVC\LOGIN\Middleware\MustNotLoginMiddelware;

//HomeController::class -> mengambil berserta namespace nya (IanSeptiana\PHP\MVC\LOGIN\Controller\HomeController)
//home
Router::add('GET', '/', HomeController::class, 'index', []);

//user
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddelware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddelware::class]);

Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddelware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddelware::class]);

Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddelware::class]);

Router::add('GET', '/users/profile', UserController::class, 'profile', [MustLoginMiddelware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postProfile', [MustLoginMiddelware::class]);

Router::add('GET', '/users/password', UserController::class, 'profilePassword', [MustLoginMiddelware::class]);
Router::add('POST', '/users/password', UserController::class, 'postProfilePassword', [MustLoginMiddelware::class]);

Router::run();
