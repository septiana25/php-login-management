<?php
namespace IanSeptiana\PHP\MVC\LOGIN\App{

    function header(string $values){
        echo $values;
    }
}

namespace IanSeptiana\PHP\MVC\LOGIN\Service{

    function setcookie(string $name, string $value){
        echo "$name: $value";
    }
}