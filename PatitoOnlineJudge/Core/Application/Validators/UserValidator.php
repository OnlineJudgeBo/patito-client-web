<?php

namespace PatitoOnlineJudge\Core\Application\Validators;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class UserValidator
{
    protected $userRepository;

    public function __construct(ILoginRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate(UserDomainObject $userData)
    {
        $this->validateUsername($userData->userId);
        $this->validateUsernameNotEmpty($userData->userId);
        $this->validateUsernameUniqueness($userData->userId);
        $this->validateEmailUniqueness($userData->email);
    }

    protected function validateUsername($username)
    {
        if (strpos($username, ' ') !== false) {
            throw new \Exception("El nombre de usuario no tiene que tener espacios.");
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            throw new \Exception("El nombre de usuario contiene caracteres no permitidos.");
        }

        if (strlen($username) < 3) {
            throw new \Exception("El nombre de usuarios es muy corto, minimo es 3 caracteres");
        }
    }

    protected function validateUsernameNotEmpty($username)
    {
        if (empty(rtrim(trim($username)))) {
            throw new \Exception("El nombre de usuario no puede estar vacio.");
        }
    }

    protected function validateUsernameUniqueness($username)
    {
        if ($this->userRepository->existsByUserId($username)) {
            throw new \Exception("El nombre de usuario {$username} ya está en uso.");
        }
    }

    protected function validateEmailUniqueness($email)
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new \Exception("El correo electrónico {$email} ya está registrado.");
        }
    }
}
