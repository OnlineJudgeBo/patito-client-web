<?php
// <title> and the tags Google and link previews read. Set $seoTitle, and optionally $seoDescription,
// $seoPath (page path for the canonical link, e.g. "problem.php?id=5") and $seoNoindex, before including it.
use PatitoOnlineJudge\Presentation\Utils\Utils;

$seoSiteName = "Juez Virtual Patito";
$seoFullTitle = trim(($seoTitle ?? "") !== "" ? $seoTitle . " | " . $seoSiteName : $seoSiteName);
$seoText = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($seoDescription ?? ""), ENT_QUOTES, 'UTF-8')));
if (mb_strlen($seoText) > 160) {
    $seoText = rtrim(mb_substr($seoText, 0, 157)) . "…";
}
$seoCanonical = isset($seoPath) && empty($seoNoindex) ? Utils::siteBaseUrl() . "/" . ltrim($seoPath, "/") : null;
$e = fn($text) => htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
?>
<title><?php echo $e($seoFullTitle) ?></title>
<?php if ($seoText !== "") { ?>
    <meta name="description" content="<?php echo $e($seoText) ?>">
    <meta property="og:description" content="<?php echo $e($seoText) ?>">
<?php } ?>
<meta property="og:title" content="<?php echo $e($seoFullTitle) ?>">
<meta property="og:site_name" content="<?php echo $e($seoSiteName) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="es_BO">
<meta property="og:image" content="<?php echo $e(Utils::siteBaseUrl() . "/assets/og-image.png") ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<?php if ($seoCanonical) { ?>
    <link rel="canonical" href="<?php echo $e($seoCanonical) ?>">
    <meta property="og:url" content="<?php echo $e($seoCanonical) ?>">
<?php } ?>
<?php if (!empty($seoNoindex)) { ?>
    <meta name="robots" content="noindex">
<?php } ?>
