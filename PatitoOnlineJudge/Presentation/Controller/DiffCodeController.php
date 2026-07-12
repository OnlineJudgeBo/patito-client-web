<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IShowSourceService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class DiffCodeController
{
    public $title;
    public $solution_id = null;
    public $solution_id2 = null;
    private $showSourceService;

    public function __construct()
    {
        $this->title = "Comparar códigos";
    }

    public function addService(IShowSourceService $showSourceService)
    {
        $this->showSourceService = $showSourceService;
    }

    public function setSolution1($sid)
    {
        $this->solution_id = $sid;
    }

    public function setSolution2($sid)
    {
        $this->solution_id2 = $sid;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $sourceDetail = null;
        $sourceDetail2 = null;
        $comparison = null;
        $errorMessage = null;

        if (!$this->solution_id || !$this->solution_id2) {
            $errorMessage = "Debe indicar dos soluciones válidas para comparar.";
            require_once $current_theme . "/diffCode.php";
            return;
        }

        $sourceDetail = $this->showSourceService->showCode($this->solution_id);
        $sourceDetail2 = $this->showSourceService->showCode($this->solution_id2);

        if (!$sourceDetail || !$sourceDetail2) {
            $errorMessage = "No se encontró una de las soluciones o no tiene permiso para verla.";
            require_once $current_theme . "/diffCode.php";
            return;
        }

        $comparison = $this->compareSources($sourceDetail, $sourceDetail2);
        require_once $current_theme . "/diffCode.php";
    }

    private function compareSources(array $sourceDetail, array $sourceDetail2): array
    {
        $source = (string)($sourceDetail["source"] ?? "");
        $source2 = (string)($sourceDetail2["source"] ?? "");
        $normalizedSource = $this->normalizeSource($source);
        $normalizedSource2 = $this->normalizeSource($source2);

        similar_text($normalizedSource, $normalizedSource2, $similarityPercentage);

        return [
            "same_problem" => (string)($sourceDetail["problem_id"] ?? "") === (string)($sourceDetail2["problem_id"] ?? ""),
            "same_language" => (string)($sourceDetail["language"] ?? "") === (string)($sourceDetail2["language"] ?? ""),
            "exact_match" => $source === $source2,
            "normalized_match" => $normalizedSource === $normalizedSource2,
            "similarity_percentage" => round($similarityPercentage, 2),
        ];
    }

    private function normalizeSource(string $source): string
    {
        $source = str_replace(["\r\n", "\r"], "\n", $source);
        return preg_replace('/\s+/', '', trim($source)) ?? '';
    }
}
