<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Presentation\Utils\Utils;

class AcademicCourseController
{
    public function render(): void
    {
        $currentTheme = Utils::get_current_theme();
        $title = 'Cursos académicos';
        $apiUrl = rtrim((string) ($_SERVER['APP_DOMAIN_API'] ?? $_ENV['APP_DOMAIN_API'] ?? '/api'), '/');
        $siteId = (int) ($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? 1);
        $adminUrl = rtrim((string) ($_SERVER['APP_DOMAIN_ADMIN'] ?? $_ENV['APP_DOMAIN_ADMIN'] ?? '/admin'), '/');
        $canCreateCourses =
            (isset($_SESSION['Administrador']) && $_SESSION['Administrador'] === 'Administrador') ||
            (isset($_SESSION['Docente']) && $_SESSION['Docente'] === 'Docente') ||
            (isset($_SESSION['Auxiliar']) && $_SESSION['Auxiliar'] === 'Auxiliar');

        require $currentTheme . '/courses.php';
    }

    public function renderDetail(int $courseId): void
    {
        if ($courseId <= 0) {
            http_response_code(400);
            echo 'Curso inválido.';
            return;
        }

        $currentTheme = Utils::get_current_theme();
        $title = 'Contenido del curso';
        $apiUrl = rtrim((string) ($_SERVER['APP_DOMAIN_API'] ?? $_ENV['APP_DOMAIN_API'] ?? '/api'), '/');
        $siteId = (int) ($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? 1);
        $assignmentId = max(0, (int) ($_GET['assignmentId'] ?? 0));

        require $currentTheme . '/course.php';
    }

    public function renderContest(int $courseId, int $assignmentId): void
    {
        if ($courseId <= 0 || $assignmentId <= 0) {
            http_response_code(400);
            echo 'Contest inválido.';
            return;
        }

        $currentTheme = Utils::get_current_theme();
        $title = 'Contest del curso';
        $apiUrl = rtrim((string) ($_SERVER['APP_DOMAIN_API'] ?? $_ENV['APP_DOMAIN_API'] ?? '/api'), '/');
        $siteId = (int) ($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? 1);

        require $currentTheme . '/course-contest.php';
    }

    public function renderRanking(int $courseId): void
    {
        if ($courseId <= 0) {
            http_response_code(400);
            echo 'Curso inválido.';
            return;
        }

        $currentTheme = Utils::get_current_theme();
        $title = 'Ranking del curso';
        $apiUrl = rtrim((string) ($_SERVER['APP_DOMAIN_API'] ?? $_ENV['APP_DOMAIN_API'] ?? '/api'), '/');
        $siteId = (int) ($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? 1);
        $assignmentId = max(0, (int) ($_GET['assignmentId'] ?? 0));

        require $currentTheme . '/courseRanking.php';
    }

    public function renderSubmissions(int $courseId, int $assignmentId): void
    {
        if ($courseId <= 0 || $assignmentId < 0) {
            http_response_code(400);
            echo 'Contest académico inválido.';
            return;
        }

        $currentTheme = Utils::get_current_theme();
        $title = 'Envíos del contest';
        $apiUrl = rtrim((string) ($_SERVER['APP_DOMAIN_API'] ?? $_ENV['APP_DOMAIN_API'] ?? '/api'), '/');
        $siteId = (int) ($_SERVER['SITE_ID'] ?? $_ENV['SITE_ID'] ?? 1);
        require $currentTheme . '/courseSubmissions.php';
    }
}
