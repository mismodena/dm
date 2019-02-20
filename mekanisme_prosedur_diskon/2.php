<?

include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

class mekanisme_prosedur_diskon_2 extends prosedur_khusus_tambahan_diskon{

	private 
		$mekanisme_prosedur_diskon = "",
		$arr_diskon_id_saling_melengkapi = array(2),
		$arr_diskon_id_share_budget = array(2),
		$arr_diskon_id_sebudget = array(2,32),
		$diskon_id = 2,
		$template = "template/diskon-bqtq#readonly#.html",
		$ada_yg_blm_dialokasikan = false,
		$prefiks_identifikasi_bqtq = "tq",
		$sufiks_identifikasi_bqtq = "_bqtq",
		$persentase_budget_bisa_digunakan = 1,
		$saldo_tersedia_awal = 0,
		$saldo_tersedia_akhir = 0
		;
	
	function __construct( $arr_parameter, $readonly = "", $parameter_template = "", $arr_budget_diskon_tersedia_terkait = array() ){
		
		$this->template = file_get_contents( str_replace( "#readonly#", $readonly, $parameter_template != "" ? $parameter_template : $this->template  ) );
		
		$saldo_bqtq = prosedur_khusus_tambahan_diskon::saldo_pusat( $arr_parameter["a3.dealer_id"][1] )[ $this->prefiks_identifikasi_bqtq . "Avail" ];
		$arr_rpl["#saldo_bqtq#"] =  self::number_format_dec( $saldo_bqtq );
		
		$pemakaian_tq_diskon = prosedur_khusus_tambahan_diskon::pemakaian_saldo_pusat( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_diskon" )["pemakaian"];
		$pemakaian_tq_freeitem= prosedur_khusus_tambahan_diskon::pemakaian_saldo_pusat( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_freeitem" )["pemakaian"];
		$budget_terpakai_non_share = $pemakaian_tq_freeitem + $pemakaian_tq_diskon;
		$arr_rpl["#budget_terpakai_non_share#"] =  "<span style=\"border-bottom:3px red none\">" . 
						self::number_format_dec( $pemakaian_tq_diskon ) . "<sup> [1]</sup></span> + ".  
						self::number_format_dec( $pemakaian_tq_freeitem ) . "<sup> [2]</sup>";
		$arr_rpl["#keterangan_bqtq#"] = "<div style=\"font-size:11px; float:left\">
			<sup>[1]</sup>. Pemakaian tambahan diskon potong TQ yang sedang dalam proses pengajuan<br />
			<sup>[2]</sup>. Pemakaian free item TQ yang sedang dalam proses pengajuan<br />
			<sup>[3]</sup>. Akumulasi pemakaian saldo TQ dari order lain yang sedang dalam proses pengajuan (Free item TQ + Tambahan diskon potong TQ)</div>";
		
		$pemakaian_tq_diskon_orderlain = prosedur_khusus_tambahan_diskon::pemakaian_saldo_pusat( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_diskon", false )["pemakaian"];
		$pemakaian_tq_freeitem_orderlain = prosedur_khusus_tambahan_diskon::pemakaian_saldo_pusat( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_freeitem", false )["pemakaian"];;
		$budget_terpakai_orderlain = $pemakaian_tq_diskon_orderlain + $pemakaian_tq_freeitem_orderlain;
		$arr_rpl["#budget_terpakai_order_lain#"] =  self::number_format_dec( $budget_terpakai_orderlain ) . "<sup> [3]</sup>";
		
		if( is_numeric( $saldo_bqtq ) ){
			$this->saldo_tersedia_awal = $saldo_bqtq;
			$budget_tersisa = $this->persentase_budget_bisa_digunakan * ( $saldo_bqtq - $budget_terpakai_non_share - $budget_terpakai_orderlain );
			$this->saldo_tersedia_akhir = $budget_tersisa;
		}else $budget_tersisa = "===";
		$arr_rpl["#budget_tersisa#"] =  self::number_format_dec( $budget_tersisa );
		
		
		$arr_parameter["a.diskon_id"] = array( " in ", "(" . implode(",", $this->arr_diskon_id_share_budget) . ")" );
		$arr_parameter["a0.tambahan_diskon"] = array( " <> ", "a.diskon_bqtq" );
		$rs_daftar_item_bqtq = prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter, "SUM(a.diskon_bqtq) total_nilai_diskon, a.diskon_id, a2.diskon", " a.diskon_id, a2.diskon " );
		if( sqlsrv_num_rows( $rs_daftar_item_bqtq ) <= 0 ) $this->mekanisme_prosedur_diskon();
		
		$proporsi_diskon = $total_nilai_diskon = 0;
		$arr_share_budget = array();
		
		while( $daftar_item_bqtq = sqlsrv_fetch_array( $rs_daftar_item_bqtq ) ){
			if( $daftar_item_bqtq["diskon_id"] == $this->diskon_id )	
				$proporsi_diskon = $daftar_item_bqtq["total_nilai_diskon"];				
			else
				$arr_share_budget[] = array("diskon_id" =>$daftar_item_bqtq["diskon_id"], "diskon" => $daftar_item_bqtq["diskon"], "total_nilai_diskon" => $daftar_item_bqtq["total_nilai_diskon"] );
			
			$total_nilai_diskon += $daftar_item_bqtq["total_nilai_diskon"];
		}			
		
		$saldo_budget = self::saldo_bqtq_pusat( $arr_parameter["a3.dealer_id"][1], $arr_parameter["a.order_id"][1] )[ $this->prefiks_identifikasi_bqtq . "Avail"]  * $this->persentase_budget_bisa_digunakan;
		if( @$arr_budget_diskon_tersedia_terkait[ $this->prefiks_identifikasi_bqtq ] != "" )	$saldo_budget  = $arr_budget_diskon_tersedia_terkait[ $this->prefiks_identifikasi_bqtq ] * $this->persentase_budget_bisa_digunakan;
		$this->saldo_tersedia_awal = $saldo_budget;
		
		//$arr_rpl["#saldo_bqtq#"] = self::number_format_dec( $saldo_budget );
		$arr_rpl["#budget_terpakai_share#"] = self::number_format_dec( round( $proporsi_diskon ) ) .  " (" . self::number_format_dec( $total_nilai_diskon > 0 ? 100 * $proporsi_diskon / $total_nilai_diskon : 0, 2 ) . "%)";		
				
		$arr_parameter__["a.order_id"] = array( "=", $arr_parameter["a.order_id"][1] );
		$arr_parameter__["a.diskon_id"] = array( " in ", "(". $this->diskon_id .")" );
		$arr_parameter__["a0.tambahan_diskon"] = array( " = ", "a.diskon_bqtq" );
		$rs_daftar_pemakaian_bqtq_non_share = sqlsrv_fetch_array( 
				prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter__, "SUM(a.diskon_bqtq) total_nilai_diskon, a.diskon_id", " a.diskon_id " )
			);		
		//$arr_rpl["#budget_terpakai_non_share#"] = self::number_format_dec( $rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"] );		

		unset( $arr_parameter__ );
		$arr_parameter__["a.order_id"] = array( "<>", $arr_parameter["a.order_id"][1] );
		$arr_parameter__["a.diskon_id"] = array( " in ", "(". implode(",", $this->arr_diskon_id_sebudget) .")" );
		$rs_daftar_pemakaian_bqtq_order_lain = sqlsrv_fetch_array( 
				prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( "", $arr_parameter__, "SUM(a.diskon_bqtq) total_nilai_diskon", " " )
			);		
		
		//$arr_rpl["#budget_terpakai_order_lain#"] = self::number_format_dec( $rs_daftar_pemakaian_bqtq_order_lain["total_nilai_diskon"] );
		
		unset( $arr_parameter__ );
		$arr_parameter__["a.diskon_id"] = array( " in ", "(" . implode(",", $this->arr_diskon_id_saling_melengkapi) . ")" );
		$rs_daftar_pemakaian_bqtq = $rs_daftar_item_bqtq = tambahan_diskon::daftar_tambahan_diskon( $arr_parameter__ , str_replace("'", "", $arr_parameter["a.order_id"][1]),true);		
		while( $daftar_pemakaian_bqtq = sqlsrv_fetch_array( $rs_daftar_pemakaian_bqtq ) ){
			if( $daftar_pemakaian_bqtq["diskon_id"] == $this->diskon_id ) $arr_rpl["#diskon-label#"] = $daftar_pemakaian_bqtq["diskon"];
		}
		
		$this->saldo_tersedia_akhir = $saldo_budget - round( $proporsi_diskon ) - $rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"] ;//- $rs_daftar_pemakaian_bqtq_order_lain["total_nilai_diskon"];
		//$arr_rpl["#budget_tersisa#"] = self::number_format_dec( $this->saldo_tersedia_akhir );
		$arr_rpl["#share-budget#"] = "";

		if( count( $arr_share_budget ) > 0 ){
			
			$arr_rpl["#display-share-budget#"] = "block";
			foreach( $arr_share_budget as $share_budget )
				$arr_rpl["#share-budget#"] .=  "<a href=\"#link_". $share_budget["diskon_id"] ."\">" . $share_budget["diskon"] . " (". self::number_format_dec( 100 * $share_budget["total_nilai_diskon"] / $total_nilai_diskon, 2 ) ."%)</a> ";
				
		}else $arr_rpl["#display-share-budget#"] = "none";
			
		// tombol kombinasi / share budget
		foreach( $this->arr_diskon_id_share_budget as $diskon_id ){
			if( $diskon_id != $this->diskon_id ) {
				$display_button_share_budget = true;
				$arr_rpl["#diskonid_awal#"] = $diskon_id;
			}
		}
		$arr_rpl["#display-button-share-budget#"] = @$display_button_share_budget ? "block" : "none";

		$arr_rpl["#disabled-button-share-budget#"] = $readonly != "" ? "disabled" : "";
		$arr_rpl["#diskonid#"] = $this->diskon_id;
		
		$this->template = str_replace( array_keys( $arr_rpl ), array_values( $arr_rpl ), $this->template );
		
		$this->mekanisme_prosedur_diskon = $this->template;
		
		// cek apakah semua free item bq/tq sudah dialokasikan budgetnya ke tabel order_diskon_bqtq
		unset( $arr_parameter__ );
		$arr_parameter__["/*a.diskon_id*/"] = array("", "(a.diskon_id in ( select diskon_id from order_diskon_bqtq where order_id = a.order_id ))");
		$arr_parameter__["a0.kuantitas_diskon_item"] = array("=", "a0.kuantitas");
		$rs = prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter__, " *, case when a1.nilai_diskon <= 100 then 'persen' else 'nominal' end jenis_nilai_diskon ", "", "bukan_bqtq");
		while( $data = sqlsrv_fetch_array($rs) ){			
		
			if( $data["diskon_id"] != $this->diskon_id ) continue;
			
			$pembanding = $data["diskon_total_persen"];
			if( $data["jenis_nilai_diskon"] == "nominal" )
				$pembanding = $data["diskon_total"];
			
			if( $pembanding != $data["nilai_diskon"] )	$this->ada_yg_blm_dialokasikan = true;
			
		}
		
	}	
	
	function mekanisme_prosedur_diskon(){
		return $this->mekanisme_prosedur_diskon;
	}		
	
	function arr_diskon_id_saling_melengkapi(){
		return $this->arr_diskon_id_saling_melengkapi;
	}
	
	function ada_yg_blm_dialokasikan(){
		return $this->ada_yg_blm_dialokasikan;
	}
	
	function arr_diskon_id_share_budget(){
		return $this->arr_diskon_id_share_budget;
	}
	
	function arr_diskon_id_sebudget(){
		return $this->arr_diskon_id_sebudget;
	}
	
	function prefiks_identifikasi_bqtq(){
		return $this->prefiks_identifikasi_bqtq;
	}
	
	function sufiks_identifikasi_bqtq(){
		return $this->sufiks_identifikasi_bqtq;
	}
	
	function persentase_budget_bisa_digunakan(){
		return $this->persentase_budget_bisa_digunakan;
	}
	
	function saldo_tersedia_awal(){
		return $this->saldo_tersedia_awal;
	}

	function saldo_tersedia_akhir(){
		return $this->saldo_tersedia_akhir;
	}

}

?>