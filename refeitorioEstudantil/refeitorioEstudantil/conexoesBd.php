<?php
$str = file_get_contents(__DIR__.'/loginBD.json');
$json = json_decode($str, true);

function limpaInputMalicioso($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function voltaMenu(){
  header("location: caixa.php");
}

session_start();

$c = oci_connect($json['usuario'], $json['senha'], "bdengcomp_low");
if (!$c) {
    $m = oci_error();
    trigger_error("Falha ao conectar com o BD". $m["message"], E_USER_ERROR);
}

// as datas tem formato especifico
$s = oci_parse($c, "alter SESSION set NLS_TIMESTAMP_FORMAT = 'HH24/DD/MM/YY'");
if (!$s) {
  $m = oci_error($c);
  trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
}


?>
