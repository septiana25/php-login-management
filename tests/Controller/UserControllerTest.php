<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Controller{

require_once __DIR__ . '/../../tests/Helper/Helper.php';

use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
    use IanSeptiana\PHP\MVC\LOGIN\Domain\Session;
    use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
    use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
    use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
    use IanSeptiana\PHP\MVC\LOGIN\Service\SessionService;
    use PHPUnit\Framework\TestCase;

    class UserControllerTest extends TestCase
    {
        private UserController $userController;
        private UserRepository $userRepository;
        private SessionService $sessionService;
        private SessionRepository $sessionRepository;

        public function setUp(): void
        {
            $this->userRepository = New UserRepository(Database::getConection());
            $this->userController = new UserController();
            $this->sessionRepository = new SessionRepository(Database::getConection());
            $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();

            putenv('mode=test');
            
        }

        public function testRegister()
        {
            $this->userController->register();

            $this->expectOutputRegex('[Register]');

        }

        public function testRegisterFailed()
        {
            $_POST['id'] = '';
            $_POST['name'] = 'ian';
            $_POST['password'] = 'ian';
            $this->userController->postRegister();

            $this->expectOutputRegex('[Id, Name, Password can not blank]');
        }

        public function testRegisterDuplicate()
        {
            $user = new User();
            $user->id = 'ian';
            $user->name = 'ian';
            $user->password = 'ian';

            $this->userRepository->save($user);

            $_POST['id'] = 'ian';
            $_POST['name'] = 'ian';
            $_POST['password'] = 'ian';
            $this->userController->postRegister();

            $this->expectOutputRegex('[User id aleady exists]');
        }

        public function testLogin()
        {
            $this->userController->login();

            $this->expectOutputRegex('[User Login]');
            $this->expectOutputRegex('[Id]');
            $this->expectOutputRegex('[Password]');

        }

        public function testLoginFiledNull()
        {
            $_POST['id'] ='';
            $_POST['password'] ='';

            $this->userController->postLogin();

            $this->expectOutputRegex('[User Login]');
            $this->expectOutputRegex('[Id Or Password can not blank]');
        }

        public function testLoginNotFound()
        {
            $user = new User();
            $user->id = 'ian';
            $user->name = 'ian';
            $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $_POST['id'] ='ian';
            $_POST['password'] ='rahadasdsia';

            $this->userController->postLogin();

            $this->expectOutputRegex('[User Login]');
            $this->expectOutputRegex('[Id Or Password Is Wrong]');
        }

        public function testLoginSuccess()
        {
            $user = new User();
            $user->id = 'ian';
            $user->name = 'ian';
            $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $_POST['id'] ='ian';
            $_POST['password'] ='rahasia';

            $this->userController->postLogin();

            $this->expectOutputRegex('[User Login]');
            $this->expectOutputRegex('[/]');
        }

        public function testProfile()
        {
            $user = new User();
            $user->id = 'ian';
            $user->name = 'ian';
            $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;

            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userController->profile();

            $this->expectOutputRegex('[Profile]');
            $this->expectOutputRegex('[Id]');
            $this->expectOutputRegex('[Name]');
            $this->expectOutputRegex('[ian]');
            $this->expectOutputRegex('[Update Profile]');

        }

        public function testUpdateProfile()
        {
            $user = new User();
            $user->id = 'ian';
            $user->name = 'ian';
            $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;

            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['name'] = 'Ian Septiana';
            
            $this->userController->postProfile();

            $this->expectOutputRegex("[/]");

            $result = $this->userRepository->findById($user->id);
            self::assertEquals('Ian Septiana', $result->name);

        }

    }
};