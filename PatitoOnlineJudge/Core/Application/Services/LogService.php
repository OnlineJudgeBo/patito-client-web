<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILogRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILogService;

class LogService implements ILogService
{

    protected $logRepository;

    public function __construct(ILogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function addRecordHistory()
    {
        $user_id = "";
        $ip = "";
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $remoteAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $tmp_ip = explode(',', $remoteAddr);
            $ip = $tmp_ip[0];
        }
        $refer = "";
        $ua = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
        $uri = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['HTTP_REFERER'])) {
            $refer = htmlspecialchars($_SERVER['HTTP_REFERER']);
        }
        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        $sessionId = session_id();

        $this->logRepository->addRecordHistory($sessionId, $user_id, $ip, $ua, $uri, $refer);
    }
}
