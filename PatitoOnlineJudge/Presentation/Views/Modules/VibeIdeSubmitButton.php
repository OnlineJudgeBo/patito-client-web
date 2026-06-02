<?php
$prefixRoute = $_SERVER['APP_PREFIX_ROUTE'] ?? '/oj';
$query = [];
if (isset($cid) && intval($cid) > 0) {
    $query['cid'] = intval($cid);
    $query['pid'] = intval($pid ?? $num ?? $id ?? 0);
} else {
    $query['id'] = intval($id ?? ($problem['problem_id'] ?? $pid ?? 0));
}
$vibeIdeLaunchUrl = $prefixRoute . '/vibe-ide-launch.php?' . http_build_query($query);
?>
<a
    id="VibeIdeSubmit"
    href="<?php echo htmlspecialchars($vibeIdeLaunchUrl, ENT_QUOTES, 'UTF-8'); ?>"
    target="_blank"
    rel="noopener noreferrer"
    onclick="event.stopPropagation();"
    class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
>
    Vibe IDE
</a>
