<?php

namespace App\Request;

class RequestResolver {

    public function getParams(): array
    {
        return $_REQUEST;
    }
}