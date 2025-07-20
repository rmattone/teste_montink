<?php

namespace app\helper;

class SessionManager
{
    public static function data(): ?array
    {
        return $_SESSION ?? null;
    }
}
