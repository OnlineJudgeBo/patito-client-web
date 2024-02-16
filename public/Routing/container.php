<?php

use DI\ContainerBuilder;
use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\{
    IContestRankService,
    IContestService,
    ILoginService,
    INewsService,
    IProblemService,
    IProblemStatusService,
    IRankListService,
    IShowSourceService,
    ISolutionService,
    IStatusService,
    ISubmitPageService
};
use PatitoOnlineJudge\Core\Application\Services\{
    ContestRankService,
    ContestService,
    LoginService,
    NewsService,
    ProblemService,
    ProblemStatusService,
    RankListService,
    ShowSourceService,
    SolutionService,
    StatusService,
    SubmitPageService
};
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\{
    IContestRepository,
    IContestRankRepository,
    ILoginRepository,
    INewsRepository,
    IProblemRepository,
    IProblemStatusRepository,
    IRankListRepository,
    IShowSourceRepository,
    ISolutionRepository,
    IStatusRepository,
    ISubmitPageRepository,
    IUserStaticRepository,
    ISourceCodeRepository
};
use PatitoOnlineJudge\Infraestructure\Database\Implementations\{
    ContestRepository,
    LoginRepository,
    NewsRepository,
    ProblemRepository,
    ProblemStatusRepository,
    RankListRepository,
    ShowSourceRepository,
    SolutionRepository,
    StatusRepository,
    SubmitPageRepository,
    ContestRankRepository,
    UserStaticRepository,
    SourceCodeRepository
};
use PatitoOnlineJudge\Presentation\Controller\ContestRankController;

require_once __DIR__ . '/../../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->addDefinitions([
    DatabaseConnector::class => \DI\create(DatabaseConnector::class),

    // Repositories
    IContestRepository::class => \DI\get(ContestRepository::class),
    IContestRankRepository::class => \DI\get(ContestRankRepository::class),
    ILoginRepository::class => \DI\get(LoginRepository::class),
    INewsRepository::class => \DI\get(NewsRepository::class),
    IProblemRepository::class => \DI\get(ProblemRepository::class),
    IProblemStatusRepository::class => \DI\get(ProblemStatusRepository::class),
    IRankListRepository::class => \DI\get(RankListRepository::class),
    IShowSourceRepository::class => \DI\get(ShowSourceRepository::class),
    ISolutionRepository::class => \DI\get(SolutionRepository::class),
    IStatusRepository::class => \DI\get(StatusRepository::class),
    ISubmitPageRepository::class => \DI\get(SubmitPageRepository::class),
    IUserStaticRepository::class => \DI\get(UserStaticRepository::class),
    ISourceCodeRepository::class => \DI\get(SourceCodeRepository::class),

    // Servicios
    IContestService::class => \DI\create(ContestService::class)->constructor(\DI\get(ContestRepository::class)),
    IContestRankService::class => \DI\create(ContestRankService::class)->constructor(\DI\get(ContestRankRepository::class)),
    ILoginService::class => \DI\create(LoginService::class)->constructor(\DI\get(LoginRepository::class), \DI\get(UserValidator::class)),
    INewsService::class => \DI\create(NewsService::class)->constructor(\DI\get(NewsRepository::class)),
    IProblemService::class => \DI\create(ProblemService::class)->constructor(\DI\get(ProblemRepository::class)),
    IProblemStatusService::class => \DI\create(ProblemStatusService::class)->constructor(\DI\get(ProblemStatusRepository::class)),
    IRankListService::class => \DI\create(RankListService::class)->constructor(\DI\get(RankListRepository::class)),
    IShowSourceService::class => \DI\create(ShowSourceService::class)->constructor(\DI\get(ShowSourceRepository::class)),
    ISolutionService::class => \DI\create(SolutionService::class)->constructor(\DI\get(SolutionRepository::class)),
    IStatusService::class => \DI\create(StatusService::class)->constructor(\DI\get(StatusRepository::class)),
    ISubmitPageService::class => \DI\create(SubmitPageService::class)->constructor(\DI\get(SubmitPageRepository::class), \DI\get(SourceCodeRepository::class), \DI\get(ContestService::class)),

    
    // Validator
    UserValidator::class => \DI\autowire()->constructor(\DI\get(ILoginRepository::class)),
]);

$container = $builder->build();

return $container;
