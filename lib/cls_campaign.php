<?

class campaign extends sql{
	
	static function daftar_campaign( $arr_parameter = array() ){
		$sql = "select * from campaign ";
		
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by urutan_campaign, campaign " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function daftar_paket( $arr_parameter = array() ){
		$sql = "select * from paket ";
		
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by paketid " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
}

?>