<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>PHPExcel Reader Example #01</title>

</head>
<body>

<h1>PHPExcel Reader Example #01</h1>
<h2>Simple File Reader using PHPExcel_IOFactory::load()</h2>
<?php

/** Include path **/
//set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include '../../../Classes/PHPExcel/IOFactory.php';


$inputFileName = 'sampleData/EXAMPLE01.xlsx';
echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


echo '<hr />';

$i = 0;
while ($objPHPExcel->setActiveSheetIndex($i)){
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    echo "<pre>\n";
    print_r($sheetData);
    echo "</pre>\n";
    echo "<hr />\n";
    $i++;
}

?>
<body>
</html>