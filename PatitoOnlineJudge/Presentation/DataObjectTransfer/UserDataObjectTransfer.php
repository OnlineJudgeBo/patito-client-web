<?php

namespace PatitoOnlineJudge\Infrastructure\Presentation\DataObjectTransfer;

class UserDataObjectTransfer
{
    public $userid;
    public $email;
    public $password;
    public $name;
    public $lastname;
    public $country;
    public $obi;

    public function addUserId($userid)
    {
        $this->userid = $userid;
    }

    public function addEmail($email)
    {
        $this->email = $email;
    }

    public function addPassword($password)
    {
        $this->password = $password;
    }

    public function addName($name)
    {
        $this->name = $name;
    }

    public function addLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function addCountry($country)
    {
        $this->country = $country;
    }

    public function addObi($obi)
    {
        $this->obi = $obi;
    }
}
