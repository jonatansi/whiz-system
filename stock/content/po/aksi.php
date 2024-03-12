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

	else if($act=='edit_material'){
		include "add/edit_material.php";
	}

	else if($act=='update_material'){
	}
	
	else if($act=='delete_material'){
		$sql="UPDATE po_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'";

		mysqli_query($conn,$sql);
	}

    else if($act=='table_add_material'){
        include "add/table.php";
	}

    //EDIT PO
    else if($act=='tambah_material_edit'){
		include "edit/tambah_material.php";
	}

	else if($act=='input_material_edit'){
        
	}

	else if($act=='edit_material_edit'){
		include "edit/edit_material.php";
	}

	else if($act=='update_material_edit'){
	}
	
	else if($act=='delete_material_edit'){
		
	}

	else if($act=='input'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]' AND deleted_at IS NULL"));

		$sql="INSERT INTO po (nomor, created_master_cabang_id, created_pegawai_id, tanggal, request_master_cabang_id, master_vendor_id, pic_nama, pic_hp, nomor_penawaran, alamat_tujuan, status_id, created_at, updated_at, deskripsi) VALUES ('$_POST[nomor]', '$d[master_cabang_id]', '$_SESSION[login_user]', '$_POST[tanggal]', '$_POST[request_master_cabang_id]', '$_POST[master_vendor_id]', '$_POST[pic_nama]', '$_POST[pic_hp]', '$_POST[nomor_penawaran]', '$_POST[alamat_tujuan]', '1', '$waktu_sekarang', '$waktu_sekarang', '$_POST[deskripsi]')";

		mysqli_query($conn, $sql);

		$id = mysqli_insert_id($conn);

		mysqli_query($conn,"UPDATE po_detail SET po_id='$id', updated_at='$waktu_sekarang' WHERE po_id IS NULL AND created_pegawai_id='$_SESSION[login_user]'");


		mysqli_query($conn,"INSERT INTO po_log (po_id, status_id, created_at, pegawai_id) VALUES ('$id', '1', '$waktu_sekarang', '$_SESSION[login_user]')");

		header("location: po");
	}

	else if($act=='cetak'){
		include "cetak.php";
	}

	mysqli_close($conn);
	
}
?>