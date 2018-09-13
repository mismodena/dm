<?

if( @$_REQUEST["item"] != "" ){
	$rs_item = order::daftar_item( 0 );
	if( sqlsrv_num_rows( $rs_item ) > 0 ){
		$item_template = file_get_contents("template/item-stok.html");
		$counter = 1;
		while( $item = sqlsrv_fetch_array($rs_item) ){
			
			$arr["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
			$arr["#item#"] = $item["itemno"];
			$arr["#harga#"] = main::number_format_dec($item["unitprice"]);
			$arr["#itemdesc#"] = $item["desc"];
			$arr["#stok_lokal#"] = $item["qty_lokal"];
			$arr["#stok_pusat#"] = $item["qty_pst"];
			$display_kedatangan = "none" ;
			if( $item["estimasi_kedatangan"] != "" ) {
				$display_kedatangan = "block" ;
				list( $tanggal, $bulan, $tahun ) = explode("/", $item["estimasi_kedatangan"]);
				$item["estimasi_kedatangan"] = $tanggal . " " . $arr_month[ (int) $bulan ] . " " . $tahun;
			}
			$arr["#display_kedatangan#"] = $display_kedatangan;
			
			$arr["#estimasi#"] = $item["estimasi_kedatangan"] . " - ".  $item["estimasi_kuantitas_kedatangan"] ."  Unit";

			@$data_item .= str_replace( array_keys( $arr ), array_values( $arr ), $item_template );
			$counter++;
		}
	}else $data_item = "Tidak ada item produk yang ditemukan!";
}

?>