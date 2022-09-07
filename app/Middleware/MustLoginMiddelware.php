<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Middleware;

use IanSeptiana\PHP\MVC\LOGIN\App\View;
use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
use IanSeptiana\PHP\MVC\LOGIN\Service\SessionService;

class MustLoginMiddelware implements Middleware
{
    protected SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConection());
        $userRepository = new UserRepository(Database::getConection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function before()
    {
        $session = $this->sessionService->current();
        if ($session == null) {
            View::redirect('/users/login');
        }
    }


}
