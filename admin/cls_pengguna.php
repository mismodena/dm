<?

class pengguna extends sql{

	static function browse_pengguna( $arr_parameter = array() ){
		$sql = "select a.*, case a.aktif when 1 then 'Aktif' else 'Tidak Aktif' end status_aktifasi, b.kode_sales kode_sales_bm, b.nama_lengkap nama_lengkap_bm 
				from [user] a left outer join [user] b on a.bm = b.kode_sales";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
	}		

}

?>