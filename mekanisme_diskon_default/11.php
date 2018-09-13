<?
/*
$arr_teritori_diskon_kontainer =array(
103=>array("nama"=>"Palembang", "discount"=>array( 4 , 3 ), "minimal_order"=>200 ),
203=>array("nama"=>"Bandung", "discount"=>array(  1, 1.5 ), "minimal_order"=>200 ),
205=>array("nama"=>"Yogyakarta", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
206=>array("nama"=>"Purwokerto", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
208=>array("nama"=>"Surabaya", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
210=>array("nama"=>"Bali", "discount"=>array( 3 , 1.5 ), "minimal_order"=>200 ),
211=>array("nama"=>"Kediri", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
301=>array("nama"=>"Samarinda", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
306=>array("nama"=>"Samarinda", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
302=>array("nama"=>"Balikpapan", "discount"=>array( 5 , 3 ), "minimal_order"=>200 ),
304=>array("nama"=>"Banjarmasin", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
401=>array("nama"=>"Makassar", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
402=>array("nama"=>"Manado", "discount"=>array( 6 , 3 ), "minimal_order"=>200 ),

216=>array("nama"=>"JAKARTA", "discount"=>array( 1 , 1.5 ), "minimal_order"=>200 ),
);*/
// perubahan per 2018
$arr_teritori_diskon_kontainer =array(
103=>array("nama"=>"Palembang", "discount"=>array( 4 , 3 ), "minimal_order"=>200 ),
203=>array("nama"=>"Bandung", "discount"=>array(  1, 1.5 ), "minimal_order"=>200 ),
205=>array("nama"=>"Yogyakarta", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
206=>array("nama"=>"Purwokerto", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
208=>array("nama"=>"Surabaya", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
210=>array("nama"=>"Bali", "discount"=>array( 3 , 1.5 ), "minimal_order"=>200 ),
211=>array("nama"=>"Kediri", "discount"=>array( 2.5 , 1.5 ), "minimal_order"=>200 ),
301=>array("nama"=>"Samarinda", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
306=>array("nama"=>"Samarinda", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
303=>array("nama"=>"Samarinda", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
302=>array("nama"=>"Balikpapan", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
304=>array("nama"=>"Banjarmasin", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
401=>array("nama"=>"Makassar", "discount"=>array( 5 , 3 ), "minimal_order"=>300 ),
402=>array("nama"=>"Manado", "discount"=>array( 6 , 3 ), "minimal_order"=>200 ),
403=>array("nama"=>"PAPUA", "discount"=>array( 12 , 3 ), "minimal_order"=>200 ),
305=>array("nama"=>"Pontianak", "discount"=>array( 5 , 3 ), "minimal_order"=>200 ),

216=>array("nama"=>"JAKARTA", "discount"=>array( 1 , 1.5 ), "minimal_order"=>200 ),
);
 
 
 // cari kode teritori dealer
 $sql = "select c.* from [order] a inner join  ". $GLOBALS["database_accpac"] ."..ARCUS b on A.dealer_id = b.IDCUST 
			left outer join  ". $GLOBALS["database_accpac"] ."..MIS_TERRITORY c on b.CODETERR = c.territory 
			where a.order_id = '". main::formatting_query_string( $nominal_order["order_id"] ) ."'";
 $rs_teritori_dealer = sql::execute( $sql );
 $teritori_dealer = sqlsrv_fetch_array( $rs_teritori_dealer );
 
 $level1 = $level2 = 0;
 
 if( sqlsrv_num_rows( $rs_teritori_dealer ) > 0 && @$teritori_dealer["territory"] != "" && array_key_exists( $teritori_dealer["territory"], $arr_teritori_diskon_kontainer ) ){

	if( $nominal_order["nominal_order"] >= ( $arr_teritori_diskon_kontainer [ $teritori_dealer["territory"] ]["minimal_order"] * 1000000 ) ){ // konversi minimal order ke satuan juta rupiah
	
		//$level1 = $nominal_order_setelah_diskon * $arr_teritori_diskon_kontainer[ $teritori_dealer["territory"] ]["discount"][0] / 100 ;
		//$level2 = ($nominal_order_setelah_diskon - $level1) * $arr_teritori_diskon_kontainer[ $teritori_dealer["territory"] ]["discount"][1] / 100;
		$level1 = $nominal_order["nominal_order_net_exc_discfaktur"] * $arr_teritori_diskon_kontainer[ $teritori_dealer["territory"] ]["discount"][0] / 100 ; 
		$level2 = ($nominal_order["nominal_order_net_exc_discfaktur"] - $level1) * $arr_teritori_diskon_kontainer[ $teritori_dealer["territory"] ]["discount"][1] / 100;
	}
	
 } 
 
 $diskon["nilai_diskon"] = $level1 + $level2;
 if( $diskon["nilai_diskon"] <= 0 ) $diskon["nilai_diskon"] = -1;

?>