<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Service;

use Exception;
use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Domain\User;
use IanSeptiana\PHP\MVC\LOGIN\Exception\ValidationException;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserLoginRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserLoginResponse;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdatePasswordRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdatePasswordResponse;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserRegisterRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserRegisterResponse;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdateRequest;
use IanSeptiana\PHP\MVC\LOGIN\Model\UserProfileUpdateResponse;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;

/**
 * menangani function logic application 
 */
class UserService
{
    protected UserRepository $userRepository;

    /**
     * set UserRepository
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * function register & validation parameter register
     *
     * @param UserRegisterRequest $register
     * @return UserRegisterResponse
     */
    public function register(UserRegisterRequest $register): UserRegisterResponse
    {
        $this->validationRegisterRequest($register);
        
        try {
            Database::beginTransaction();

            $findId = $this->userRepository->findById($register->id);
            if ($findId != null) {
                throw new ValidationException("User id aleady exists");
            }

            $user = new User();
            $user->id = $register->id;
            $user->name = $register->name;
            $user->password = password_hash($register->password, PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);
            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
            Database::closeDB();
            return $response;

        } catch (Exception $e) {
            Database::rollBackTransaction();
            Database::closeDB();
            throw $e;
        }

    }

    /**
     * validadation parameter
     *
     * @param UserRegisterRequest $register
     * @return void
     */
    private function validationRegisterRequest(UserRegisterRequest $register)
    {
        if ($register->id == null || $register->name == null || $register->password == null ||
            trim($register->id == "") || trim($register->name == "") || trim($register->password == "")){
            throw new ValidationException("Id, Name, Password can not blank");
            
        }
    }

    /**
     * Undocumented function
     *
     * @param UserLoginRequest $login
     * @return UserLoginResponse
     */
    public function login(UserLoginRequest $login): UserLoginResponse
    {
        $this->validationLoginRequest($login);

        $findId = $this->userRepository->findById($login->id);
        if ($findId == null) {
            throw new ValidationException("User Is Not Found");
        }

        if (password_verify($login->password, $findId->password)) {
            $response = new UserLoginResponse();
            $response->user = $findId;
            return $response;
        }else{
            throw new ValidationException("Id Or Password Is Wrong");
        }  
    }

    /**
     * Undocumented function
     *
     * @param UserLoginRequest $login
     * @return void
     */
    private function validationLoginRequest(UserLoginRequest $login)
    {
        if ($login->id == null || $login->password == null ||
            trim($login->id == "") || trim($login->password == "")){
            throw new ValidationException("Id Or Password can not blank");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validationProfileUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User Is Not Found");
            }

            $user->name = $request->name;

            $this->userRepository->update($user);

            Database::commitTransaction();
            
            $response = new UserProfileUpdateResponse();
            $response->user = $user;


            return $response;

        } catch (Exception $e) {
            Database::rollBackTransaction();

            throw $e;
        }
    }

    private function validationProfileUpdateRequest(UserProfileUpdateRequest $request)
    {
        if ($request->id == null || $request->name == null ||
            trim($request->id == "") || trim($request->name == "")){
            throw new ValidationException("Id Or Name can not blank");
        }
    }

    public function updateProfilePassword(UserProfileUpdatePasswordRequest $request): UserProfileUpdatePasswordResponse
    {
        $this->validationProfileUpdatePasswordRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);

            if ($user == null) {
                throw new ValidationException("User Is Not Found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password is wrong");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdatePasswordResponse();
            $response->user = $user;

            return $response;

        } catch (Exception $exception) {
            Database::rollBackTransaction();
            throw $exception;
        }
    }

    private function validationProfileUpdatePasswordRequest(UserProfileUpdatePasswordRequest $request)
    {
        if ($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id == "") || trim($request->oldPassword == "") || trim($request->newPassword == "")){
            throw new ValidationException("Id Or Password can not blank");
        }
    }
}
