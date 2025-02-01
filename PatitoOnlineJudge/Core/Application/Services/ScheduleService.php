<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IScheduleRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IScheduleService;

class ScheduleService implements IScheduleService
{
    private $scheduleRepository;

    public function __construct(
        IScheduleRepository $scheduleRepository,
    ) {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getSchedule()
    {
        return $this->scheduleRepository->getSchedule();
    }
}
