<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\INewsRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;

class NewsService implements INewsService
{
    private $newsRepository;

    public function __construct(INewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getLatestNews()
    {
        return $this->newsRepository->getLatestNews();
    }
}
