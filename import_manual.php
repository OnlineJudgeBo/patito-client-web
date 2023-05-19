<?php
require_once("./include/db_info.inc.php");
require_once("./include/my_func.inc.php");

if (($handle = fopen("obi_2019.csv", "r")) !== FALSE) {
  while (($d = fgetcsv($handle, 100000, ";",'"')) !== FALSE) {
  	$data        = array_map('trim', $d);
    $qry = sprintf('INSERT INTO 
  users(user_id, email, ip, accesstime, reg_time, password,nick,school,lastname,pais_id,obi,institucion_id,departament,vcyt,district,level,rude,ci)
  VALUES("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s");',
  $data[0],$data[1],$data[2],$data[3],$data[4],pwGen($data[5]),$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],
  $data[12],$data[13],$data[14],$data[15],$data[0],$data[5]);
  echo $qry."<br>";
  }
  fclose($handle);
}

