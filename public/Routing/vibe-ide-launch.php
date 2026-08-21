<?php

require_once __DIR__ . '/container.php';

use PatitoOnlineJudge\Core\Application\Services\VibeIdeHandoffTokenService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;

/** @var IProblemService $problemService */
$problemService = $container->get(IProblemService::class);
/** @var IContestService $contestService */
$contestService = $container->get(IContestService::class);
$tokenService = new VibeIdeHandoffTokenService();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo 'Debe iniciar sesión para usar IDE.';
    exit;
}

$cid = isset($_GET['contestId']) ? intval($_GET['contestId']) : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
$pid = isset($_GET['num']) ? intval($_GET['num']) : (isset($_GET['pid']) ? intval($_GET['pid']) : 0);
$id = isset($_GET['problemId']) ? intval($_GET['problemId']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$courseId = isset($_GET['courseId']) ? intval($_GET['courseId']) : 0;
$assignmentId = isset($_GET['assignmentId']) ? intval($_GET['assignmentId']) : 0;
$languageId = isset($_GET['languageId']) ? intval($_GET['languageId']) : null;
$languageName = isset($_GET['languageName']) ? trim(strval($_GET['languageName'])) : '';
$siteId = intval($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? getenv('SITE_ID') ?: 1);

try {
    if ($cid > 0) {
        if ($pid < 0 || (!$contestService->isContestActive($cid) && !$contestService->isVirtualContest($cid))) {
            throw new RuntimeException('Contest no disponible para enviar.');
        }

        $problemId = intval($contestService->getProblemIdByNum($cid, $pid));
        if ($problemId <= 0) {
            throw new RuntimeException('Problema de contest no encontrado.');
        }

        $languagesAvailable = $contestService->languagesAvailable($cid);
        $claims = [
            'sub' => strval($userId),
            'site_id' => $siteId,
            'problem_id' => $problemId,
            'contest_id' => $cid,
            'num' => $pid,
        ];
    } elseif ($courseId > 0 && $assignmentId > 0) {
        if ($id <= 0 || !$problemService->getProblemByAcademicAssignment($id, $courseId, $assignmentId, strval($userId))) {
            throw new RuntimeException('Problema no encontrado en este curso.');
        }

        $languagesAvailable = $contestService->languagesAvailable(0);
        $claims = [
            'sub' => strval($userId),
            'site_id' => $siteId,
            'problem_id' => $id,
            'course_id' => $courseId,
            'assignment_id' => $assignmentId,
        ];
    } else {
        if ($id <= 0 || !$problemService->getProblemById($id)) {
            throw new RuntimeException('Problema no encontrado.');
        }

        $languagesAvailable = $contestService->languagesAvailable(0);
        $claims = [
            'sub' => strval($userId),
            'site_id' => $siteId,
            'problem_id' => $id,
        ];
    }

    if ($languageId !== null && $languageId >= 0) {
        $claims['language_id'] = $languageId;
    }
    if ($languageName !== '') {
        $claims['language_name'] = $languageName;
    }

    $claims['allowed_languages'] = array_values(array_map(static fn ($language) => intval($language['language_id']), $languagesAvailable));
    $token = $tokenService->createLaunchToken($claims);
    $baseUrl = rtrim($_SERVER['VIBE_IDE_BASE_URL'] ?? $_ENV['VIBE_IDE_BASE_URL'] ?? getenv('VIBE_IDE_BASE_URL') ?: '/ide/', '/') . '/';

    header('Location: ' . $baseUrl . '?' . http_build_query(['token' => $token]));
    exit;
} catch (Throwable $error) {
    http_response_code(403);
    echo htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}
