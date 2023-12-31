<?php

namespace App\Interfaces;

use App\Dto\LoginDto;
use App\Dto\LoginResultDto;
use App\Exceptions\InvalidAuthTokenException;
use App\Exceptions\InvalidLoginDataException;

interface AuthenticationInterface
{
    /**
     * @throws InvalidLoginDataException
     */
    public function login(LoginDto $dto): LoginResultDto;
    public function verifyIsLogged(): void;
}
