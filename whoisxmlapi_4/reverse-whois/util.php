<?php 
function crypt_dn($s){
  
    if(!$s || strlen($s)==0)return $s;
    $res=$s[0];
    $n=strlen($s);
    for($i=1;$i<$n;$i++){
      if($s[$i]!='.')$res.='_';
      else $res.=$s[$i];
    }
    return $res;
}
?>