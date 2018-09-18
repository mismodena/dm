<?

if( $_REQUEST["draft"] == 1 ){
	$arr_draft = [];
	$data_dealer = "";	

	$_REQUEST["item"] = $_REQUEST["items"];

	$rs_item = order::daftar_item(0);
	if( sqlsrv_num_rows( $rs_item ) > 0 ){
		while( $item = sqlsrv_fetch_array($rs_item) ){
			$arr_draft["item-desc"] = $item["desc"];

			if ($_REQUEST["gudang"]=="GDGPST"){
				$arr_draft["stock_acc"] =(int) $item["qty_accpac_pusat"];
				$arr_draft["stock_commit"] =(int) $item["kuantitas_pusat_terambil"] ;
				$arr_draft["stock_free"] = $arr_draft["stock_acc"] - $arr_draft["stock_commit"] ;
			}else{
				$arr_draft["stock_acc"] = (int)$item["qty_accpac_cabang"];
				$arr_draft["stock_commit"] = (int)$item["kuantitas_cabang_terambil"] ;
				$arr_draft["stock_free"] = $arr_draft["stock_acc"] - $arr_draft["stock_commit"] ;
			}
			
		}
	}

	$rs_draft =  order::draft_stock($_REQUEST["items"], $_REQUEST["gudang"]);
	if( sqlsrv_num_rows( $rs_draft ) > 0 ){
		
		while( $data_area = sqlsrv_fetch_array($rs_draft) ){

			$data_dealer .= "<tr><td>" . $data_area["order_id"] . "</td><td>" . $data_area["user_id"] . "</td><td>" . date_format($data_area['tanggal'], 'd-F-Y' ) . "</td><td>" . $data_area["kuantitas"] . "</td></tr>";

			if($data_area["pengajuan_diskon"]==1){
				$data_dealer .= "<td>Pengajuan</td>";
			}else{
				$data_dealer .= "<td>Drafting</td>";
			}
			
			$data_dealer .= "</tr>";

		}
	}
	$arr_draft["item"] = $data_dealer;

	
	$json = json_encode($arr_draft, JSON_FORCE_OBJECT);
	echo $json;
	exit();

}

SkipCommand:

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
			$arr["#stok_lokal#"] = '<a href="#" onclick=showmodal(\''. $item["itemno"] .'\',\''. $_SESSION['cabang'] .'\',this)>' . $item["qty_lokal"] . '</a>';
			$arr["#stok_pusat#"] = '<a href="#" onclick=showmodal(\''. $item["itemno"] . '\',\'GDGPST\',this)>' .$item["qty_pst"] . '</a>';
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

SkipView:

?>