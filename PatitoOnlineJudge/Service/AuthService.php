<?php
namespace PatitoOnlineJudge\Service;

class AuthService {

    public function generatePasswordHash($password, $isMd5 = false) {
        if (!$isMd5) {
            $password = md5($password);
        }
        $salt = sha1(rand());
        $salt = substr($salt, 0, 4);
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    public function verifyPassword($password, $savedHash) {
        if ($this->isOldPassword($savedHash)) {
            return md5($password) === $savedHash;
        }
        $decoded = base64_decode($savedHash);
        $salt = substr($decoded, 20);
        $hash = base64_encode(sha1(md5($password) . $salt, true) . $salt);
        return strcmp($hash, $savedHash) === 0;
    }

    private function isOldPassword($passwordHash) {
        return ctype_xdigit($passwordHash) && strlen($passwordHash) === 32;
    }
}
