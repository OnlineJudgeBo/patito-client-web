<?php
function verificaCaptcha($response,$secret){
 $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response;
 $curl = curl_init();
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 curl_setopt($curl, CURLOPT_TIMEOUT, 15);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
 $curlData = curl_exec($curl);
 curl_close($curl);

 $res = json_decode($curlData, TRUE);
 if($res['success'] == 'true') 
  return true;
else
  return false;
}
?>