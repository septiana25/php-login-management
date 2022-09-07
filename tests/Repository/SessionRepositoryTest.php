<?php

namespace IanSeptiana\PHP\MVC\LOGIN;

use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Domain\Session;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConection());
        $this->sessionRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "ian1";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);
    }

    public function testIdNotFound()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "ian1";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById('asdasd');

        self::assertNull($result);
    }

    public function testDeleteByid()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "ian1";

    
        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);

        $result = $this->sessionRepository->findById($session->id);
        
        self::assertNull($result);
    }
}
