<?

include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

class mekanisme_prosedur_diskon_1 extends prosedur_khusus_tambahan_diskon{
	
	private 
		$mekanisme_prosedur_diskon = "",
		$arr_diskon_id_saling_melengkapi = array(1),
		$arr_diskon_id_share_budget = array(1),
		$arr_diskon_id_sebudget = array(1,13),
		$diskon_id = 1,
		$template = "template/diskon-bqtq#readonly#.html",
		$ada_yg_blm_dialokasikan = false,
		$prefiks_identifikasi_bqtq = "bq",
		$sufiks_identifikasi_bqtq = "_bqtq",
		$persentase_budget_bisa_digunakan = 0.7,
		$saldo_tersedia_awal = 0,
		$saldo_tersedia_akhir = 0
		;
	
	function __construct( $arr_parameter, $readonly = "", $parameter_template = "", $arr_budget_diskon_tersedia_terkait = array() ){
		
		$this->template = file_get_contents( str_replace( "#readonly#", $readonly, $parameter_template != "" ? $parameter_template : $this->template  ) );
		
		$saldo_bqtq = prosedur_khusus_tambahan_diskon::saldo( $arr_parameter["a3.dealer_id"][1] )[ $this->prefiks_identifikasi_bqtq . "Avail" ];		
		$arr_rpl["#saldo_bqtq#"] =  self::number_format_dec( $saldo_bqtq );						
		
		$pemakaian_bq_diskon = prosedur_khusus_tambahan_diskon::pemakaian_saldo( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_diskon" )["pemakaian"];
		$pemakaian_bq_freeitem= prosedur_khusus_tambahan_diskon::pemakaian_saldo( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_freeitem" )["pemakaian"];
		$budget_terpakai_non_share = $pemakaian_bq_diskon + $pemakaian_bq_freeitem;
		$arr_rpl["#budget_terpakai_non_share#"] =  "<span style=\"border-bottom:3px red none\">" . 
						self::number_format_dec( $pemakaian_bq_diskon ) . "<sup> [1]</sup></span> + ".  
						self::number_format_dec( $pemakaian_bq_freeitem ) . "<sup> [2]</sup>";						
		$arr_rpl["#keterangan_bqtq#"] = "<div style=\"font-size:11px; float:left; \">
			<sup>[1]</sup>. Pemakaian tambahan diskon potong BQ yang sedang dalam proses pengajuan<br />
			<sup>[2]</sup>. Pemakaian free item BQ yang sedang dalam proses pengajuan<br />
			<sup>[3]</sup>. Akumulasi pemakaian saldo BQ dari order lain yang sedang dalam proses pengajuan (Free item BQ + Tambahan diskon potong BQ)<br />
			<sup>[4]</sup>. Pemakaian tambahan diskon potong BQ adalah maksimal " . ($this->persentase_budget_bisa_digunakan * 100) . "% dari saldo tersedia</div>";
		
		$pemakaian_bq_diskon_orderlain = prosedur_khusus_tambahan_diskon::pemakaian_saldo( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_diskon", false )["pemakaian"];
		$pemakaian_bq_freeitem_orderlain = prosedur_khusus_tambahan_diskon::pemakaian_saldo( $arr_parameter["a.order_id"][1], $this->prefiks_identifikasi_bqtq . "_freeitem", false )["pemakaian"];;
		$budget_terpakai_orderlain = $pemakaian_bq_diskon_orderlain + $pemakaian_bq_freeitem_orderlain;
		$arr_rpl["#budget_terpakai_order_lain#"] =  self::number_format_dec( $budget_terpakai_orderlain ) . "<sup> [3]</sup>";
		
		if( is_numeric( $saldo_bqtq ) ){
			$this->saldo_tersedia_awal = $saldo_bqtq - $pemakaian_bq_freeitem - $budget_terpakai_orderlain;		
			$budget_tersisa = $this->persentase_budget_bisa_digunakan * ( $saldo_bqtq - $budget_terpakai_non_share - $budget_terpakai_orderlain );		
			$this->saldo_tersedia_akhir = $budget_tersisa;
		}else $budget_tersisa = "===";
		$arr_rpl["#budget_tersisa#"] =  self::number_format_dec( $budget_tersisa ). "<sup> [4]</sup>";
		
		
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
		
		// cek diskon free item bq ada di order ini?
		$free_item_bq_ada = false;
		unset($arr_parameter__);
		$arr_parameter__["b.order_id"] = array( "=", $arr_parameter["a.order_id"][1] );
		$arr_parameter__["b.diskon_id"] = array( " in ", "(". implode(",", $this->arr_diskon_id_sebudget ) .")" );
		$rs_cek_free_item_bq = self::daftar_tambahan_diskon($arr_parameter__, $arr_parameter["a.order_id"][1]);
		if( sqlsrv_num_rows( $rs_cek_free_item_bq ) == count( $this->arr_diskon_id_sebudget ) )	$free_item_bq_ada = true;
		
		// cek nilai pemakaian free item tq di order ini?
		unset($arr_parameter__);		
		$parameter = "	( a.diskon_id <> ". $this->diskon_id ." and  a.diskon_id in (". implode(",", $this->arr_diskon_id_sebudget) .") and a.order_id = ". $arr_parameter["a.order_id"][1] ." ) ";
		$arr_parameter__["/*a.diskon_id*/"] = array( "" , $parameter);
		$rs_daftar_pemakaian_bqt_freeitem = sqlsrv_fetch_array( 
				prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter__, "SUM(a0.tambahan_diskon) total_nilai_diskon, a.diskon_id", " a.diskon_id " )
			);		
		
		$saldo_budget = self::saldo_bqtq( $arr_parameter["a3.dealer_id"][1], /*$arr_parameter["a.order_id"][1]*/"", $this->persentase_budget_bisa_digunakan )[ $this->prefiks_identifikasi_bqtq . "Avail"] ;
		$this->saldo_tersedia_awal = $saldo_budget;
		
		$arr_rpl["#budget_terpakai_share#"] = self::number_format_dec( round( $proporsi_diskon ) ) .  " (" . self::number_format_dec( $total_nilai_diskon > 0 ? 100 * $proporsi_diskon / $total_nilai_diskon : 0, 2 ) . "%)";		
		
		unset($arr_parameter__);
		$arr_parameter__["a.order_id"] = array( "=", $arr_parameter["a.order_id"][1] );
		$arr_parameter__["a.diskon_id"] = array( " in ", "(". $this->diskon_id .")" );
		$arr_parameter__["a0.kuantitas"] = array( " = ", "a.kuantitas_bqtq" );
		$rs_daftar_pemakaian_bqtq_non_share = sqlsrv_fetch_array( 
				prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter__, "SUM(a0.tambahan_diskon) total_nilai_diskon, a.diskon_id", " a.diskon_id " )
			);		
			
		$rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"] = 	$rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"] / $this->persentase_budget_bisa_digunakan; 
		
		//$arr_rpl["#budget_terpakai_non_share#"] = self::number_format_dec( $rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"] );		
		
		$daftar_pemakaian_bqtq_non_share = $rs_daftar_pemakaian_bqtq_non_share["total_nilai_diskon"];
		$daftar_pemakaian_bq_order_lain = 0;
		if( !$free_item_bq_ada ){			
			foreach($this->arr_diskon_id_sebudget as $diskon_id){
				if( $diskon_id != $this->diskon_id ) continue;
				$parameter = "	( a.diskon_id in (". $diskon_id .") and a.order_id <> ". $arr_parameter["a.order_id"][1] ." ) ";
				unset($arr_parameter__);
				$arr_parameter__["/*a.diskon_id*/"] = array( "" , $parameter);
				$rs_daftar_pemakaian_bqtq_order_lain = sqlsrv_fetch_array( 
						prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( "", $arr_parameter__, "SUM(a.diskon_bqtq) total_nilai_diskon", "  " )
					);		
				$daftar_pemakaian_bq_order_lain += $rs_daftar_pemakaian_bqtq_order_lain["total_nilai_diskon"] / ( $diskon_id == 1 ? $this->persentase_budget_bisa_digunakan : 1 );
			}
			$saldo_bqtq = ( ( $saldo_budget + $daftar_pemakaian_bq_order_lain) * $this->persentase_budget_bisa_digunakan ) + $daftar_pemakaian_bqtq_non_share;
		}else
			$saldo_bqtq = ( ( $saldo_budget + $daftar_pemakaian_bqtq_non_share + $daftar_pemakaian_bq_order_lain) * $this->persentase_budget_bisa_digunakan ) ;	
		
		//$arr_rpl["#saldo_bqtq#"] = self::number_format_dec( $saldo_bqtq );
		//$this->saldo_tersedia_akhir = $saldo_bqtq - $daftar_pemakaian_bqtq_non_share - $daftar_pemakaian_bq_order_lain;
		
		//$arr_rpl["#budget_terpakai_order_lain#"] = self::number_format_dec( $daftar_pemakaian_bq_order_lain );//$rs_daftar_pemakaian_bqtq_order_lain["total_nilai_diskon"] );
		
		unset( $arr_parameter__ );
		$arr_parameter__["a.diskon_id"] = array( " in ", "(" . implode(",", $this->arr_diskon_id_saling_melengkapi) . ")" );
		$rs_daftar_pemakaian_bqtq = $rs_daftar_item_bqtq = tambahan_diskon::daftar_tambahan_diskon( $arr_parameter__ , str_replace("'", "", $arr_parameter["a.order_id"][1]),true);		
		while( $daftar_pemakaian_bqtq = sqlsrv_fetch_array( $rs_daftar_pemakaian_bqtq ) ){
			if( $daftar_pemakaian_bqtq["diskon_id"] == $this->diskon_id ) $arr_rpl["#diskon-label#"] = $daftar_pemakaian_bqtq["diskon"];
		}
		
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

		$arr_rpl["#disabled-button-share-budget#"] = $readonly !== "" ? "disabled" : "";
		$arr_rpl["#diskonid#"] = $this->diskon_id;
		
		$this->template = str_replace( array_keys( $arr_rpl ), array_values( $arr_rpl ), $this->template );
		
		$this->mekanisme_prosedur_diskon = $this->template;
		
		// cek apakah semua free item bq/tq sudah dialokasikan budgetnya ke tabel order_diskon_bqtq --> khusus untuk free item saja
		goto Skip_Cek_Alokasi;
		unset( $arr_parameter__ );
		$arr_parameter__["/*a.diskon_id*/"] = array("", "(a.diskon_id in ( select diskon_id from order_diskon_bqtq where order_id = a.order_id ))");		
		$arr_parameter__["a0.kuantitas_diskon_item"] = array("=", "a0.kuantitas");
		$rs = prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( $arr_parameter["a.order_id"][1], $arr_parameter__, " *, case when a1.nilai_diskon <= 100 then 'persen' else 'nominal' end jenis_nilai_diskon ", "", "bukan_bqtq");
		while( $data = sqlsrv_fetch_array($rs) ){			
		
			$pembanding = $data["diskon_total_persen"];
			if( $data["jenis_nilai_diskon"] == "nominal" )
				$pembanding = $data["diskon_total"];
			
			if( $pembanding != $data["nilai_diskon"] )	$this->ada_yg_blm_dialokasikan = true;
			
		}
		Skip_Cek_Alokasi:
		
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
		//return $this->saldo_tersedia_akhir;
		return $this->saldo_tersedia_awal;
	}

	function saldo_tersedia_akhir(){
		return $this->saldo_tersedia_akhir;
	}
	
}

?>