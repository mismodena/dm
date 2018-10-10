<?
	//overlimit -> PPOK Auto --
	//e.cabang, e.user_id, e.kode_sales, e.email, e.nama_lengkap, c.order_id, c.keterangan_order
	//$notPPOK = array('A1','A2','A3','A4','A6','A7','V','Y01','Y02','Y11','Y12','Z','Z1');
	$notPPOK = array('A1','A3','A4','A7','V','Y01','Y02','Y11','Y12','Z','Z1');
	$urlp = "Salah";
	if (!in_array(trim($data_dealer["idgrp"]),$notPPOK)){
		if($status_order == 1){
			$jenis = 0;
			$persen = 0;
			if($overlimit["limit_kredit"]>0){
				$persen = ((float)$overlimit["piutang_plus_order_baru"]-(float)$overlimit["limit_kredit"])/(float)$overlimit["limit_kredit"] * (float)100;
			}
			if ($persen >= 200 && $nominal_order["nominal_order_net"] > 150000000) $jenis = 2;
			elseif ($persen >= 100) $jenis = 1;
			
			$nama = str_replace(' ','_',trim($data_dealer["nama_lengkap"]));
			$ket = str_replace(' ','_',trim($data_dealer["keterangan_order"]));
			$email = str_replace(' ','',trim($data_dealer["email"]));
			$emailbm = str_replace(' ','',trim($data_dealer["email_bm"]));
			
			$urlp = "http://indomoportal.modena.co.id:2010/app/lkonline/ppok_exec_auto.asp?cmd=exe&j=".$jenis."&dealer=".$data_dealer["idcust"];
			$urlp .= "&sOrder=".$nominal_order["nominal_order_net"]."&kodeS=".$data_dealer["kode_sales"]."&id=".$data_dealer["order_id"];
			$urlp .= "&nama=".$nama."&ket=".$ket;
			$urlp .= "&email=".$email."&emailbm=".$emailbm;
			$urlp .= "&limit=".$overlimit["limit_kredit"];
			$urlp .= "&persen=".$persen;
			sql::execute( "insert into auto_ppok values ('".$data_dealer["order_id"]."','".$urlp."')" );
			//file_get_contents($urlp);
			//echo $urlp;
			
		}
	}
	
?>