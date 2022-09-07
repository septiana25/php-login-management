<?php



namespace IanSeptiana\PHP\MVC\LOGIN\Middleware{

require_once __DIR__ . '/../../tests/Helper/Helper.php';

    use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
    use IanSeptiana\PHP\MVC\LOGIN\Domain\Session;
    use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
    use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
    use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
    use IanSeptiana\PHP\MVC\LOGIN\Service\SessionService;
    use IanSeptiana\PHP\MVC\LOGIN\Middleware\MustNotLoginMiddelware;
    use PHPUnit\Framework\TestCase;

    class MustNotLoginMiddleware extends TestCase
    {
        //private SessionService $sessionService;
        private SessionRepository $sessionRepository;
        private UserRepository $userRespository;
        private MustNotLoginMiddelware $middleware;

        
        public function setUp(): void
        {
            $this->userRespository = new UserRepository(Database::getConection());
            $this->sessionRepository = new SessionRepository(Database::getConection());
            //$this->sessionService = new SessionService($this->sessionRepository, $this->userRespository);
            $this->middleware = new MustNotLoginMiddelware();

            putenv("mode=test");

            $this->sessionRepository->deleteAll();
            $this->userRespository->deleteAll();
        }

        public function testMiddleMemeber()
        {
            $user = new User();
            $user->id = "ian";
            $user->name = "ian";
            $user->password = "ian";

            $this->userRespository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = "ian";

            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->middleware->before();

            $this->expectOutputRegex("[]");

        }

        public function testMiddleGuest()
        {
            $this->middleware->before();

            $this->expectOutputRegex("[Location: /users/login]");
        }
    }
}