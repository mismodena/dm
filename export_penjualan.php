<?php 

set_time_limit (300);
ini_set('memory_limit', '-1');

include_once 'library/Classes/PHPExcel.php';
include_once 'lib/var.php';

$tanggal_pertama = @$_REQUEST["m"] != "" ? @$_REQUEST["m"] : "";
$tanggal_lanjut = @$_REQUEST["a"] != "" ? @$_REQUEST["a"] : "";

// DATE FORMAT : 04/30/2018

function set_titik($param){
	$paramA = (string) $param;
	$explode_ = explode(".", $paramA);
	$jumlah = strlen($explode_[0]);
	$result = "";
	while(true){
		if($jumlah > 3){
			$jumlah = $jumlah - 3;
			$result = "," . substr($explode_[0], $jumlah, 3) . $result;
		} else {
			$result = substr($explode_[0], 0, $jumlah) . $result;
			break;
		}
	}
	return $result . (isset($explode_[1]) && $explode_[1] != "" ? "." . $explode_[1] : "");
}

$query_header = "

	select 
		a.*, 
		'['+ltrim(rtrim(textsnam))+'] '+ b.namecust namecust, 
		isnull(e.invnet, c.invnet) nilai_order, 
		dbo.sambung_order_id(d.order_id, d.order_id_split, '-') order_id_split, 
		case isnull(d.gudang, '') when '' then a.gudang else d.gudang end gudang 
	from [order] a 
		inner join sgtdat..arcus b on 
			a.dealer_id = b.idcust
		left outer join order_split d on 
			a.order_id = d.order_id 
		left outer join sgtdat..oeordh c on 
			a.order_id = c.ordnumber 
		left outer join sgtdat..oeordh e on 
			dbo.sambung_order_id(d.order_id, d.order_id_split, '-') = e.ordnumber 	
	where 
		(a.kirim = '1' or a.pengajuan_diskon = '1') and 
		convert(date, convert(varchar, a.tanggal, 101) )between convert(date, '".$tanggal_pertama."' ) and convert(date, '".$tanggal_lanjut."' )
	order by [user_id] asc , a.tanggal desc

";

$months = array(
	"01" => "Januari",
	"02" => "Februari",
	"03" => "Maret",
	"04" => "April",
	"05" => "Mei",
	"06" => "Juni",
	"07" => "Juli",
	"08" => "Agustus",
	"09" => "September",
	"10" => "Oktober",
	"11" => "November",
	"12" => "Desember"

);

$query = sqlsrv_query( $conn, $query_header);

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Order ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'User Sales');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal Transaksi');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Customer');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Item Name');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Harga Asli');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Paket Id');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Nama Campaign');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Diskon Total');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Diskon Total Persen');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Harga Setelah Diskon');

$address = 2;

while($hasil = sqlsrv_fetch_array($query)){
	$order_id = $hasil['order_id'];
	$user_id = $hasil['user_id'];
	$tanggal = $hasil['tanggal']->format('Y-m-d H:i:s');
	$namecust = $hasil['namecust'];
	$query_detail = sqlsrv_query( $conn, "select * from dbo.ufn_daftar_order_item('".$order_id."')");
	
	while($hasil_detail = sqlsrv_fetch_array($query_detail)){
		
		$tanggal_explode = explode(" ", $tanggal);
		$tanggal_0 = $tanggal_explode[0];
		$tanggal_0_explode = explode("-", $tanggal_0);
		$tanggal_format = $tanggal_0_explode[2] . " " . $months[$tanggal_0_explode[1]] . " " . $tanggal_0_explode[0];
		
		$nama_campaign = "";
		if($hasil_detail['paketid'] != ""){
			$query_campaign_id = sqlsrv_query( $conn, "select campaignid from paket where paketid = '".$hasil_detail['paketid']."'");
			$hasil_campaign_id = sqlsrv_fetch_array($query_campaign_id);
			
			$query_campaign_nama = sqlsrv_query( $conn, "select campaign from campaign where campaignid = '".$hasil_campaign_id['campaignid']."'");
			$hasil_campaign_nama = sqlsrv_fetch_array($query_campaign_nama);
			$nama_campaign = $hasil_campaign_nama['campaign'];
			
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $address, $order_id);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $address, $user_id);
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $address, $tanggal_format);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $address, $namecust);
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $address, $hasil_detail['nama_item']);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $address, $hasil_detail['harga']);
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $address, ($hasil_detail['paketid'] == "" ? "-" : $hasil_detail['paketid']));
		$objPHPExcel->getActiveSheet()->setCellValue('H' . $address, ($nama_campaign == "" ? "-" : $nama_campaign));
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $address, $hasil_detail['diskon_total']);
		$objPHPExcel->getActiveSheet()->setCellValue('J' . $address, $hasil_detail['diskon_total_persen']);
		$objPHPExcel->getActiveSheet()->setCellValue('K' . $address, $hasil_detail['sub_total']);
		$address++;
		
	}
	
}

$objPHPExcel->getActiveSheet()->setTitle('Hasil Penjualan.');
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

	$objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

	$sheet = $objPHPExcel->getActiveSheet();
	$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(true);
	
	foreach ($cellIterator as $cell) {
		$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="PENJUALANDM.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

ob_end_clean();
$objWriter->save('php://output');