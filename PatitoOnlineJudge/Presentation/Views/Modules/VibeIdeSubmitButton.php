<?php
$prefixRoute = $_SERVER['APP_PREFIX_ROUTE'] ?? '/oj';

$vibeProblemId = intval($problem['problem_id'] ?? $id ?? 0);
$vibeContestId = intval($cid ?? 0);
$vibeNum = intval($num ?? $pid ?? 0);

$query = [];
if ($vibeContestId > 0) {
    $query['contestId'] = $vibeContestId;
    $query['cid'] = $vibeContestId;
    $query['num'] = $vibeNum;
    $query['pid'] = $vibeNum;
} else {
    $query['problemId'] = $vibeProblemId;
    $query['id'] = $vibeProblemId;
}

if (isset($language_id) && intval($language_id) >= 0) {
    $query['languageId'] = intval($language_id);
}

$vibeIdeLaunchUrl = $prefixRoute . '/vibe-ide-launch.php?' . http_build_query($query);
?>
<a
    id="VibeIdeSubmit"
    href="<?php echo htmlspecialchars($vibeIdeLaunchUrl, ENT_QUOTES, 'UTF-8'); ?>"
    target="_blank"
    rel="noopener noreferrer"
    onclick="event.stopPropagation(); updateVibeIdeLaunchLink();"
    class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
>
    IDE
</a>
<script>
(function () {
    const linkId = 'VibeIdeSubmit';
    window.updateVibeIdeLaunchLink = function updateVibeIdeLaunchLink() {
        const link = document.getElementById(linkId);
        if (!link) return true;
        const language = document.getElementById('language');
        if (!language || !language.value) return true;

        const url = new URL(link.getAttribute('href'), window.location.origin);
        url.searchParams.set('languageId', language.value);
        const selected = language.options && language.selectedIndex >= 0 ? language.options[language.selectedIndex] : null;
        const languageName = selected ? selected.text.trim() : '';
        if (languageName) url.searchParams.set('languageName', languageName);
        link.setAttribute('href', url.pathname + url.search + url.hash);
        return true;
    };
    window.updateVibeIdeLaunchLink();
}());
</script>
