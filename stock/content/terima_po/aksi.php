<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";
	include "../../../konfig/fungsi_log_activity.php";
	include "../../../konfig/fungsi_angka.php";
	include "../../../konfig/fungsi_tanggal.php";
    include "../../../konfig/base_url.php";
	include "../../../konfig/fungsi_form_modal.php";
    include "../../../konfig/fungsi_generate_js.php";
	include "../../../konfig/fungsi_thumb.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
	$act=$_GET['act'];
    
	$module = "Penerimaan Material";

	$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

	else if($act=='tambah_form'){
		include "tambah_form.php";
	}

	else if($act=='input'){
		mysqli_begin_transaction($conn);
		try {
			$tanggal_awal = "$thn_sekarang-01-01";
			$tanggal_akhir = "$thn_sekarang-12:31";

			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM po_terima WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

			$urutan = $a['urutan']+1;
			$urutan_nomor= sprintf("%05s",$urutan);

			$number = "IN-WDB-$urutan_nomor-$thn".$bulan;

			$sql="INSERT INTO po_terima (nomor, po_id, remark, status_id, urutan, created_at, updated_at, tanggal) VALUES ('$number', '$_POST[po_id]', '$_POST[remark]', '200', '$urutan_nomor', '$waktu_sekarang', '$waktu_sekarang', '$_POST[tanggal]')";
			mysqli_query($conn, $sql);

			$id = mysqli_insert_id($conn);

			foreach($_POST['po_detail_id'] AS $po_detail_id){
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT master_material_id, jumlah_konversi, jumlah, jumlah_diterima  AS jumlah_diterima_sebelumnya, master_kondisi_id FROM po_detail WHERE id='$po_detail_id' AND deleted_at IS NULL"));

				$jumlah_satuan_besar = $_POST["jumlah_$po_detail_id"];
				$gudang_id = $_POST["gudang_$po_detail_id"];

				$sql="INSERT INTO po_terima_detail (po_detail_id, jumlah_diterima, master_gudang_id, po_terima_id, created_at, updated_at) VALUES ('$po_detail_id', '$jumlah_satuan_besar', '$gudang_id', '$id', '$waktu_sekarang', '$waktu_sekarang')";
				mysqli_query($conn, $sql);

				$po_terima_detail_id = mysqli_insert_id($conn);

			}

			mysqli_query($conn,"INSERT INTO po_terima_log (po_terima_id, status_id, created_at, pegawai_id, remark) VALUES ('$id', '200', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			print_r($e);
		}
		header("location: terimapo");
	}

	else if($act=='sn_input'){

		mysqli_begin_transaction($conn);
		try {
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.po_id, b.harga, b.master_material_id, b.master_kategori_material_id,  b.master_kondisi_id, b.jumlah_konversi, c.nama AS nama_gudang, d.nama AS nama_satuan_besar, e.nama AS nama_satuan_kecil, f.merk_type, g.nomor AS nomor_po_terima
			FROM po_terima_detail a
			LEFT JOIN po_detail b ON a.po_detail_id=b.id AND b.deleted_at IS NULL
			LEFT JOIN master_material f ON b.master_material_id=f.id AND f.deleted_at IS NULL
			LEFT JOIN master_gudang c ON a.master_gudang_id=c.id AND c.deleted_at IS NULL
			LEFT JOIN master_satuan d ON b.master_satuan_besar_id=d.id AND d.deleted_at IS NULL
			LEFT JOIN master_satuan e ON b.master_satuan_kecil_id=e.id AND e.deleted_at IS NULL
			LEFT JOIN po_terima g ON a.po_terima_id=g.id AND g.deleted_at IS NULL
			WHERE a.deleted_at IS NULL AND a.id='$_POST[po_terima_detail_id]'"));

			$jumlah_item = $d['jumlah_diterima']*$d['jumlah_konversi'];
			$harga = $d['harga']/$jumlah_item;
			
			$sql="INSERT INTO po_terima_sn (po_terima_detail_id, serial_number, created_at, material_sn_id, status, harga, material_sn_status_id, master_klasifikasi_material_id) VALUES ('$d[id]', '$_POST[serial_number]', '$waktu_sekarang', '0', '1', '$harga', '500', '$_POST[master_klasifikasi_material_id]')";

			//CEK APAKAH SERIAL NUMBER SDH ADA ATAU BELUM DI MATERIAL SN
			if($_POST['serial_number']=='0'){
				$a=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM po_terima_sn WHERE po_terima_detail_id='$d[id]' AND status='1'"));
				for($i=$a['tot'];$i<$jumlah_item;$i++){
					mysqli_query($conn, $sql);
				}
			}
			else{
				$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM po_terima_sn WHERE serial_number='$_POST[serial_number]' AND status IN (1,2)"));
				$cek2 = mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM material_sn WHERE serial_number='$_POST[serial_number]'"));
				if(isset($cek['id'])=='' AND  isset($cek2['id'])==''){
					//MASUKKAN DATA SERIAL NUMBER KE DALAM TERIMA PO DETAIL SN
					
					mysqli_query($conn, $sql);
				}

				else{
					?>
					<script type="text/javascript">
						alert("Serial Number tidak dapat diinput. Hal ini bisa disebabkan oleh beberapa kemungkinan berikut:\n1. Serial Number sudah diinput sebelumnya.\n2. Serial Number sudah berada dalam gudang.\n3. Serial Number sudah pernah digunakan.\n4. Serial Number sudah diinput oleh cabang lainnya.\n\nMohon periksa kembali Serial Number yang Anda masukkan dengan teliti. Terima kasih atas perhatian Anda.");
						window.history.back();
					</script>
					<?php
				}
			}
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			print_r($e);
		}
		?>
			<script type="text/javascript">
				//window.history.back();
				window.location.href="terimapo-sn-<?php echo $_POST['po_terima_detail_id'];?>";
			</script>
		<?php
	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM po_terima_sn WHERE id='$_GET[id]'"));

		mysqli_query($conn,"DELETE FROM po_terima_sn WHERE id='$_GET[id]'");

		header("location: terimapo-sn-$d[po_terima_detail_id]");
	}


	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_begin_transaction($conn);
		try {
			mysqli_query($conn,"INSERT INTO po_terima_log (po_terima_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[po_terima_id]', '215', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE po_terima SET status_id='215' WHERE id='$_POST[po_terima_id]'");
			
			mysqli_query($conn,"UPDATE po_terima_sn a INNER JOIN po_terima_detail b ON a.po_terima_detail_id = b.id SET a.status='3' WHERE b.po_terima_id='$_POST[po_terima_id]'");

			mysqli_query($conn,"UPDATE po_terima_detail SET deleted_at='$waktu_sekarang' WHERE po_terima_id='$_POST[po_terima_id]'");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
		}
		header("location: terimapo-view-$_POST[po_terima_id]");
	}

	else if($act=='next'){
		include "next.php";
	}

	else if($act=='next_action'){
		mysqli_begin_transaction($conn);
		try {
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT nomor FROM po_terima WHERE id='$_POST[po_terima_id]' AND deleted_at IS NULL"));
			$number = $d['nomor'];

			$vdir_upload = "../../../files/po/";

			$acak			 = rand(1111,9999);
			$lokasi_file     = $_FILES['dokumen']['tmp_name'];
			$tipe_file       = $_FILES['dokumen']['type'];
			$nama_file       = $_FILES['dokumen']['name'];
			$nama_file_unik  = $acak.$nama_file;
			
			if ($_FILES["dokumen"]["error"] > 0 OR empty($lokasi_file)){
				$nama_file_unik = "";
			}
		
			else{
				UploadDokumen($nama_file_unik, $vdir_upload);
			}
			
		
			//UPDATE STATUS DI PO TERIMA LOG
			mysqli_query($conn,"INSERT INTO po_terima_log (po_terima_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[po_terima_id]', '210', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE po_terima SET status_id='210' WHERE id='$_POST[po_terima_id]'");

			mysqli_query($conn,"UPDATE po_terima_sn a INNER JOIN po_terima_detail b ON a.po_terima_detail_id = b.id SET a.status='2' WHERE b.po_terima_id='$_POST[po_terima_id]'");

			//UPDATE STOK DI GUDANG
			$data=mysqli_query($conn,"SELECT a.*, b.master_material_id, b.master_kondisi_id, b.jumlah_konversi, b.jumlah_total, b.harga, b.master_kategori_material_id, b.jumlah_diterima AS jumlah_diterima_po_detail FROM po_terima_detail a INNER JOIN po_detail b ON a.po_detail_id=b.id AND b.deleted_at IS NULL WHERE a.deleted_at IS NULL AND a.po_terima_id='$_POST[po_terima_id]'");
			while($r=mysqli_fetch_array($data)){

				//UPDATE PO DETAIL
				$jumlah_terima_po_detail = $r['jumlah_diterima_po_detail'] + $r['jumlah_diterima'];
				mysqli_query($conn,"UPDATE po_detail SET jumlah_diterima='$jumlah_terima_po_detail' WHERE id='$r[po_detail_id]'");

				//CEK APAKAH SUDAH ADA GUDANG
				$cek=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM stok WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND master_gudang_id='$r[master_gudang_id]' AND master_material_id='$r[master_material_id]' AND deleted_at IS NULL"));
				$jumlah_masuk = $r['jumlah_diterima'] * $r['jumlah_konversi'];

				//UPDATE STOK
				if(isset($cek['id'])!=''){
					$stok_id = $cek['id'];

					$balance_current = $cek['jumlah']+$jumlah_masuk;

					mysqli_query($conn,"UPDATE stok SET jumlah='$balance_current', updated_at='$waktu_sekarang' WHERE id='$cek[id]'");
				}
				else{
					$balance_current = $jumlah_masuk;

					mysqli_query($conn,"INSERT INTO stok (master_cabang_id, master_gudang_id, master_material_id, jumlah, created_at, updated_at) VALUES ('$_SESSION[master_cabang_id]', '$r[master_gudang_id]', '$r[master_material_id]', '$balance_current', '$waktu_sekarang', '$waktu_sekarang')");

					$stok_id = mysqli_insert_id($conn);
				}

				//CEK APAKAH INI BARANG BARU ATAU BEKAS UNTUK DIRELAKSI KE KONDISI PADA BAGIAN STOK GUDANG
				$cek_saldo_kondisi = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM stok_kondisi WHERE stok_id='$stok_id' AND deleted_at IS NULL AND master_kondisi_id='$r[master_kondisi_id]'"));
				if(isset($cek_saldo_kondisi['id'])!=''){
					$balance_current = $cek_saldo_kondisi['jumlah']+$jumlah_masuk;
					mysqli_query($conn,"UPDATE stok_kondisi SET jumlah='$balance_current', updated_at='$waktu_sekarang' WHERE id='$cek_saldo_kondisi[id]'");
				}
				else{
					$balance_current = $jumlah_masuk;

					mysqli_query($conn,"INSERT INTO stok_kondisi (stok_id, master_kondisi_id, jumlah, created_at, updated_at) VALUES ('$stok_id', '$r[master_kondisi_id]', '$balance_current', '$waktu_sekarang', '$waktu_sekarang')");
				}

				//CATAT KE DALAM LOG
				mysqli_query($conn,"INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$stok_id', '$jumlah_masuk', '0', '$balance_current', '$waktu_sekarang', 'Receipt of materials', '$r[id]', '101', 'po_terima_detail', '1', '$_POST[po_terima_id]', '$number')");


				//MASUKKAN DATA KE DALAM MASTER MATERIAL SERIAL NUMBER
				$terima_sn = mysqli_query($conn,"SELECT * FROM po_terima_sn WHERE po_terima_detail_id='$r[id]' AND status='2'");
				while($tsn=mysqli_fetch_array($terima_sn)){
					mysqli_query($conn,"INSERT INTO material_sn (master_material_id, status_id, keterangan, serial_number, master_gudang_id, created_at, harga, master_kategori_material_id, master_kondisi_id, master_klasifikasi_material_id) VALUES ('$r[master_material_id]', '$tsn[material_sn_status_id]', '', '$tsn[serial_number]', '$r[master_gudang_id]', '$waktu_sekarang', '$tsn[harga]', '$r[master_kategori_material_id]', '$r[master_kondisi_id]', '$tsn[master_klasifikasi_material_id]')");

					$material_sn_id = mysqli_insert_id($conn);

					mysqli_query($conn,"UPDATE po_terima_sn SET material_sn_id='$material_sn_id' WHERE id='$tsn[id]'");

					//MASUKKAN DATA LOG SERIAL NUMBER
					mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark, act_type_id, act_table_id, transaction_number) VALUES ('$material_sn_id', '515', '$waktu_sekarang', 'Receipt of materials', '1', '$_POST[po_terima_id]', '$number')");
				}
			}

			//UPDATE STATUS PO
			$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(jumlah) AS total FROM po_detail WHERE po_id='$_POST[po_id]' AND deleted_at IS NULL"));
			$total_item_po = $cek['total'];

			$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(a.jumlah_diterima) AS total FROM po_terima_detail a INNER JOIN po_terima b ON a.po_terima_id=b.id AND b.deleted_at IS NULL WHERE a.deleted_at IS NULL AND b.po_id='$_POST[po_id]' AND b.status_id='210';"));
			$total_item_terima = $cek['total'];

			if($total_item_po==$total_item_terima){
				$status_po=25;
			}
			else{
				$status_po=20;
			}
		
			mysqli_query($conn,"UPDATE po SET status_id='$status_po', updated_at='$waktu_sekarang' WHERE id='$_POST[po_id]' AND deleted_at IS NULL");
			
			mysqli_query($conn,"INSERT INTO po_log (po_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[po_id]', '$status_po', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}
		header("location: terimapo-view-$_POST[po_terima_id]");
	}
	mysqli_close($conn);
	
}
?>