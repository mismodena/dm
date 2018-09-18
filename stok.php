<?
include "includes/top.php";
?>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<div>
	<div style="float:left; display:block; width:100%; border:none">
	Berikut ini adalah data persediaan produk di lokasi cabang dan pusat. Silahkan isikan nama item produk: <br /><br />
	<input type="text" name="item" id="item" value="<?=@$_REQUEST["item"]?>" style="width:107px" /><input type="button" name="b_cari" id="b_cari" value="Cari" onclick="cari_item()" /><br /><br />
	<span class="tanda-seru">!</span><strong>Note</strong> : harga yang ditampilkan adalah price list.	
	</div>
	<div style="padding-top:17px; float:left; width:100%; border:none">
		<?=@$data_item?>
	</div>
</div> 

<div id="modal-stok" class="modal">
  	<div class="modal-content" style="width: 60%;">
	    <div class="modal-header" style="background-color: gainsboro;padding: 20px;">
	      <span class="close">&times;</span>
	      <h2>Data Draft Stok By <span id="gudangid"></span></h2>
	    </div>
	    <div class="modal-body">
	    	<div>
	    		<h3>Accpac Stock: <span id="s_acc"></span></h3>
	    		<h3>Commited Stock: <span id="s_commit"></span></h3>
	    		<h3>Free Stock: <span id="s_free"></span></h3>
	    	</div>
	    	<hr>
	      	<table id="myTable" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Order ID</th>
	                <th>User ID</th>
	                <th>Tanggal</th>
	               	<th>Kirim</th>
	               	<th>Pengajuan Disc</th>
	               	<th>Qty</th>
	            </tr>
	        </thead>
	        <tbody>

	        </tbody>
	        
	    </table>
	    </div>
    
  	</div>
</div>
<?
include "includes/bottom.php";
?>