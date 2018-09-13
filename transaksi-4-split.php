<?
$rs_order = order::daftar_order_split( array( "a.order_id" => array("=", "'". @$_REQUEST["order_id"] ."'") ) );

$template = "<div style=\"padding:17px; border-bottom:1px #FFF solid\">
		No. Order : #order_id#<br />
		Kode : #dealer_id#<br />
		Nama Dealer : #namecust#<br />
		Nilai Order (Rp) : #nilai_order#<br />
	</div>";
$dealer_id = "";
	
while( $data_order = sqlsrv_fetch_array( $rs_order ) ){
	$dealer_id = $data_order["dealer_id"];
	$arr["#order_id#"] = $data_order["ordnumber"];
	$arr["#dealer_id#"] = $data_order["dealer_id"];
	$arr["#namecust#"] = $data_order["namecust"];
	$arr["#nilai_order#"] = main::number_format_dec( $data_order["nilai_order"] );
	@$info_order .= str_replace( array_keys($arr), array_values($arr), $template );
}

?>
<h3>Transaksi Berhasil!</h3>
<span class="tanda-seru">!</span>Berikut ringkasan data transaksi Anda:
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:17px">
	<div style="padding:17px">
		<?=$info_order?>
	</div>	
</div>
<input type="button" id="b_lanjut_order" value="- Order Lanjutan -" onclick="javascript:location.href='transaksi-2.php?dealer=<?=$dealer_id?>'" style="width:49%" />
<input type="button" id="b_lanjut" value="- Selesai -" onclick="javascript:location.href='sales.php'" style="width:49%" />