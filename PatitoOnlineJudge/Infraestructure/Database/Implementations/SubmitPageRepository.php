<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PDO;

class SubmitPageRepository implements ISubmitPageRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function saveContestRequest($pid, $cid, $source)
    {


        if (!isset($pid)) {
            $sql = "INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
                VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
        } else {
            $sql = "INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
                VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
        }

    }

    public function saveProblemRequest($pid, $source)
    {
    }
}
