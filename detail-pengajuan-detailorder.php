<?

$_POST["pengajuan_diskon"] = 1;
$_SERVER['SCRIPT_FILENAME'] = str_replace("detail-pengajuan-detailorder.php", "histori-detail.php", $_SERVER['SCRIPT_FILENAME']);
include "histori-detail.php";

?>
<script>
function set_block(tag, kelas){
	var sp = document.getElementsByTagName( tag );
	for(var x = 0; x < sp.length; x++){
		if( sp[x].className == kelas )
			sp[x].style.display = 'inline'
	}
}
document.body.onload=function(){
	set_block('span', 'div-stok');
	set_block('sup', 'peringatan')
}
</script>