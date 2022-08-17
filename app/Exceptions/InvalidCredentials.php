<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidCredentials extends HttpException
{
    public function __construct()
    {
        $this->withStatusCode(Response::HTTP_UNAUTHORIZED)
            ->withMessage('Invalid credentials.');
    }
}
