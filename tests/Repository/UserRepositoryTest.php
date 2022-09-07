<?php

namespace IanSeptiana\PHP\MVC\LOGIN;

use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
use PHPUnit\Framework\TestCase;


class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConection());
        $sessionRepository = new SessionRepository(Database::getConection());

        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = 'ian3';
        $user->name = 'ian';
        $user->password = 'ian';

        $save = $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testIdNull()
    {
        $result = $this->userRepository->findById("");
        self::assertNull($result);
    }

    public function testUpdate()
    {
        $user = new User();
        $user->id = 'ian3';
        $user->name = 'ian';
        $user->password = 'ian';

        $this->userRepository->save($user);

        $user->name = 'Cici';

        $this->userRepository->update($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }
}
