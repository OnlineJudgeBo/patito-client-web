<?php
// Link preview for a contest page (problem list, ranking, or the "private" error page).
// Set $seoContest (contest row) and optionally $seoKind ("Ranking") before including it.
// Only official contests are indexed: the rest are private or hidden behind a link.
if (!empty($seoContest)) {
    $seoMonths = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    $seoDate = fn($time) => date("j", $time) . " " . $seoMonths[date("n", $time) - 1] . " " . date("Y, H:i", $time);
    $seoStart = strtotime($seoContest["start_time"]);
    $seoEnd = strtotime($seoContest["end_time"]);
    $seoStatus = time() < $seoStart ? "Próximamente" : (time() <= $seoEnd ? "En curso" : "Finalizado");
    $seoOfficial = ($seoContest["defunct"] ?? "") === "O";

    $seoTitle = (($seoKind ?? "") === "Ranking" ? "🏆 Ranking: " : "🏁 Concurso: ") . strip_tags($seoContest["title"]);
    $seoDescription = $seoStatus . " · " . ($seoContest["private"] == 0 ? "Público" : "Privado")
        . " · Del " . $seoDate($seoStart) . " al " . $seoDate($seoEnd) . ". "
        . (($seoKind ?? "") === "Ranking" ? "Mira la clasificación del concurso." : "Entra, resuelve los problemas y compite en vivo.");
    $seoNoindex = !empty($seoNoindex) || !$seoOfficial;
    $seoPath = (($seoKind ?? "") === "Ranking" ? "contestrank.php" : "contest.php")
        . "?cid=" . intval($seoContest["contest_id"]) . (($seoKind ?? "") === "Ranking" ? "" : "&type=official_contest");
}
require __DIR__ . "/seo-meta.php";
