<?php

namespace PatitoOnlineJudge\Core\Domain\DomainObjects;

class ScoreDomainObject
{
    public $solved = 0;
    public $time = 0;
    public $p_wa_num;
    public $p_ac_sec;
    public $user_id;
    public $nick;
    public $lastname;
    public $pass_rate;
    public $points;
    public $p_virtual_num;

    public function __construct()
    {
        $this->solved = 0;
        $this->time = 0;
        $this->p_wa_num  = array();
        $this->p_ac_sec  = array();
        $this->pass_rate = array();
        $this->points    = 0;
        $this->p_virtual_num = array();
    }

    public function Add($pid, $sec, $res, $pass_rate = 0, $obi = 0, $is_virtual)
    {
        $this->p_virtual_num[$pid] = $is_virtual;
        if ($obi == 0) {
            if (isset($this->p_ac_sec[$pid]) && $this->p_ac_sec[$pid] > 0) {
                return;
            }
        }
        if ($res != 4) {
            if (isset($this->p_wa_num[$pid])) {
                $this->p_wa_num[$pid]++;
            } else {
                $this->p_wa_num[$pid] = 1;
            }
        } else {
            if ($obi ==  1) {
                $pass_rate = (100 - $pass_rate * 100);
                if ($pass_rate == 1) {
                    $pass_rate = 100;
                }
                if ($this->p_ac_sec[$pid] == 0) {
                    $this->solved++;
                    $this->p_ac_sec[$pid]  = $sec;
                }
                if (($pass_rate) > $this->pass_rate[$pid]) {
                    $this->p_ac_sec[$pid]  = $sec;
                    $this->pass_rate[$pid] = $pass_rate;
                }
            } else {
                $this->p_ac_sec[$pid]  = $sec;
                $this->solved++;
            }
            $this->points = 0;
            foreach ($this->pass_rate as $index => $value) {
                $this->points += $value;
            }

            if (!isset($this->p_wa_num[$pid])) {
                $this->p_wa_num[$pid] = 0;
            }
            $this->time = 0;
            foreach ($this->p_ac_sec as $index => $value) {
                $this->time += $value;
            }
            //$this->time += $sec ;//+ $this->p_wa_num[$pid];//*1200;
        }
    }


    public function s_cmp($A, $B)
    {
        if ($A->solved != $B->solved) {
            return $B->solved <=> $A->solved;
        } else {
            return $A->time <=> $B->time;
        }
    }

    public function points_cmp($A, $B)
    {
        if ($A->points != $B->points) {
            return $B->points <=> $A->points;
        } else {
            return $A->time <=> $B->time;
        }
    }
}
