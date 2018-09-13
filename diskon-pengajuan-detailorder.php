<?

$_POST["persetujuan_diskon"] = 1;
$_SERVER['SCRIPT_FILENAME'] = str_replace("diskon-pengajuan-detailorder.php", "histori-detail.php", $_SERVER['SCRIPT_FILENAME']);
include "histori-detail.php";

?>