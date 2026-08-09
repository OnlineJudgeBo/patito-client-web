<?php

use PatitoOnlineJudge\Presentation\Controller\AcademicCourseController;

require_once __DIR__ . '/container.php';

$controller = new AcademicCourseController();
$controller->renderRanking((int) ($_GET['id'] ?? 0));
