<?
include "includes/top.php";
?>
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
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p></p>
  </div>
</div>
<?
include "includes/bottom.php";
?>