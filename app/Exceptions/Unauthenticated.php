<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class Unauthenticated extends HttpException
{
    public function __construct()
    {
        $this->withStatusCode(Response::HTTP_UNAUTHORIZED)
            ->withMessage('Unauthenticated.');
    }
}
