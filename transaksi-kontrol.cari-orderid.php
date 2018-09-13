<div>
Masukkan nomor order yang akan dicek :
<input type="text" name="order_id" id="order_id" style="width:100%" value="" />
<input type="button" name="b_cari" id="b_cari" style="width:100%" value="Cek Nomor Order" onclick="if(document.getElementById('order_id').value != '' )location.href='transaksi-kontrol.php?order_id='+ document.getElementById('order_id').value" />
</div>