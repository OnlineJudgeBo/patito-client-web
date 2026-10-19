<?php

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

require_once __DIR__ . '/container.php';

// Public problems and the official contest archive, the pages worth finding on Google.
$base = Utils::siteBaseUrl();
$urls = [
    ["$base/", null],
    ["$base/problemset.php", null],
    ["$base/icpc_contest.php", null],
];
foreach ($container->get(IProblemService::class)->getSitemapProblems() as $problem) {
    $urls[] = ["$base/problem.php?id=" . intval($problem["problem_id"]), $problem["in_date"]];
}
foreach ($container->get(IContestService::class)->getAllContestDetails("official") as $contest) {
    $urls[] = ["$base/contest.php?cid=" . intval($contest["contest_id"]) . "&type=official_contest", null];
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$loc, $lastmod]) {
    echo '  <url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc>';
    if ($lastmod && strtotime($lastmod) > 0) {
        echo '<lastmod>' . date('Y-m-d', strtotime($lastmod)) . '</lastmod>';
    }
    echo "</url>\n";
}
echo "</urlset>\n";
