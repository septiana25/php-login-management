<?php

namespace IanSeptiana\PHP\MVC\LOGIN\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('Home/index', ['title' => 'PHP LOGIN MANAGEMENT']);

        $this->expectOutputRegex('[PHP LOGIN MANAGEMENT]');
        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[Register]');
    }

    
}
