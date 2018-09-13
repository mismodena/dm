<?

class operator extends sql{

	protected static function cari_index_array( $arr, $item ){
		$arr_return = array();
		foreach( $arr as $index => $arr_data_item  ){
			if( trim(strtoupper($arr_data_item["item"])) == trim(strtoupper($item)) )
				$arr_return[] =  $index;
		}
		return $arr_return;
	}

	/*
	format $rentang_nilai = nilai bawah-nilai atas .. pake operator minus "-"
	ini berlaku untuk operator "between", "in", "not in"
	*/
	
	protected static function operator_between($nilai, $rentang_nilai){
		list($bawah, $atas) = explode("-", $rentang_nilai);		
		if( $nilai >= $bawah && $nilai <= $atas || $nilai >= $atas ) return true;
		return false;
	}
	
	protected static function operator_kurang_dari_sama_dengan($nilai, $rentang_nilai){
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] <= $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
		
		if( $nilai <= $rentang_nilai ) return true;
		return false;
	}
	
	protected static function operator_kurang_dari( $nilai, $rentang_nilai ){
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] < $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
		
		if( $nilai < $rentang_nilai ) return true;
		return false;
	}

	protected static function operator_lebih_dari_sama_dengan($nilai, $rentang_nilai){
		
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] >= $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
			
		if( $nilai >= $rentang_nilai ) return true;
		return false;
	}
	
	protected static function operator_lebih_dari( $nilai, $rentang_nilai ){
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] > $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
		
		if( $nilai > $rentang_nilai ) return true;
		return false;
	}

	protected static function operator_sama_dengan( $nilai, $rentang_nilai ){
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] == $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
		
		if( $nilai == $rentang_nilai ) return true;
		return false;
	}
	
	protected static function operator_tidak_sama_dengan( $nilai, $rentang_nilai ){
		$arr_nilai = explode(";", $nilai);
		$arr_rentang_nilai = explode(";", $rentang_nilai);		
		if( count( $arr_nilai ) > 1  && count( $arr_rentang_nilai ) > 1 && count( $arr_nilai ) == count( $arr_rentang_nilai ) ){
			foreach( $arr_rentang_nilai as $index=>$rentang_nilai_single ){
				if( $arr_nilai[ $index ] != $rentang_nilai_single ) continue;
				else return false;
			}
			return true;
		}
		
		if( $nilai != $rentang_nilai ) return true;
		return false;
	}
	
	protected static function operator_in( $nilai, $rentang_nilai ){
		$arr_rentang_nilai = explode( "-", $rentang_nilai );
		if( in_array( $nilai, $arr_rentang_nilai ) ) return true;
		return false;
	}

	protected static function operator_not_in( $nilai, $rentang_nilai ){
		$arr_rentang_nilai = explode( "-", $rentang_nilai );
		if( !in_array( $nilai, $arr_rentang_nilai ) ) return true;
		return false;
	}

}

?>