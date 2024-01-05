 <?php
    $view_title = $MSG_CONTEST;

    function formatTimeLength($length)
    {
        $hour = 0;
        $minute = 0;
        $second = 0;
        $result = '';
        if ($length >= 60) {
            $second = $length % 60;
            if ($second > 0) {
                $result = $second . 's ';
            }
            $length = floor($length / 60);
            if ($length >= 60) {
                $minute = $length % 60;
                if ($minute == 0) {
                    if ($result != '') {
                        $result = '0m ' . $result;
                    }
                } else {
                    $result = $minute . 'm ' . $result;
                }
                $length = floor($length / 60);
                if ($length >= 24) {
                    $hour = $length % 24;
                    if ($hour == 0) {
                        if ($result != '') {
                            $result = '0h ' . $result;
                        }
                    } else {
                        $result = $hour . 'h ' . $result;
                    }
                    $length = floor($length / 24);
                    $result = $length . 'd ' . $result;
                } else {
                    $result = $length . 'h ' . $result;
                }
            } else {
                $result = $length . 'm ' . $result;
            }
        } else {
            $result = $length . 's ';
        }
        return "<nowdate class='nowdate'>".$result."</nowdate>";
    }

    function closetags($html)
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];

        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    $sql = "SELECT *
    FROM `contest`
    WHERE `defunct`='N'
    ORDER BY `contest_id`
    DESC limit 100";
    $result = mysql_query($sql);

    $view_contest = array();
    $i = 0;
    while ($row = mysql_fetch_object($result)) {

        $start_time = strtotime($row->start_time);
        $end_time   = strtotime($row->end_time);
        $now        = time();
        $length     = $end_time - $start_time;
        $left       = $end_time - $now;

        // past
        if ($now > $end_time) {
            continue;
        } else {
            // pending
            $view_contest[$i]["contest_id"] = $row->contest_id;
            $view_contest[$i]["title"]      = closetags($row->title);
            //falta
            if ($now < $start_time) {
                $view_contest[$i]["wait"] = true;
                $view_contest[$i]["start_time_run"] = $MSG_Start . " " . $row->start_time;
                $view_contest[$i]["start_time"] = $MSG_TotalTime . " " . formatTimeLength($start_time - $now);
            } else {
                // running
                $view_contest[$i]["wait"] = false;
                $view_contest[$i]["start_time_run"] = $MSG_Running . " " . $row->start_time;
                $view_contest[$i]["start_time"] = $MSG_LeftTime . " " . formatTimeLength($left);
            }
            $view_contest[$i]["start_time"] .= "</a>";
        }
        $i++;
    }

    mysql_free_result($result);

    require("template/" . $OJ_TEMPLATE . "/contestsetlist.php");
    ?>

