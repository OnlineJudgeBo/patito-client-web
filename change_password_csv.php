<?php
require_once("./include/db_info.inc.php");
require_once("./include/my_func.inc.php");

if (($handle = fopen("obi_2019_lp.csv", "r")) !== FALSE) {
  while (($d = fgetcsv($handle, 100000, ";",'"')) !== FALSE) {
  	$data        = array_map('trim', $d);
if(sizeof($data) < 10) continue;
        $password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
        for($i =0; $i< 29; $i++){
         echo $data[$i].";";
        }
        echo $password."<br>";
        
        $password=pwGen($password);

        $qry = sprintf('UPDATE users SET password = "%s" WHERE vcyt ="%s"',$password,$data[1]);
	echo $qry.";<br>";
  }
  fclose($handle);
}

