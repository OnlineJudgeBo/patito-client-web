<?php

namespace PatitoOnlineJudge\Core\Application\Validators;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class UserValidator {
    protected $userRepository;

    public function __construct(ILoginRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function validate(UserDomainObject $userData) {
        $this->validateUsernameUniqueness($userData->userId);
        $this->validateEmailUniqueness($userData->email);
    }

    protected function validateUsernameUniqueness($username) {
        if ($this->userRepository->existsByUserId($username)) {
            throw new \Exception("El nombre de usuario {$username} ya está en uso.");
        }
    }

    protected function validateEmailUniqueness($email) {
        if ($this->userRepository->existsByEmail($email)) {
            throw new \Exception("El correo electrónico {$email} ya está registrado.");
        }
    }
}