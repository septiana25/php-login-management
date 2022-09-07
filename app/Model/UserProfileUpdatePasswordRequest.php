<?php

namespace IanSeptiana\PHP\MVC\LOGIN\Model;



class UserProfileUpdatePasswordRequest  
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}
