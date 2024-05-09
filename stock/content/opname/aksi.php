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
    
	$module = "Stok Opname Material";
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

    else if($act=='tambah_material'){
		include "add/tambah_material.php";
	}

	else if($act=='input_material'){
		$jumlah_aktual = str_replace(".","",$_POST['jumlah']);

		//AMBIL DATA JUMLAH STOK YANG TERCATAT DALAM DATABASE 
		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT b.id, b.stok_id, b.jumlah FROM stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id WHERE a.master_cabang_id='$_SESSION[master_cabang_id]' AND a.master_gudang_id='$_POST[master_gudang_id]' AND a.master_material_id='$_POST[master_material_id]' AND b.master_kondisi_id='$_POST[master_kondisi_id]'"));

		if(isset($cek['id'])!=''){
			$stok_id=$cek['stok_id'];
			$jumlah_tercatat = $cek['jumlah'];
			$stok_kondisi_id = $cek['id'];
		}
		else{
			mysqli_query($conn,"INSERT INTO stok (master_cabang_id, master_gudang_id, master_material_id, jumlah, created_at, updated_at) VALUES ('$_SESSION[master_cabang_id]', '$_POST[master_gudang_id]', '$_POST[master_material_id]', '0', '$waktu_sekarang', '$waktu_sekarang')");
			$stok_id = mysqli_insert_id($conn);

			mysqli_query($conn,"INSERT INTO stok_kondisi (stok_id, master_kondisi_id, jumlah, created_at, updated_at) VALUES ('$stok_id', '$_POST[master_kondisi_id]', '0', '$waktu_sekarang', '$waktu_sekarang')");
			$stok_kondisi_id = mysqli_insert_id($conn);

			$jumlah_tercatat = 0;
		}

		//CEK DATA YANG DIMASUKKAN KE DALAM OPNAME DETAIL NAMUN BELUM DIPROSES 
		$md=mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM opname_detail WHERE stok_kondisi_id='$stok_kondisi_id' AND opname_id IS NULL AND deleted_at IS NULL"));

		$jumlah_opname=0;
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[master_material_id]' AND deleted_at IS NULL"));

		if(isset($md['id'])!=''){
			$sql="UPDATE opname_detail SET updated_at='$waktu_sekarang', jumlah_tercatat='$jumlah_tercatat', jumlah_aktual='$jumlah_aktual', remark='$_POST[remark]' WHERE id='$md[id]'";
		}
		else{
			$sql="INSERT INTO opname_detail (master_material_id, master_satuan_kecil_id, master_kategori_material_id, jumlah_tercatat, jumlah_aktual, remark, master_kondisi_id, stok_kondisi_id, created_pegawai_id, created_at, updated_at, master_gudang_id) VALUES ('$_POST[master_material_id]', '$_POST[master_satuan_kecil_id]', '$d[master_kategori_material_id]', '$jumlah_tercatat', '$jumlah_aktual', '$_POST[remark]', '$_POST[master_kondisi_id]', '$stok_kondisi_id', '$_SESSION[login_user]', '$waktu_sekarang', '$waktu_sekarang', '$_POST[master_gudang_id]')";
		}
		mysqli_query($conn,$sql);
		
	}

	else if($act=='delete_material'){
		$sql="UPDATE opname_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'";

		mysqli_query($conn,$sql);
	}

    else if($act=='table_add_material'){
        include "add/table.php";
	}

	else if($act=='input'){
		$tanggal_awal = "$thn_sekarang-01-01";
		$tanggal_akhir = "$thn_sekarang-12:31";

		$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM opname WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

		$urutan = $a['urutan']+1;
		$urutan_nomor= sprintf("%05s",$urutan);

		$number = "SO-UVT-$urutan_nomor-$thn".$bulan;

		$status_id = 350;

		$sql="INSERT INTO opname (nomor, tanggal, created_master_cabang_id, created_pegawai_id, pic_pegawai_id, pic_pegawai_jabatan, urutan, status_id, created_at, updated_at, deskripsi, master_gudang_id) VALUES ('$number', '$_POST[tanggal]', '$_SESSION[master_cabang_id]', '$_SESSION[login_user]',  '$_POST[pic_pegawai_id]', '$_POST[pic_pegawai_jabatan]', '$urutan', '$status_id', '$waktu_sekarang', '$waktu_sekarang', '$_POST[remark]', '$_POST[master_gudang_id]')";


		mysqli_query($conn, $sql);

		$id = mysqli_insert_id($conn);

		mysqli_query($conn,"INSERT INTO opname_log (opname_id, status_id, created_at, pegawai_id) VALUES ('$id', '$status_id', '$waktu_sekarang', '$_SESSION[login_user]')");

		$data=mysqli_query($conn,"SELECT a.*, b.master_material_id, b.master_gudang_id, c.master_kategori_material_id, c.master_satuan_id  FROM stok_kondisi a 
		INNER JOIN stok b ON a.stok_id=b.id AND b.deleted_at IS NULL 
		INNER JOIN master_material c ON b.master_material_id=c.id
		WHERE b.master_gudang_id='$_POST[master_gudang_id]' AND a.deleted_at IS NULL");
		while($r=mysqli_fetch_array($data)){
			mysqli_query($conn,"INSERT INTO opname_detail (opname_id, master_material_id, master_satuan_kecil_id, master_kategori_material_id, jumlah_tercatat, master_kondisi_id, stok_kondisi_id, created_pegawai_id, master_gudang_id, created_at, updated_at) VALUES ('$id', '$r[master_material_id]', '$r[master_satuan_id]', '$r[master_kategori_material_id]', '$r[jumlah]', '$r[master_kondisi_id]', '$r[id]', '$_SESSION[login_user]', '$r[master_gudang_id]', '$waktu_sekarang', '$waktu_sekarang')");
		}
		mysqli_query($conn,"UPDATE opname_detail SET opname_id='$id', updated_at='$waktu_sekarang' WHERE opname_id IS NULL AND created_pegawai_id='$_SESSION[login_user]' AND deleted_at IS NULL");

		header("location: opname");
	}

	else if($act=='cetak'){
		include "cetak.php";
	}

	else if($act=='next'){
		include "next.php";
	}

	else if($act=='next_action'){
		mysqli_begin_transaction($conn);
		try {
			$vdir_upload = "../../../files/opname/";

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

			mysqli_query($conn,"INSERT INTO opname_log (opname_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[opname_id]', '$_POST[next_status_id]', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE opname SET status_id='$_POST[next_status_id]' WHERE id='$_POST[opname_id]'");

			if($_POST['next_status_id']=='320'){
				//HEADER opname
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname WHERE id='$_POST[opname_id]' AND deleted_at IS NULL"));
				$number = $d['nomor'];

				//UPDATE BAHWA TELAH JADI DIopnameKAN
				mysqli_query($conn,"UPDATE opname_sn a INNER JOIN opname_detail b ON a.opname_detail_id = b.id SET a.status='2' WHERE b.opname_id='$_POST[opname_id]'");

				//PINDAHKAN STOK KONDISI
				$data = mysqli_query($conn,"SELECT a.* FROM opname_detail a WHERE a.opname_id='$_POST[opname_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 

					//CATAT DI LOG PENGURANGAN STOK
					$st=mysqli_fetch_array(mysqli_query($conn,"SELECT a.id, a.jumlah FROM stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id WHERE b.id='$r[stok_kondisi_id]' AND a.deleted_at IS NULL"));
					$balance_current = $st['jumlah']-$r['jumlah'];

					mysqli_query($conn,"INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name) VALUES ('$st[id]', '0', '$r[jumlah]', '$balance_current', '$waktu_sekarang', 'Pengopnamean material $number', '$r[id]', '105', 'opname_detail')");

					//UPDATE STOK KONDISI DI GUDANG ASAL
					mysqli_query($conn,"UPDATE stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id SET a.jumlah=(a.jumlah-$r[jumlah]), b.jumlah=(b.jumlah-$r[jumlah]) WHERE b.id='$r[stok_kondisi_id]' AND a.deleted_at IS NULL");
					
				}

				//UPDATE POSISI MATERIAL SN
				$data = mysqli_query($conn,"SELECT b.* FROM opname_detail a INNER JOIN opname_sn b ON a.id=b.opname_detail_id WHERE a.opname_id='$_POST[opname_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 
					
					//UPDATE INFO MATERIAL SN TELAH DIopnameKAN
					mysqli_query($conn,"UPDATE material_sn SET status_id='505', master_gudang_id='0' WHERE id='$r[material_sn_id]'");

					//CATAT DI LOG MATERIAL SN
					mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark) VALUES ('$r[material_sn_id]', '525', '$waktu_sekarang', 'Pengopnamean Material pada Transaksi $number')");
				}
			}

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}

		header("location: opname-view-$_POST[opname_id]");
	}

	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_begin_transaction($conn);
		try {
			mysqli_query($conn,"INSERT INTO opname_log (opname_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[opname_id]', '255', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE opname SET status_id='255' WHERE id='$_POST[opname_id]'");
			mysqli_query($conn,"UPDATE opname_sn a INNER JOIN opname_detail b ON a.opname_detail_id = b.id SET a.status='3' WHERE b.opname_id='$_POST[opname_id]'");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
		}
		header("location: opname-view-$_POST[opname_id]");
	}

	else if($act=='sn_input'){
		//CEK APAKAH SERIAL NUMBER ITU MEMANG ADA DI GUDANG ITU
		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT a.* FROM material_sn a 
		INNER JOIN opname_detail b ON a.master_gudang_id=b.master_gudang_asal_id AND b.deleted_at IS NULL AND a.master_material_id=b.master_material_id
		WHERE a.serial_number='$_POST[serial_number]' AND b.id='$_POST[opname_detail_id]'"));

		if(isset($cek['id'])!=''){
			//CEK APAKAH SERIAL NUMBER INI SUDAH ADA DI DALAM LIST opname SN ATAU BELUM DIKECUALIKAN UNTUK '0'
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE opname_detail_id='$_POST[opname_detail_id]' AND serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			if(isset($d['id'])==''){
				mysqli_query($conn,"INSERT INTO opname_sn (opname_detail_id, serial_number, created_at, material_sn_id) VALUES ('$_POST[opname_detail_id]', '$_POST[serial_number]', '$waktu_sekarang', '$cek[id]')");

				header("location: opname-sn-$_POST[opname_detail_id]");
			}
			else{
				?>
				<script type="text/javascript">
					alert("Serial Number sudah ada dalam daftar yang akan diopname");
					window.history.back();
				</script>
				<?php
			}
		}
		else{
			?>
			<script type="text/javascript">
				alert("Serial number tersebut tidak ada dalam gudang itu atau serial number tidak sesuai");
				window.history.back();
			</script>
			<?php
		}

	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE id='$_GET[id]'"));

		mysqli_query($conn,"DELETE FROM opname_sn WHERE id='$_GET[id]'");

		header("location: opname-sn-$d[opname_detail_id]");
	}


	mysqli_close($conn);
	
}
?>