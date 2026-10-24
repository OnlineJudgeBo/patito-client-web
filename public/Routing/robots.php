<?php

use PatitoOnlineJudge\Presentation\Utils\Utils;

// Search engines may read the public pages; personal and action pages stay out.
$prefix = rtrim($_SERVER['APP_PREFIX_ROUTE'] ?? $_ENV['APP_PREFIX_ROUTE'] ?? '', '/');
header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\n";
foreach (['status.php', 'submitpage.php', 'showsource.php', 'diff_code.php', 'login.php', 'registerpage.php', 'lostpassword.php', 'logout.php', 'vibe-ide-launch.php', 'contestrankExcel.php', 'google-login.php', 'google-callback.php', 'google-register.php'] as $page) {
    echo "Disallow: $prefix/$page\n";
}
echo "Allow: /\n\n";
echo "Sitemap: " . Utils::siteBaseUrl() . "/sitemap.xml\n";
