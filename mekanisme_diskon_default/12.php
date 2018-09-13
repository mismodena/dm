<?

if( $nominal_order["nominal_order"] >= 50000000 )
	$diskon["nilai_diskon"] = 4;
elseif( $nominal_order["nominal_order"] >= 10000000 && $nominal_order["nominal_order"] < 50000000 )
	$diskon["nilai_diskon"] = 3;
 /*
 10 - 50jt : 3%
 >50jt : 4%
 */
 
?>