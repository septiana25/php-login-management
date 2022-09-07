<?php 
namespace IanSeptiana\PHP\MVC\LOGIN\Controller;
use IanSeptiana\PHP\MVC\LOGIN\App\View;
use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use IanSeptiana\PHP\MVC\LOGIN\Repository\SessionRepository;
use IanSeptiana\PHP\MVC\LOGIN\Repository\UserRepository;
use IanSeptiana\PHP\MVC\LOGIN\Service\SessionService;

/**
 * atur folder & file yang akan di load dari folder view
 */
class HomeController
{
    protected SessionService $sessionSerive;

    public function __construct()
    {

        $sessonRepository = new SessionRepository(Database::getConection());
        $userRepository = new UserRepository(Database::getConection());
        $this->sessionSerive = new SessionService($sessonRepository, $userRepository);
    }



    function index() {
        
        $session = $this->sessionSerive->current();
        if ($session == null) {
            View::render('Home/index', [
                'title' => 'PHP Login Management'
            ]);
        }else{
            View::render('Home/dashboard', [
                'title' => 'Dashboard',
                'user' => [
                    'name' => $session->name
                ]
            ]);
        }


    }

}