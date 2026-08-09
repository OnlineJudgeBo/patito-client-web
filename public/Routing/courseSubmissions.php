<?php

use PatitoOnlineJudge\Presentation\Controller\AcademicCourseController;

require_once __DIR__ . '/container.php';

(new AcademicCourseController())->renderSubmissions(
    (int) ($_GET['id'] ?? 0),
    (int) ($_GET['assignmentId'] ?? 0)
);
