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
    
	$module = "Mutasi Stok";
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

    else if($act=='tambah_material'){
		include "add/tambah_material.php";
	}

	else if($act=='input_material'){
		$jumlah = str_replace(".","",$_POST['jumlah']);

		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT b.id, b.stok_id, b.jumlah FROM stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id WHERE a.master_cabang_id='$_SESSION[master_cabang_id]' AND a.master_gudang_id='$_POST[master_gudang_asal_id]' AND a.master_material_id='$_POST[master_material_id]' AND b.master_kondisi_id='$_POST[master_kondisi_id]'"));

		if(isset($cek['id'])!=''){
			//DATA YANG DIMASUKKAN KE DALAM MUTASI DETAIL NAMUN BELUM DIPROSES 
			$md=mysqli_fetch_array(mysqli_query($conn,"SELECT id, jumlah AS jumlah_mutasi FROM mutasi_detail WHERE stok_kondisi_id='$cek[id]' AND mutasi_id IS NULL AND deleted_at IS NULL"));

			$jumlah_mutasi=0;
			if(isset($md['id'])!=''){
				$jumlah_mutasi = $md['jumlah_mutasi'];
			}
			
			$total_akan_mutasi = $jumlah+$jumlah_mutasi;

			if($cek['jumlah']>=$total_akan_mutasi){
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[master_material_id]' AND deleted_at IS NULL"));
	
				if(isset($md['id'])!=''){
					$sql="UPDATE mutasi_detail SET updated_at='$waktu_sekarang', jumlah = (jumlah+$jumlah_mutasi) WHERE id='$md[id]'";
				}
				else{
					$sql="INSERT INTO mutasi_detail (master_material_id, master_gudang_asal_id, master_satuan_kecil_id, jumlah, created_pegawai_id, master_kategori_material_id, master_kondisi_id, created_at, updated_at, stok_kondisi_id) VALUES ('$_POST[master_material_id]', '$_POST[master_gudang_asal_id]', '$_POST[master_satuan_kecil_id]', '$jumlah', '$_SESSION[login_user]', '$d[master_kategori_material_id]', '$_POST[master_kondisi_id]', '$waktu_sekarang', '$waktu_sekarang', '$cek[stok_id]')";
				}
				mysqli_query($conn,$sql);
			}
			else{
				echo "<div class='alert alert-danger'>Gagal transaksi. Jumlah item yang akan dimutasi adalah <b>$total_akan_mutasi</b> dan lebih besar dari jumlah stok di gudang.</div>";
			}
		}
		else{
			echo "<div class='alert alert-danger'>Gagal transaksi. Jumlah item yang akan dimutasi lebih besar dari jumlah stok di gudang.</div>";
		}

		
	}

	else if($act=='delete_material'){
		$sql="UPDATE mutasi_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'";

		mysqli_query($conn,$sql);
	}

    else if($act=='table_add_material'){
        include "add/table.php";
	}

	else if($act=='input'){
		$tanggal_awal = "$thn_sekarang-01-01";
		$tanggal_akhir = "$thn_sekarang-12:31";

		$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM mutasi WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

		$urutan = $a['urutan']+1;
		$urutan_nomor= sprintf("%05s",$urutan);

		$number = "MM-UVT-$urutan_nomor-$thn".$bulan;

		$status_id = 250;

		$sql="INSERT INTO mutasi (nomor, created_master_cabang_id, created_pegawai_id, tanggal, request_pegawai_id, master_gudang_tujuan_id, status_id, created_at, updated_at, urutan, deskripsi, request_pegawai_jabatan) VALUES ('$number', '$_SESSION[master_cabang_id]', '$_SESSION[login_user]',  '$_POST[tanggal]', '$_POST[request_pegawai_id]', '$_POST[master_gudang_tujuan_id]', '$status_id', '$waktu_sekarang', '$waktu_sekarang', '$urutan', '$_POST[deskripsi]', '$_POST[request_pegawai_jabatan]')";

		mysqli_query($conn, $sql);

		$id = mysqli_insert_id($conn);

		mysqli_query($conn,"UPDATE mutasi_detail SET mutasi_id='$id', updated_at='$waktu_sekarang' WHERE mutasi_id IS NULL AND created_pegawai_id='$_SESSION[login_user]' AND deleted_at IS NULL");

		mysqli_query($conn,"INSERT INTO mutasi_log (mutasi_id, status_id, created_at, pegawai_id) VALUES ('$id', '$status_id', '$waktu_sekarang', '$_SESSION[login_user]')");

		header("location: mutasi");
	}

	else if($act=='cetak'){
		include "cetak.php";
	}

	else if($act=='next'){
		include "next.php";
	}

	else if($act=='next_action'){
		$vdir_upload = "../../../files/mutasi/";

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

		mysqli_query($conn,"INSERT INTO mutasi_log (mutasi_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[mutasi_id]', '$_POST[next_status_id]', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

		mysqli_query($conn,"UPDATE mutasi SET status_id='$_POST[next_status_id]' WHERE id='$_POST[mutasi_id]'");

		header("location: mutasi-view-$_POST[mutasi_id]");
	}

	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_query($conn,"INSERT INTO mutasi_log (mutasi_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[mutasi_id]', '255', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

		header("location: mutasi-view-$_POST[mutasi_id]");
	}

	else if($act=='sn_input'){
		//CEK APAKAH SERIAL NUMBER ITU MEMANG ADA DI GUDANG ITU
		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT a.* FROM material_sn a 
		INNER JOIN mutasi_detail b ON a.master_gudang_id=b.master_gudang_asal_id AND b.deleted_at IS NULL WHERE a.serial_number='$_POST[serial_number]' AND b.id='$_POST[mutasi_detail_id]'"));

		if(isset($cek['id'])!=''){
			//CEK APAKAH SERIAL NUMBER INI SUDAH ADA DI DALAM LIST MUTASI SN ATAU BELUM DIKECUALIKAN UNTUK '0'
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM mutasi_sn WHERE mutasi_detail_id='$_POST[mutasi_detail_id]' AND serial_number='$_POST[serial_number]' AND serial_number!='0'"));
			if(isset($d['id'])==''){
				mysqli_query($conn,"INSERT INTO mutasi_sn (mutasi_detail_id, serial_number, created_at, material_sn_id) VALUES ('$_POST[mutasi_detail_id]', '$_POST[serial_number]', '$waktu_sekarang', '$cek[id]')");

				header("location: mutasi-sn-$_POST[mutasi_detail_id]");
			}
			else{
				?>
				<script type="text/javascript">
					alert("Serial Number sudah ada dalam daftar yang akan dimutasi");
					window.history.back();
				</script>
				<?php
			}
		}
		else{
			?>
			<script type="text/javascript">
				alert("Serial number tersebut tidak ada dalam gudang itu");
				window.history.back();
			</script>
			<?php
		}

	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM mutasi_sn WHERE id='$_GET[id]'"));

		mysqli_query($conn,"DELETE FROM mutasi_sn WHERE id='$_GET[id]'");

		header("location: mutasi-sn-$d[mutasi_detail_id]");
	}


	mysqli_close($conn);
	
}
?>