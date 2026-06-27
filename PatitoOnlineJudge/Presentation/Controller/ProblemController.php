<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class ProblemController
{
    private IProblemService $problemService;
    private IContestService $contestService;
    public $title;
    private $cid;
    private $pid;
    private $cType;
    private $siteId;

    public function __construct(IProblemService $problemService, IContestService $contestService)
    {
        $this->title = "Problema";
        $this->problemService = $problemService;
        $this->contestService = $contestService;
    }

    public function setProblemId($pid)
    {
        $this->pid = $pid;
    }

    public function setContestId($cid)
    {
        $this->cid = $cid;
    }

    public function setCtype($cType)
    {
        $this->cType = $cType;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $isContestActive = true;
        if (intval($this->pid) >= 0 && intval($this->cid) > 0) {
            $num = $this->pid;
            $cid = $this->cid;
            $cType = $this->cType;
            $problem = $this->problemService->getProblemByContestId($this->cid, $this->pid, $this->cType);
            $isContestActive = $this->contestService->isContestActive($this->cid, $this->cType);
        } else {
            //try {
                $problem = $this->problemService->getProblemById($this->pid);
            //} catch (\Exception $e) {
                //$error = $e->getMessage();
                //require_once __DIR__."/../Views/genericError.php";
                //die();
            //}
        }
        $problem = $this->inlineDisplayMath($problem);
        require_once $current_theme . "/problem.php";
    }

    private function inlineDisplayMath($problem)
    {
        $fields = ["description", "input", "output", "hint"];

        if (!is_array($problem)) {
            return $problem;
        }

        foreach ($fields as $field) {
            if (isset($problem[$field]) && is_string($problem[$field])) {
                $problem[$field] = $this->inlineMixedParagraphDisplayMath($problem[$field]);
            }
        }

        return $problem;
    }

    private function inlineMixedParagraphDisplayMath(string $html): string
    {
        return preg_replace_callback('/<p\b([^>]*)>(.*?)<\/p>/is', function ($paragraphMatch) {
            $content = $paragraphMatch[2];

            if (!preg_match('/\\\[[\s\S]+?\\\]/', $content)) {
                return $paragraphMatch[0];
            }

            $textOutsideMath = preg_replace('/\\\[[\s\S]+?\\\]/', '', strip_tags($content));
            $textOutsideMath = trim(html_entity_decode($textOutsideMath ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            if ($textOutsideMath === '') {
                return $paragraphMatch[0];
            }

            $content = str_replace(['\\[', '\\]'], ['\\(', '\\)'], $content);

            return '<p' . $paragraphMatch[1] . '>' . $content . '</p>';
        }, $html);
    }
}
