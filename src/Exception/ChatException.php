<?php

namespace App\Exception;

class ChatException extends \Exception
{
    public static function forbiddenException()
    {
        return new static('User can not access this chat', 403);
    }

    public static function userNotFoundException()
    {
        return new static('user not found', 404);
    }
}
