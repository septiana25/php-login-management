<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Service;

function setcookie(string $name, string $value){
    echo "$name: $value";
}

use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Domain\Session;
use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = New SessionRepository(Database::getConection());
        $this->userRepository = New UserRepository(Database::getConection());
        $this->sessionService = New SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = New User();
        $user->id = 'ian';
        $user->name = 'ian';
        $user->password = password_hash('ian', PASSWORD_BCRYPT);

        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create('ian');
        $this->expectOutputRegex("[X-IAN-SESSION: $session->id]");

    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'ian';

        $this->sessionRepository->save($session);

        $this->sessionService->destroy($session->userId);;

        $this->expectOutputRegex("[X-IAN-SESSION: ]");

    }


    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'ian';

        $s= $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();       

        $this->assertEquals($session->userId, $user->id);
    }
}
