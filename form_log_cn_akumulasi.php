<?
include "includes/top_blank.php";

// periode campaign
$data_campaign = sqlsrv_fetch_array( sql::execute(" select a.awal, a.akhir, c.periodeid, c.campaignid from periode a, campaign b, paket c where a.periodeid = b.periodeid and b.periodeid = c.periodeid and b.campaignid = c.campaignid and c.paketid = '". main::formatting_query_string( $_REQUEST["paketid"] ) ."'") );	


$sql = "select a1.order_id,convert(date,tanggal) tanggal,a1.user_id,kuantitas,subtotal_order ,a1.INVNUMBER
from ( 
	select SUM(kuantitas) kuantitas, SUM(diskon) diskon, 
			sum(subtotal_order-(subtotal_order*(INVDISCPER/100))) subtotal_order,order_id,user_id,N.INVNUMBER
	from (
	select distinct	e.QTYSHIPPED-ISNULL( g.qtyreturn, 0 ) kuantitas, e.INVDISC diskon, 
			e.EXTINVMISC - e.INVDISC - e.HDRDISC - isnull(g.EXTCRDMISC, 0) - isnull(g.CRDDISC, 0) - isnull(g.HDRDISC, 0) subtotal_order, c.order_id, c.user_id,d.INVNUMBER
	from [order] c 
	inner join order_item a on c.order_id = a.order_id and c.user_id = a.user_id 
	inner join paket_item b on 
	( 
		(a.item_id = b.item and b.mode = 1) or b.item 
		in (	
			select x.item from paket_item x, sub_kategori y 
			where x.paketid = b.paketid and x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 
			and substring( a.item_id, 1, 2 ) 
			in ( 
				select * from dbo.[ufn_split_string](y.kode_prefiks, ',') 
				) 
			) 
	) 
	inner join sgtdat..ICITEM f on a.item_id = f.itemno 
	inner join sgtdat..OEINVH d on c.order_id = d.ordnumber
	inner join sgtdat..OEINVD e on d.invuniq = e.invuniq and f.fmtitemno = e.item 		
	left outer join sgtdat..OECRDD g on g.INVNUMBER = d.INVNUMBER and g.LINENUM = e.LINENUM 
	where c.dealer_id = '" . main::formatting_query_string( $_REQUEST["dealer_id"] ) . "' 
			and CONVERT(DATE,c.tanggal) >= '". $data_campaign["awal"]->format("m/d/Y") ."' 
			and CONVERT(DATE,c.tanggal) <= '". $data_campaign["akhir"]->format("m/d/Y") ."' 
			and b.paketid = '". main::formatting_query_string( $_REQUEST["paketid"] ) ."' 
	)N 
	inner join sgtdat..OEINVH H on N.order_id = H.ORDNUMBER
	group by N.order_id, user_id ,N.INVNUMBER
		
) a1, [order] a2 
left outer join order_akumulasi_log oal on oal.order_id=a2.order_id
where a1.order_id = a2.order_id and a1.user_id = a2.user_id and oal.order_id is null";

$rs_data_invoice = sql::execute( $sql );

// $invoice_diakumulasi = 0;




//if( sqlsrv_num_rows( $rs_data_invoice ) <= 0 ) die("<script>alert('Gagal mendapatkan data Log!');parent.TINY.box.hide();</script>");
?>

<style>
input[type=button]{
	width:57px;
}
</style>
<h3>Log Order</h3>

<div style="margin:7px 0px 7px 0px; font-weight:bold">
	<table border="1" cellpadding="1" cellspacing="1">
		<thead>
			<tr>
				<th>Order ID</th>
				<th>Invoice</th>
				<th>Tanggal</th>
				<th>Qty</th>
				<th>Subtotal</th>
			</tr>
		</thead>
		<tbody>			
				<?php		
					$akumulasi_kuantitas_order_sebulan = $akumulasi_nominal_order = 0;	
					while( $data_invoice = sqlsrv_fetch_array( $rs_data_invoice ) ){ 
						$akumulasi_kuantitas_order_sebulan += $data_invoice[3];
						$akumulasi_nominal_order += $data_invoice[4];
						?>	
						<tr>		
							
							<td><?= $data_invoice["order_id"] ?></td>
							<td><?= $data_invoice["INVNUMBER"] ?></td>					
							<td><?= date_format($data_invoice['tanggal'], 'd-F-Y' ) ?></td>	
							<td align="right"><?= main::number_format_dec($data_invoice[3]) ?></td>	
							<td align="right"><?= main::number_format_dec($data_invoice[4]) ?></td>
						</tr>
					<?php	
					}
				?>	
				<tr>
					<td colspan="3" align="center"><b>Subtotal</b></td>
					<td align="right"><?= main::number_format_dec($akumulasi_kuantitas_order_sebulan) ?></td>
					<td align="right"><?= main::number_format_dec($akumulasi_nominal_order) ?></td>
				</tr>		
		</tbody>
	</table>
</div>
<div>
	<strong>Note:</strong><br />Ini merupakan data order sebelumnya dalam periode campaign yang belum dilakukan claim CN<br />
</div>

<div style="text-align:center; margin:13px 0px 0px 0px">
	<input type="button" name="b_batal" id="b_batal" value="Batal" onclick="parent.TINY.box.hide()" /> 
</div>
