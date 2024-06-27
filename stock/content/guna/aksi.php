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
    
	$module = "Penggunaan Material";
	
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
			//DATA YANG DIMASUKKAN KE DALAM guna DETAIL NAMUN BELUM DIPROSES 
			$md=mysqli_fetch_array(mysqli_query($conn,"SELECT id, jumlah AS jumlah_guna FROM guna_detail WHERE stok_kondisi_id='$cek[id]' AND guna_id IS NULL AND deleted_at IS NULL"));

			$jumlah_guna=0;
			if(isset($md['id'])!=''){
				$jumlah_guna = $md['jumlah_guna'];
			}
			
			$total_akan_guna = $jumlah+$jumlah_guna;

			if($cek['jumlah']>=$total_akan_guna){
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[master_material_id]' AND deleted_at IS NULL"));
	
				if(isset($md['id'])!=''){
					$sql="UPDATE guna_detail SET updated_at='$waktu_sekarang', jumlah = (jumlah+$jumlah_guna) WHERE id='$md[id]'";
				}
				else{
					$sql="INSERT INTO guna_detail (master_material_id, master_gudang_asal_id, master_satuan_kecil_id, jumlah, created_pegawai_id, master_kategori_material_id, master_kondisi_id, created_at, updated_at, stok_kondisi_id) VALUES ('$_POST[master_material_id]', '$_POST[master_gudang_asal_id]', '$_POST[master_satuan_kecil_id]', '$jumlah', '$_SESSION[login_user]', '$d[master_kategori_material_id]', '$_POST[master_kondisi_id]', '$waktu_sekarang', '$waktu_sekarang', '$cek[stok_id]')";
				}
				mysqli_query($conn,$sql);
			}
			else{
				echo "<div class='alert alert-danger'>Gagal transaksi. Jumlah item yang akan diguna adalah <b>$total_akan_guna</b> dan lebih besar dari jumlah stok di gudang.</div>";
			}
		}
		else{
			echo "<div class='alert alert-danger'>Gagal transaksi. Jumlah item yang akan diguna lebih besar dari jumlah stok di gudang.</div>";
		}

		
	}

	else if($act=='delete_material'){
		$sql="UPDATE guna_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'";

		mysqli_query($conn,$sql);
	}

    else if($act=='table_add_material'){
        include "add/table.php";
	}

	else if($act=='master_guna_kategori'){
		include "add/master_guna_kategori.php";
	}

	else if($act=='input'){
		$tanggal_awal = "$thn_sekarang-01-01";
		$tanggal_akhir = "$thn_sekarang-12:31";

		mysqli_begin_transaction($conn);
		try {
			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_guna WHERE id='$_POST[master_guna_id]'"));
			if($d['type']=='1'){
				$sql="SELECT id, kode, nama FROM master_customer WHERE id='$_POST[master_guna_kategori_id]'";
				$x=mysqli_fetch_array(mysqli_query($conn,$sql));
				$user_identity = "$x[kode] - $x[nama]";
			}
			else if($d['type']=='2'){
				$sql="SELECT id, nama FROM master_guna_kategori WHERE id='$_POST[master_guna_kategori_id]'";
				$x=mysqli_fetch_array(mysqli_query($conn,$sql));
				$user_identity = $x['nama'];
			}

			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_guna WHERE id='$_POST[master_guna_id]'"));

			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM guna WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

			$urutan = $a['urutan']+1;
			$urutan_nomor= sprintf("%05s",$urutan);

			$number = "$d[kode]-WDB-$urutan_nomor-$thn".$bulan;

			$status_id = 300;

			$sql="INSERT INTO guna (nomor, tanggal, created_master_cabang_id, created_pegawai_id, master_guna_id, used_master_cabang_id, request_pegawai_id, request_pegawai_jabatan, no_ref, alamat_tujuan, lok_provinsi_id, lok_kabupaten_id, lok_kecamatan_id, lok_kelurahan_id, tujuan_kode_pos, urutan, status_id, created_at, updated_at, deskripsi, tujuan_penggunaan, master_guna_kategori_id, user_identity) VALUES ('$number', '$_POST[tanggal]', '$_SESSION[master_cabang_id]', '$_SESSION[login_user]',  '$_POST[master_guna_id]', '$_SESSION[master_cabang_id]', '$_POST[request_pegawai_id]', '$_POST[request_pegawai_jabatan]', '$_POST[no_ref]', '$_POST[alamat_tujuan]', '$_POST[lok_provinsi_id]', '$_POST[lok_kabupaten_id]', '$_POST[lok_kecamatan_id]', '$_POST[lok_kelurahan_id]', '$_POST[tujuan_kode_pos]', '$urutan', '$status_id', '$waktu_sekarang', '$waktu_sekarang', '$_POST[deskripsi]', '$_POST[tujuan_penggunaan]', '$_POST[master_guna_kategori_id]', '$user_identity')";

			mysqli_query($conn, $sql);

			$id = mysqli_insert_id($conn);

			mysqli_query($conn,"UPDATE guna_detail SET guna_id='$id', updated_at='$waktu_sekarang' WHERE guna_id IS NULL AND created_pegawai_id='$_SESSION[login_user]' AND deleted_at IS NULL");

			mysqli_query($conn,"INSERT INTO guna_log (guna_id, status_id, created_at, pegawai_id) VALUES ('$id', '$status_id', '$waktu_sekarang', '$_SESSION[login_user]')");
			
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}

		header("location: guna");
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
			$vdir_upload = "../../../files/guna/";

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

			mysqli_query($conn,"INSERT INTO guna_log (guna_id, status_id, created_at, pegawai_id, dokumen, remark) VALUES ('$_POST[guna_id]', '$_POST[next_status_id]', '$waktu_sekarang', '$_SESSION[login_user]', '$nama_file_unik', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE guna SET status_id='$_POST[next_status_id]' WHERE id='$_POST[guna_id]'");

			if($_POST['next_status_id']=='320'){
				//HEADER guna
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna WHERE id='$_POST[guna_id]' AND deleted_at IS NULL"));
				$number = $d['nomor'];

				//UPDATE BAHWA TELAH JADI DIGUNAKAN
				mysqli_query($conn,"UPDATE guna_sn a INNER JOIN guna_detail b ON a.guna_detail_id = b.id SET a.status='2' WHERE b.guna_id='$_POST[guna_id]'");

				//PINDAHKAN STOK KONDISI
				$data = mysqli_query($conn,"SELECT a.* FROM guna_detail a WHERE a.guna_id='$_POST[guna_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 

					//CATAT DI LOG PENGURANGAN STOK
					$st=mysqli_fetch_array(mysqli_query($conn,"SELECT a.id, a.jumlah FROM stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id WHERE b.id='$r[stok_kondisi_id]' AND a.deleted_at IS NULL"));
					$balance_current = $st['jumlah']-$r['jumlah'];

					mysqli_query($conn,"INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$st[id]', '0', '$r[jumlah]', '$balance_current', '$waktu_sekarang', 'Penggunaan material', '$r[id]', '105', 'guna_detail', '5', '$_POST[guna_id]', '$number')");

					//UPDATE STOK KONDISI DI GUDANG ASAL
					mysqli_query($conn,"UPDATE stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id SET a.jumlah=(a.jumlah-$r[jumlah]), b.jumlah=(b.jumlah-$r[jumlah]) WHERE b.id='$r[stok_kondisi_id]' AND a.deleted_at IS NULL");
					
				}

				//UPDATE POSISI MATERIAL SN
				$data = mysqli_query($conn,"SELECT b.* FROM guna_detail a INNER JOIN guna_sn b ON a.id=b.guna_detail_id WHERE a.guna_id='$_POST[guna_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 
					
					//UPDATE INFO MATERIAL SN TELAH DIGUNAKAN
					mysqli_query($conn,"UPDATE material_sn SET status_id='505', master_guna_id='$d[master_guna_id]', master_guna_kategori_id='$d[master_guna_kategori_id]', user_identity='$d[user_identity]' WHERE id='$r[material_sn_id]'");

					//CATAT DI LOG MATERIAL SN
					mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark, act_type_id, act_table_id, transaction_number) VALUES ('$r[material_sn_id]', '525', '$waktu_sekarang', 'Penggunaan Material', '5', '$_POST[guna_id]', '$number')");
				}
			}

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo $e;
		}

		header("location: guna-view-$_POST[guna_id]");
	}

	else if($act=='cancel'){
		include "cancel.php";
	}

	else if($act=='cancel_action'){

		mysqli_begin_transaction($conn);
		try {
			mysqli_query($conn,"INSERT INTO guna_log (guna_id, status_id, created_at, pegawai_id, remark) VALUES ('$_POST[guna_id]', '255', '$waktu_sekarang', '$_SESSION[login_user]', '$_POST[remark]')");

			mysqli_query($conn,"UPDATE guna SET status_id='255' WHERE id='$_POST[guna_id]'");
			mysqli_query($conn,"UPDATE guna_sn a INNER JOIN guna_detail b ON a.guna_detail_id = b.id SET a.status='3' WHERE b.guna_id='$_POST[guna_id]'");

			mysqli_query($conn,"UPDATE guna_detail SET deleted_at='$waktu_sekarang' WHERE guna_id='$_POST[guna_id]'");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
		}
		header("location: guna-view-$_POST[guna_id]");
	}

	else if($act=='sn_input'){
		//CEK APAKAH SERIAL NUMBER ITU MEMANG ADA DI GUDANG ITU
		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT a.* FROM material_sn a 
		INNER JOIN guna_detail b ON a.master_gudang_id=b.master_gudang_asal_id AND b.deleted_at IS NULL AND a.master_material_id=b.master_material_id
		WHERE a.serial_number='$_POST[serial_number]' AND b.id='$_POST[guna_detail_id]' AND a.status_id IN (500, 501)"));

		if(isset($cek['id'])!=''){
			//CEK APAKAH SERIAL NUMBER INI SUDAH ADA DI DALAM LIST guna SN ATAU BELUM DIKECUALIKAN UNTUK '0'
			// $d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna_sn WHERE guna_detail_id='$_POST[guna_detail_id]' AND serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d1=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d2=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d3=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM po_terima_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d4=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM mutasi_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			if(isset($d1['id'])=='' AND isset($d2['id'])=='' AND isset($d3['id'])=='' AND isset($d4['id'])==''){
				mysqli_query($conn,"INSERT INTO guna_sn (guna_detail_id, serial_number, created_at, material_sn_id) VALUES ('$_POST[guna_detail_id]', '$_POST[serial_number]', '$waktu_sekarang', '$cek[id]')");

				header("location: guna-sn-$_POST[guna_detail_id]");
			}
			else{
				?>
				<script type="text/javascript">
					alert("Serial Number tidak dapat diinput. Hal ini bisa disebabkan oleh beberapa kemungkinan berikut:\n1. Serial Number sudah diinput sebelumnya.\n2. Serial Number sedang dalam proses stok opname.\n3. Serial Number sudah pernah digunakan.\n4. Serial Number sudah diinput oleh cabang lainnya.\n5. Serial Number sedang dalam proses mutasi.\n\nMohon periksa kembali Serial Number yang Anda masukkan dengan teliti. Terima kasih atas perhatian Anda.");
					window.history.back();
				</script>
				<?php
			}
		}
		else{
			?>
			<script type="text/javascript">
				alert("Serial number tersebut tidak ada dalam gudang tersebut atau serial number tidak sesuai");
				window.history.back();
			</script>
			<?php
		}

	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna_sn WHERE id='$_GET[id]'"));

		mysqli_query($conn,"DELETE FROM guna_sn WHERE id='$_GET[id]'");

		header("location: guna-sn-$d[guna_detail_id]");
	}


	mysqli_close($conn);
	
}
?>