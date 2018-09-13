<? 
include "includes/top.php";
set_time_limit(60);

echo $style;
?>
<a href="transaksi.php" style="color:blue">Kembali ke daftar dealer</a><br /><br />
<div style="float:right"><img src="images/item.png" border="none" /></div>
<span class="tanda-seru">!</span>Anda sedang melakukan order pembelian item untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Kode : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		<!--Diskon (%) : <?=$data_dealer["disc"]?><br />--><br />
		<input type="button" id="b_batal" value="Batalkan Order Dealer" onclick="hapus_order('<?=$_SESSION["order_id"]?>')" />
	</div>	
</div>
<div id="overlimit-note" style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px; display:none">
	<div style="padding:17px">
		<div style="font-weight:900"><span class="tanda-seru">!</span>Dealer ini telah melebihi limit kredit (Overlimit)</div>
		<div>Limit kredit dealer : Rp<span id="limit-kredit"></span></div>
		<div>Piutang + Order sekarang : Rp<span id="piutang+order"></span></div>
	</div>	
</div>
<input type="button" name="b_cari" id="b_cari" value="Cari Item" onclick="javascript:location.href='transaksi-2-item.php?po='+ document.getElementById('t_po').value" class="tombol-hijau" />
<div style="float:left;width:100%; ">
	<div style="text-align:center; margin-top:17px; border-top:3px double #CCC; border-bottom:3px double #CCC; background-color:#EEE;"><h3>Ringkasan Order</h3></div>
	<div style="font-weight:bold; line-height:27px;">
		Total Order : Rp<span id="total-order"></span><br />
		Total Diskon Campaign : Rp<span id="total-diskon-campaign"></span><br />
		<span id="kontainer-total-diskon-tambahan" style="display:none">Total Diskon Tambahan : Rp<span id="total-diskon-tambahan"></span><br /></span>
		<span id="label-reward" <?= (@$_SESSION["pilih_cn"]== "" || @$_SESSION["pilih_cn"]== 0) ? 'style="display:none"' : 'style="display:block"' ?>> Total Reward CN : Rp<span id="total-reward-cn"></span><br /></span>
		Total Order Net : Rp<span id="total-order-net"></span><br />

	</div>
</div>
<div style="float:left;width:100%; margin-bottom:17px;">
	<div style="text-align:center; margin-top:17px; border-top:3px double #CCC; border-bottom:3px double #CCC; background-color:#EEE;"><h3>Detail Order</h3></div>
	<?=$data_order?>
</div>
<?= @$item_stok_habis ? "<span class=\"peringatan\">Mohon ubah data kuantitas item dengan unit pembelian melebihi stok tersedia, sebelum melanjutkan ke proses berikutnya!</span>" : "" ?>	
<!--<input type="button" name="b_lanjut" id="b_lanjut" value="Lanjutkan Order ke Proses Berikutnya" onclick="location.href='transaksi-3.php'" class="tombol-hijau" <?= $item_stok_habis ? "disabled" : "" ?>/>	-->

<input type="button" name="b_keterangan_detail" id="b_keterangan_detail" value="Munculkan Keterangan Detail" onclick="munculkan_detail_keterangan()" style="width:100%; background-color:#ffff80; border:solid 1px #808000;" />
<div style="padding: 7px 0px 7px 0px; display:none" id="kontainer_keterangan_detail">	
	<div style="float:left; background-color:#EEE; width:100%; margin-bottom:3px">
		<h3>Alamat Pengiriman</h3>
		<div style="float:left; width:100%; padding-bottom:7px">
		Nama Konsumen
		<input  type="text" name="t_nama_konsumen" value="<?=@$_REQUEST["t_nama_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="60" />
		</div>
		<div style="float:left; width:100%; padding-bottom:7px">
		Alamat
		<textarea  type="text" name="t_alamat_konsumen" style="width:100%; height:57px" onblur="simpan_session(this)" maxlength="240" ><?=@$_REQUEST["t_alamat_konsumen"]?></textarea>
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
		Kota
		<input  type="text" name="t_kota_konsumen" value="<?=@$_REQUEST["t_kota_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="30" />
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
		Propinsi
		<input  type="text" name="t_propinsi_konsumen" value="<?=@$_REQUEST["t_propinsi_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="30" />
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
		Telepon
		<input  type="text" name="t_telepon_konsumen" value="<?=@$_REQUEST["t_telepon_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="30" />
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
		HP
		<input  type="text" name="t_hp_konsumen" value="<?=@$_REQUEST["t_hp_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="30" />
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
		Email
		<input  type="email" name="t_email_konsumen" value="<?=@$_REQUEST["t_email_konsumen"]?>" style="width:100%;" onblur="simpan_session(this)" maxlength="30" />
		</div>
		<div style="float:left; width:100%; padding-bottom:21px;">
			<input type="checkbox" name="cb_alamat_penagihan" id="cb_alamat_penagihan" <?=@$_REQUEST["cb_alamat_penagihan"] != "" ? "checked" : ""?> value="1" /><label for="cb_alamat_penagihan"><strong>Alamat penagihan disesuaikan dengan alamat  pengiriman di atas</strong></label>
		</div>
	</div>	
</div>	
<div style="float:left; background-color:#EEE; width:100%; margin-bottom:3px">
	<a id="jump"></a>
	<h3>Nomor PO</h3>
	<div style="float:left; width:100%; padding-bottom:21px; ">
	<span style="color:red; font-weight:900">Khusus dealer Modern, mohon isikan nomor PO!</span>
	<input  type="text" name="t_po" id="t_po" value="<?=@$_REQUEST["t_po"]!= "" ? $_REQUEST["t_po"] : $_SESSION["t_po"] ?>" style="width:80%;" onblur="simpan_session(this)" maxlength="22" /><input type="button" name="b_cek_po" id="b_cek_po" value="Cek PO" onclick="cek_po()" style="width:20%" />
	</div>
</div>
<div style="float:left; background-color:#EEE; width:100%; margin-bottom:3px">
	<h3>Keterangan Tambahan</h3>
	<div style="float:left; width:100%; padding-bottom:21px; ">
	<textarea name="t_keterangan" style="width:100%; height:57px"><?=@$_REQUEST["t_keterangan"]?></textarea>
	</div>
</div>
<div style="float:left; background-color:#FFF; width:100%; margin-bottom:3px">
	<h3>Pilih Opsi Transaksi!</h3>
	<div style="float:left; width:100%; line-height:21px">
		<input type="hidden" name="order_id" id="order_id" value="<?=$data_dealer["order_id"]?>" />
		<input type="radio" name="r_cek" id="r_cek_1" value="1" <?= @$_REQUEST["r_cek"] == "" || @$_REQUEST["r_cek"] == "1" ? "checked" : "" ?>  /><label for="r_cek_1">Kirimkan Order ke Admin Sales (ACCPAC)</label><br />
		<input type="radio" name="r_cek" id="r_cek_2" value="2" <?= @$_REQUEST["r_cek"] == "2" ? "checked" : "" ?> /><label for="r_cek_2" id="label_r_cek_2">Ajukan Penambahan Diskon (Branch Manager - <?=$data_dealer["nama_lengkap_bm"]?>)</label><br />
		<input type="button" name="b_lanjut" id="b_lanjut" value="Proses" onclick="javascript:<?=$script_eksekusi?>" class="tombol-hijau" <?= @$item_stok_habis || ($subtotal_noncampaign + $subtotal_campaign) <= 0 ? "disabled" : ""?>/>
		<?=@$data_po?>
		<script>
			try{
				document.getElementById('b_lanjutkan_order').disabled = document.getElementById('b_lanjut').disabled				
			}catch(e){}
			<?=$script_tambahan?>
		</script>
	</div>
</div>
<?
include "includes/bottom.php";
?>

<!-- The Modal -->
<input type='hidden' id='flag_terapkan' value='<?=$_SESSION["pilih_cn"]?>' />
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p></p>
  </div>
</div>