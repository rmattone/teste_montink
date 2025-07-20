<?php

namespace app\libraries;


class Funcoes_comuns
{
    public function __construct() {}

    public function convertTextToHtmlSafe($value)
    {
        return str_replace("\r", "<br>", htmlentities($value));
    }
}
