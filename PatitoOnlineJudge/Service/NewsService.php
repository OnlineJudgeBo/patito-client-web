<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\NewsRepository;

class NewsService
{
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getLatestNews()
    {
        return $this->newsRepository->getLatestNews();
    }
}
