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
    
	$module = "Dismantle Material";

	$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

	else if($act=='tambah_form'){
		include "tambah_form.php";
	}

	else if($act=='useridentity'){
		include "useridentity.php";
	}

	else if($act=='input'){
		mysqli_begin_transaction($conn);
		try {
			$tanggal_awal = "$thn_sekarang-01-01";
			$tanggal_akhir = "$thn_sekarang-12:31";

			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM dismantle WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

			$urutan = $a['urutan']+1;
			$urutan_nomor= sprintf("%05s",$urutan);

			$number = "DO-WDB-$urutan_nomor-$thn".$bulan;

			//INSERT KE DISMANTLE HEADER
			mysqli_query($conn,"INSERT INTO dismantle (nomor, tanggal, master_guna_id, master_guna_kategori_id, user_identity, status_id, urutan, remark, created_master_cabang_id, created_pegawai_id, created_at, updated_at) VALUES ('$number', '$_POST[tanggal]', '$_POST[master_guna_id]', '$_POST[master_guna_kategori_id]', '$_POST[user_identity]', '550', '$urutan', '$_POST[remark]', '$_SESSION[master_cabang_id]', '$_SESSION[login_user]', '$waktu_sekarang', '$waktu_sekarang')");

			$id = mysqli_insert_id($conn);

			foreach($_POST['material_sn_id'] AS $material_sn_id){
				$sn = mysqli_fetch_array(mysqli_query($conn,"SELECT serial_number FROM material_sn WHERE id='$material_sn_id'"));

				$material_sn_status_id = $_POST["material_sn_status_id_$material_sn_id"];
				$master_gudang_id = $_POST["master_gudang_id_$material_sn_id"];
				$remark = $_POST["remark_$material_sn_id"];

				$sql="INSERT INTO dismantle_sn (dismantle_id, material_sn_id, serial_number, status, material_sn_status_id, remark, created_at, master_gudang_id) VALUES ('$id', '$material_sn_id', '$sn[serial_number]', '1', '$material_sn_status_id', '$remark', '$waktu_sekarang', '$master_gudang_id')";

				mysqli_query($conn, $sql);
			}

			mysqli_query($conn,"INSERT INTO dismantle_log (dismantle_id, status_id, created_at, pegawai_id, remark) VALUES ('$id', '550', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			print_r($e);
		}
		header("location: dismantle");
	}

	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_begin_transaction($conn);
		try {
			mysqli_query($conn,"INSERT INTO dismantle_log (dismantle_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[dismantle_id]', '570', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE dismantle SET status_id='570' WHERE id='$_POST[dismantle_id]'");
			
			mysqli_query($conn,"UPDATE dismantle_sn SET status='3' WHERE dismantle_id='$_POST[dismantle_id]'");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
		}
		header("location: dismantle-view-$_POST[dismantle_id]");
	}

	else if($act=='next'){
		include "next.php";
	}

	else if($act=='next_action'){
		mysqli_begin_transaction($conn);
		try {
			$vdir_upload = "../../../files/dismantle/";

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
			
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT nomor FROM dismantle WHERE id='$_POST[dismantle_id]'"));
			$number = $d['nomor'];
			
			//UPDATE STATUS DI PO TERIMA LOG
			mysqli_query($conn,"INSERT INTO dismantle_log (dismantle_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[dismantle_id]', '560', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE dismantle SET status_id='560' WHERE id='$_POST[dismantle_id]'");

			mysqli_query($conn,"UPDATE dismantle_sn SET status='2' WHERE dismantle_id='$_POST[dismantle_id]'");

			//UPDATE STOK DI GUDANG
			$data=mysqli_query($conn,"SELECT a.*, b.master_material_id, b.master_kondisi_id FROM dismantle_sn a INNER JOIN material_sn b ON a.material_sn_id=b.id WHERE a.status='2' AND a.dismantle_id='$_POST[dismantle_id]'");
			while($r=mysqli_fetch_array($data)){

				$jumlah_masuk = 1;
				//CEK APAKAH SUDAH ADA GUDANG
				$cek=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM stok WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND master_gudang_id='$r[master_gudang_id]' AND master_material_id='$r[master_material_id]' AND deleted_at IS NULL"));
				
				//UPDATE STATUS MATERIAL SN
				mysqli_query($conn,"UPDATE material_sn SET status_id='$r[material_sn_status_id]', master_gudang_id='$r[master_gudang_id]' WHERE id='$r[material_sn_id]'");
				
				//CATAT DI LOG MATERIAL SN
				mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark, act_type_id, act_table_id, transaction_number) VALUES ('$r[material_sn_id]', '530', '$waktu_sekarang', 'Dismantle Material', '4', '$_POST[dismantle_id]', '$number')");

				if($r['material_sn_status_id']=='501'){
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
					mysqli_query($conn,"INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$stok_id', '$jumlah_masuk', '0', '$balance_current', '$waktu_sekarang', 'Dismantle of materials', '$r[id]', '125', 'dismantle_sn', '4', '$_POST[dismantle_id]', '$number')");

				}
			}
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}
		header("location: dismantle-view-$_POST[dismantle_id]");
	}
	mysqli_close($conn);
	
}
?>