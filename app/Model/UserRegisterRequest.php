<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Model;


/**
 * parameter yang dibutuhkan function register, dibuat class supaya tidak banyak parameter yg diketik
 * parameter bisa null
 */
class UserRegisterRequest  
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $password = null;

}
