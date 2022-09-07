<?php
namespace IanSeptiana\PHP\MVC\LOGIN\App;

class Router  
{
    private static array $routers = [];

    /**
     * set semua parameter untuk proses routing
     * kembalikan error 404 jika class/function tidak ada
     *
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $function
     * @param array $middlewares
     * @return void
     */
    public static function add( string $method, 
                                string $path, 
                                string $controller, 
                                string $function,
                                array  $middlewares = [])
    {
        self::$routers[] = [
            "method" => $method,
            "path" => $path,//regex
            "controller" => $controller,
            "function" => $function,
            "middlewares" => $middlewares
        ];
    }

    /**
     * Jalankan class, function & tampung value dengan funsi regex 
     *
     * @return void
     */
    public static function run()
    {
        $path = "/";
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        }

        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routers as $route) {
            /* #-> tanda regex (karakter bebas jarang di gunakan), 
            ^ -> awal regex
            $ -> akhir regex 
            * -> bisa lebih dari 1*/
            $pattern = '#^' . $route['path'] . '$#';
            //$path == $route['path'] ganti jadi preg_match 
            if (preg_match($pattern, $path, $variabels) && $method == $route['method']) {
                foreach ($route['middlewares'] as $middleware) {
                    $instance = new $middleware;
                    $instance->before();
                }

                //$function = 'index' -> nama function
                $function = $route['function'];
                //$controller = new IanSeptiana\PHP\MVC\LOGIN\Controller\HomeController -> class + namespace
                $controller = new $route['controller'];
                //$controller->$function();

                array_shift($variabels);//hapus index pertama
                call_user_func_array([$controller, $function], $variabels);
                return;
            }
        }
        
        http_response_code(404);
        echo "CONTROLLER NOT FOUND";

    }

}
