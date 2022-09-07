<?php
namespace IanSeptiana\PHP\MVC\LOGIN\App;

class View 
{
    /**
     * load semua file dari folder view berdasarkan parameter, tampung juga data array
     *
     * @param string $view
     * @param [type] $model
     * @return void
     */
    public static function render(string $view, $model){
        require __DIR__ . "/../View/header.php";
        require __DIR__ . "/../View/" . $view . ".php";
        require __DIR__ . "/../View/footer.php";
    }

    public static function redirect(string $url)
    {
        header("Location: $url");
        if (getenv("mode") != "test") {
            exit;
        }
    }
}