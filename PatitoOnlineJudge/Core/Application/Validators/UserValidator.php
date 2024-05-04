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
        $this->validateEmailUnique($userData->email);
    }

    public function validateProfileToUpdate(UserDomainObject $userData)
    {
        $this->validateName($userData->nick);
        $this->validateUsernameNotEmpty($userData->nick);

        $this->validateName($userData->lastname, "Apellido del usuario");
        $this->validateUsernameNotEmpty($userData->lastname, "Apellido del usuario");

        $this->validateEmailUniqueToChange($userData->userId, $userData->email);
    }

    protected function validateUsername($username, $field = "nombre de usuario")
    {
        if (strpos($username, ' ') !== false) {
            throw new \Exception("El $field no tiene que tener espacios.");
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            throw new \Exception("El $field contiene caracteres no permitidos.");
        }

        if (strlen($username) < 3) {
            throw new \Exception("El $field es muy corto, mínimo 3 caracteres");
        }
    }

    protected function validateName($username, $field = "nombre de usuario")
    {
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $username)) {
            throw new \Exception("El $field contiene caracteres no permitidos.");
        }

        if (strlen($username) < 3) {
            throw new \Exception("El $field es muy corto, mínimo 3 caracteres");
        }
    }

    protected function validateUsernameNotEmpty($username, $field = "nombre de usuario")
    {
        if (empty(rtrim(trim($username)))) {
            throw new \Exception("El $field no puede estar vacio.");
        }
    }

    protected function validateUsernameUniqueness($username)
    {
        if ($this->userRepository->existsByUserId($username)) {
            throw new \Exception("El nombre de usuario {$username} ya está en uso.");
        }
    }

    protected function validateEmailUnique($email)
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new \Exception("El correo electrónico {$email} ya está registrado.");
        }
    }

    protected function validateEmailUniqueToChange($email, $userId)
    {
        if ($this->userRepository->isEmailAvailableForChange($email, $userId)) {
            throw new \Exception("El correo electrónico {$email} ya se encuentra en uso por otro usuario registrado.");
        }
    }    
}
