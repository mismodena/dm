<?

class simulasi_campaign extends order{

	static function item_info( $item ){		
		$sql = self::sql_item_info() . " and b.itemno = '". main::formatting_query_string( $item ) ."' " ;
		return sql::execute( $sql );		
	}
	
	static function sub_kategori_info( $sub_kategori ){
		$sql = "select a.*, b.kategori, case brandid when 1 then 'MODENA' when 2 then 'DOMO' end brand
			from sub_kategori a, kategori b 
			where a.kategoriid = b.kategoriid and a.sub_kategoriid = '". main::formatting_query_string( $sub_kategori ) ."';";
		return sql::execute( $sql );
	}

	static function item_info_dari_subkategori( $sub_kategoriid ){
		$sql = self::sql_item_info() . " and substring(b.itemno, 1, 2) in 
			(select * from dbo.[ufn_split_string]( ( select kode_prefiks from sub_kategori where sub_kategoriid = '". main::formatting_query_string( $sub_kategoriid ) ."') , ','))" ;
		return sql::execute( $sql );
	}	
	
	static function insert_order_simulasi( $arr_col ){
		$sql = "insert into [order] (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute( $sql );
	}
}

?>