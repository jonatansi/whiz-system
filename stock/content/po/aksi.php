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
    
	$module = "Purchase Order";
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

	else if($act=='tambah_material'){
		include "add/tambah_material.php";
	}

	else if($act=='input_material'){
		$jumlah = str_replace(".","",$_POST['jumlah']);
		$jumlah_konversi = str_replace(".","",$_POST['jumlah_konversi']);
		$harga = str_replace(".","",$_POST['harga']);


		$jumlah_total = $jumlah * $jumlah_konversi;

		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[master_material_id]' AND deleted_at IS NULL"));

        $sql="INSERT INTO po_detail (master_kategori_material_id, master_material_id, master_kondisi_id, master_satuan_besar_id, jumlah_konversi, master_satuan_kecil_id, jumlah, jumlah_total, harga, created_at, updated_at, created_pegawai_id) VALUES ('$_POST[master_kategori_material_id]', '$_POST[master_material_id]', '$_POST[master_kondisi_id]', '$_POST[master_satuan_besar_id]', '$jumlah_konversi', '$d[master_satuan_id]', '$jumlah', '$jumlah_total', '$harga', '$waktu_sekarang', '$waktu_sekarang', '$_SESSION[login_user]')";

		mysqli_query($conn,$sql);
	}
	
	else if($act=='delete_material'){
		$sql="UPDATE po_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'";

		mysqli_query($conn,$sql);
	}

    else if($act=='table_add_material'){
        include "add/table.php";
	}

	else if($act=='input'){
		mysqli_begin_transaction($conn);
		try {
			//GENERATE NUMBER
			$tanggal_awal = "$thn_sekarang-01-01";
			$tanggal_akhir = "$thn_sekarang-12:31";

			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM po WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

			$b=mysqli_fetch_array(mysqli_query($conn,"SELECT kode FROM master_cabang WHERE id='$_POST[request_master_cabang_id]' AND deleted_at IS NULL"));
			$kode_cabang=$b['kode'];

			$urutan = $a['urutan']+1;
			$urutan_nomor= sprintf("%05s",$urutan);

			// $po_number = "PO-$thn".$bulan."-".$kode_cabang.$urutan_nomor;
			$po_number = "PO-UVT-".$thn.$bulan."-".$kode_cabang.$urutan_nomor;;

			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT master_cabang_id FROM pegawai WHERE id='$_SESSION[login_user]' AND deleted_at IS NULL"));

			$sql="INSERT INTO po (nomor, created_master_cabang_id, created_pegawai_id, tanggal, request_master_cabang_id, master_vendor_id, request_pic_nama, request_pic_hp, nomor_penawaran, alamat_tujuan, status_id, created_at, updated_at, deskripsi, urutan, lok_provinsi_id, lok_kabupaten_id, lok_kecamatan_id, lok_kelurahan_id, vendor_pic_nama, vendor_pic_hp, tujuan_kode_pos) VALUES ('$po_number', '$d[master_cabang_id]', '$_SESSION[login_user]', '$tgl_sekarang', '$_POST[request_master_cabang_id]', '$_POST[master_vendor_id]', '$_POST[request_pic_nama]', '$_POST[request_pic_hp]', '$_POST[nomor_penawaran]', '$_POST[alamat_tujuan]', '1', '$waktu_sekarang', '$waktu_sekarang', '$_POST[deskripsi]', '$urutan', '$_POST[lok_provinsi_id]', '$_POST[lok_kabupaten_id]', '$_POST[lok_kecamatan_id]', '$_POST[lok_kelurahan_id]', '$_POST[vendor_pic_nama]', '$_POST[vendor_pic_hp]', '$_POST[tujuan_kode_pos]')";

			mysqli_query($conn, $sql);

			$id = mysqli_insert_id($conn);

			mysqli_query($conn,"UPDATE po_detail SET po_id='$id', updated_at='$waktu_sekarang' WHERE po_id IS NULL AND created_pegawai_id='$_SESSION[login_user]' AND deleted_at IS NULL");

			mysqli_query($conn,"INSERT INTO po_log (po_id, status_id, created_at, pegawai_id) VALUES ('$id', '1', '$waktu_sekarang', '$_SESSION[login_user]')");
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}
		header("location: po");
	}

	else if($act=='cetak'){
		include "cetak.php";
	}

	else if($act=='next'){
		include "next.php";
	}

	else if($act=='next_action'){
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

		mysqli_query($conn,"INSERT INTO po_log (po_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[po_id]', '$_POST[next_status_id]', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

		mysqli_query($conn,"UPDATE po SET status_id='$_POST[next_status_id]' WHERE id='$_POST[po_id]'");

		header("location: po-view-$_POST[po_id]");
	}

	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_query($conn,"INSERT INTO po_log (po_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[po_id]', '5', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

		mysqli_query($conn,"UPDATE po SET status_id='5' WHERE id='$_POST[po_id]'");

		mysqli_query($conn,"UPDATE po_detail SET deleted_at='$waktu_sekarang' WHERE po_id='$_POST[po_id]'");

		header("location: po-view-$_POST[po_id]");
	}

	mysqli_close($conn);
	
}
?>