<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Controller;


use IanSeptiana\PHP\MVC\LOGIN\App\View;
use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Exception\ValidationException;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserLoginRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdatePasswordRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdateRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserRegisterRequest;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
use IanSeptiana\PHP\MVC\LOGIN\Service\SessionService;
use IanSeptiana\PHP\MVC\LOGIN\Service\UserService;

class UserController  
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $conn = Database::getConection();
        $userRepository = new UserRepository($conn);
        $this->userService = New UserService($userRepository);

        $sessionRepository = new SessionRepository($conn);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    /**
     * menampilkan form registernya
     *
     * @return void
     */
    public function register()
    {
        View::render('User/register', [
            'title' => 'Form Registrasion'
        ]);
    }

    /**
     * menangani aksi registernya
     *
     * @return void
     */
    public function postRegister()
    {
        $request = New UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch (ValidationException $e) {
            View::render('User/register', [
                'title' => 'Form Registrasion',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function login()
    {

        View::render('User/login', [
            'title' => 'User Login'
        ]);

        
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $result = $this->userService->login($request);
            $this->sessionService->create($result->user->id);

            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('User/login', [
                'title' => 'User Login',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function profile()
    {
        $user = $this->sessionService->current();
        View::render("/User/profile", [
            'title' => 'Profile',
            'user' => [
                'id' => $user->id,
                'name' => $user->name
            ]
        ]);
    }
    public function postProfile()
    {
        $user = $this->sessionService->current();

        try {
            $request = new UserProfileUpdateRequest();
            $request->id = $user->id;
            $request->name = $_POST['name'];

            $this->userService->updateProfile($request);

            View::redirect('/');
        } catch (ValidationException $e) {
            
            View::render("/User/profile", [
                'title' => 'Profile',
                'error' => $e->getMessage(),
                'user' => [
                    'id' => $user->id,
                    'name' => $_POST['name']
                ]
            ]);
        }
    }

    public function profilePassword()
    {
        $user = $this->sessionService->current();
        View::render('/User/password', [
            'title' => 'Change Password',
            'user' => [
                'id' => $user->id
            ]
        ]);
    }

    public function postProfilePassword()
    {
        $user = $this->sessionService->current();

        try {
            $request = new UserProfileUpdatePasswordRequest();
            $request->id = $user->id;
            $request->oldPassword = $_POST['oldPassword'];
            $request->newPassword = $_POST['newPassword'];

            $this->userService->updateProfilePassword($request);

            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('/User/password', [
                'title' => 'Change Password',
                'error' => $exception->getMessage(),
                'user' => [
                    'id' => $user->id
                ]
            ]);
        }
    }
}
