<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Service;

use PHPUnit\Framework\TestCase;
use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
use IanSeptiana\PHP\MVC\LOGIN\Exception\ValidationException;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserLoginRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdatePasswordRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdateRequest;
use IanSeptiana\PHP\MVC\LOGIN\Service\UserService;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserRegisterRequest;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private UserService $userService;
    
    public function setUp(): void
    {
        $conn = Database::getConection();
        $this->userRepository = New UserRepository($conn);
        $this->userService = New UserService($this->userRepository);
        $sessionRepository = new SessionRepository($conn);

        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {
        $request = New UserRegisterRequest();
        $request->id = 'cici2';
        $request->name = 'Cici RM';
        $request->password = 'rahasia';

        $response = $this->userService->register($request);


        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);

        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);
        $request = New UserRegisterRequest();
        $request->id = '';
        $request->name = '';
        $request->password = '';

        $this->userService->register($request);
    }

    public function testRegisterDuplicate()
    {
        $user = New User();
        $user->id = 'cici2';
        $user->name = 'Cici RM';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);
        $request = New UserRegisterRequest();
        $request->id = 'cici2';
        $request->name = 'Cici RM';
        $request->password = 'rahasia';

        $this->userService->register($request);


    }

    public function testLoginFailed()
    {
        $this->expectException(ValidationException::class);
        $login = new UserLoginRequest();
        $login->id = 'ian';
        $login->password = 'ian';

        $this->userService->login($login);
    }

    public function testLoginPasswordWrong()
    {
        $user = new User();
        $user->id = 'ian';
        $user->name = 'ian';
        $user->password = password_hash('ian', PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);
        $login = new UserLoginRequest();
        $login->id = 'ian';
        $login->password = 'salah';

        $this->userService->login($login);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = 'ian';
        $user->name = 'ian';
        $user->password = password_hash('ian', PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $login = new UserLoginRequest();
        $login->id = 'ian';
        $login->password = 'ian';

        $response = $this->userService->login($login);

        self::assertEquals($login->id, $response->user->id);
        self::assertTrue(password_verify($login->password, $response->user->password));
    }

    public function testUserProfileUpdateSuccess()
    {
        $user = new User();
        $user->id = 'ian';
        $user->name = 'ian';
        $user->password = password_hash('ian', PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = "Budi";

        $this->userService->updateProfile($request);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($request->name, $result->name);

    }

    public function testNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = 'ian';
        $request->name = "Budi";

        $this->userService->updateProfile($request);
    }

    public function testRequestUserUpdateProfile()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = '';
        $request->name = "";

        $this->userService->updateProfile($request);
    }

    public function testUserProfileUpdatePassword()
    {
        $user = new User();
        $user->id = 'ian';
        $user->name = 'ian';
        $user->password = password_hash('ian', PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserProfileUpdatePasswordRequest();
        $request->id = $user->id;
        $request->oldPassword  = "ian";
        $request->newPassword  = "rahasia";

        $this->userService->updateProfilePassword($request);

        $result = $this->userRepository->findById($user->id);
        self::assertTrue(password_verify($request->newPassword, $result->password));
        self::assertEquals($request->id, $result->id);
        self::assertEquals($user->name, $result->name);
    }
}
