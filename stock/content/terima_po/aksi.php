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

			$number = "IN-UVT-$urutan_nomor-$thn".$bulan;

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

			$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM po_detail WHERE jumlah!=jumlah_diterima AND po_id='$_POST[po_id]' AND deleted_at IS NULL"));
			if(isset($cek['id'])==''){
				$status_po=25;
			}
			else{
				$status_po=20;
			}

			mysqli_query($conn,"INSERT INTO po_terima_log (po_terima_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$id', '$status_po', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");
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
		
		//MASUKKAN DATA SERIAL NUMBER
		$sql="INSERT INTO material_sn (master_material_id, status_id, serial_number, master_gudang_id, created_at, table_id, table_name, harga, master_kategori_material_id, master_kondisi_id) VALUES ('$d[master_material_id]', '500', '$_POST[serial_number]', '$d[master_gudang_id]', '$waktu_sekarang', '$d[id]', 'po_terima_detail', '$harga', '$d[master_kategori_material_id]', '$d[master_kondisi_id]')";

		$exists_data = 0;
		if($_POST['serial_number']=='0'){
			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM material_sn WHERE serial_number='$_POST[serial_number]' AND table_id='$d[id]' AND table_name='po_terima_detail'"));

			for($i=$a['tot'];$i<$jumlah_item;$i++){
				mysqli_query($conn, $sql);
				$material_sn_id = mysqli_insert_id($conn);

				//MASUKKAN DATA LOG SERIAL NUMBER
				mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark) VALUES ('$material_sn_id', '515', '$waktu_sekarang', 'Penerimaan PO $d[nomor_po_terima]')");
			}
		}
		else{
			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM material_sn WHERE serial_number='$_POST[serial_number]'"));
			
			if(isset($a['id'])==''){
				mysqli_query($conn, $sql);
				$material_sn_id = mysqli_insert_id($conn);

				//MASUKKAN DATA LOG SERIAL NUMBER
				mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark) VALUES ('$material_sn_id', '515', '$waktu_sekarang', 'Penerimaan PO $d[nomor_po_terima]')");
			}
			else{
				$exists_data = 1;
			}
		}
		

		if($exists_data=='0'){
			//UPDATE STATUS
			$status_terima = 210;
			$tampil=mysqli_query($conn,"SELECT a.id, (a.jumlah_diterima*b.jumlah_konversi) AS jumlah_material, (SELECT COUNT(c.id) AS tot FROM material_sn c WHERE c.table_id=a.id AND c.table_name='po_terima_detail') AS total_sn
			FROM po_terima_detail a
			LEFT JOIN po_detail b ON a.po_detail_id=b.id AND b.deleted_at IS NULL
			WHERE a.deleted_at IS NULL AND po_terima_id='$d[po_terima_id]'");
			while($r=mysqli_fetch_array($tampil)){
				if($r['jumlah_material']!=$r['total_sn']){
					$status_terima=205;
				}	
			}

			mysqli_query($conn,"UPDATE po_terima SET status_id='$status_terima' WHERE id='$d[po_terima_id]'");

			header("location: terimapo-sn-$d[id]");
		}
		else{
			?>
			<script type="text/javascript">
				alert("Serial Number sudah ada sebelumnya. Mohon periksa dengan baik");
				window.history.back();
			</script>
			<?php
		}
	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.po_terima_id FROM material_sn a INNER JOIN po_terima_detail b ON a.table_id=b.id AND b.deleted_at IS NULL WHERE a.id='$_GET[id]'"));

		mysqli_query($conn,"DELETE FROM material_sn WHERE id='$_GET[id]'");

		mysqli_query($conn,"UPDATE po_terima SET status_id='205' WHERE id='$d[po_terima_id]'");

		header("location: terimapo-sn-$d[table_id]");
	}


	mysqli_close($conn);
	
}
?>