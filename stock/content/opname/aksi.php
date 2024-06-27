<?php
session_start();
// error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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


    else if($act=='table_add_material'){
        include "add/table.php";
	}

	else if($act=='input'){
		mysqli_begin_transaction($conn);
		try {
			$tanggal_awal = "$thn_sekarang-01-01";
			$tanggal_akhir = "$thn_sekarang-12:31";

			$a=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(urutan) AS urutan FROM opname WHERE deleted_at IS NULL AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'"));

			$urutan = $a['urutan']+1;
			$urutan_nomor= sprintf("%05s",$urutan);

			$number = "OP-WDB-$urutan_nomor-$thn".$bulan;

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
				//DETAIL DARI OPNAME
				mysqli_query($conn,"INSERT INTO opname_detail (opname_id, master_material_id, master_satuan_kecil_id, master_kategori_material_id, jumlah_tercatat, jumlah_aktual, master_kondisi_id, stok_kondisi_id, created_pegawai_id, master_gudang_id, created_at, updated_at) VALUES ('$id', '$r[master_material_id]', '$r[master_satuan_id]', '$r[master_kategori_material_id]', '$r[jumlah]',  '$r[jumlah]', '$r[master_kondisi_id]', '$r[id]', '$_SESSION[login_user]', '$r[master_gudang_id]', '$waktu_sekarang', '$waktu_sekarang')");

				$opname_detail_id = mysqli_insert_id($conn);

				//MATERIAL SN YANG DIOPNAME
				if($r['jumlah']>0){
					for($i=1;$i<=$r['jumlah'];$i++){
						$sn = mysqli_fetch_array(mysqli_query($conn,"SELECT a.* FROM material_sn a WHERE a.master_material_id='$r[master_material_id]' AND a.status_id IN (500,501) AND a.master_gudang_id='$r[master_gudang_id]' AND a.master_kondisi_id='$r[master_kondisi_id]' AND NOT EXISTS (SELECT NULL FROM opname_sn c WHERE c.serial_number=a.serial_number AND c.opname_detail_id='$opname_detail_id')"));
						if(isset($sn['id'])!=''){
							mysqli_query($conn,"INSERT INTO opname_sn (opname_detail_id, serial_number, material_sn_id, status, harga, created_at, material_sn_status_id, master_klasifikasi_material_id) VALUES ('$opname_detail_id', '$sn[serial_number]', '$sn[id]', '1', '$sn[harga]', '$waktu_sekarang', '$sn[status_id]', '$sn[master_klasifikasi_material_id]')");
						}
					}
				}
			}
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo "Error ".$e;
		}

		header("location: opname");
	}

	else if($act=='table_view_material'){
		include "view/table.php";
	}

	else if($act=='tambah_material'){
		include "view/tambah_material.php";
	}
	
	else if($act=='input_material'){
		mysqli_begin_transaction($conn);
		try {
			$jumlah = str_replace(".","",$_POST['jumlah_aktual']);

			$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname WHERE id='$_POST[opname_id]' AND deleted_at IS NULL"));

			$e=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[master_material_id]' AND deleted_at IS NULL"));

			$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT id, jumlah_aktual FROM opname_detail WHERE master_material_id='$e[id]' AND master_kondisi_id='$_POST[master_kondisi_id]' AND master_gudang_id='$d[master_gudang_id]' AND deleted_at IS NULL"));
			if(isset($cek['id'])==''){
				$sql="INSERT INTO opname_detail (opname_id, master_material_id, master_satuan_kecil_id, master_kategori_material_id, jumlah_tercatat, jumlah_aktual, master_kondisi_id, stok_kondisi_id, master_gudang_id, remark) VALUES ('$_POST[opname_id]', '$e[id]', '$e[master_satuan_id]', '$e[master_kategori_material_id]', '0', '$jumlah', '$_POST[master_kondisi_id]', '0', '$d[master_gudang_id]', '$_POST[remark]')";
			}
			else{
				$sql="UPDATE jumlah_aktual=(jumlah_aktual+$jumlah) WHERE id='$cek[id]'";
			}
			mysqli_query($conn, $sql);
			
			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
			echo "Error ".$e;
		}
	}
	
	else if($act=='edit_material'){
		include "view/edit_material.php";
	}

	else if($act=='update_material'){
		$jumlah_aktual = str_replace(".","",$_POST['jumlah']);

		mysqli_query($conn,"UPDATE opname_detail SET jumlah_aktual='$jumlah_aktual', remark='$_POST[remark]' WHERE id='$_POST[id]'");

		header("location: opname-view-$_POST[opname_id]");
	}

	else if($act=='delete_material'){
		mysqli_query($conn,"UPDATE opname_detail SET deleted_at='$waktu_sekarang' WHERE id='$_POST[id]'");
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

			if($_POST['next_status_id']=='365'){
				//HEADER opname
				$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname WHERE id='$_POST[opname_id]' AND deleted_at IS NULL"));
				$number = $d['nomor'];
				$created_master_cabang_id=$d['created_master_cabang_id'];

				//UPDATE BAHWA TELAH JADI DIopnameKAN
				mysqli_query($conn,"UPDATE opname_sn a INNER JOIN opname_detail b ON a.opname_detail_id = b.id SET a.status='2' WHERE b.opname_id='$_POST[opname_id]'");

				//UPDATE MATERIAL SN STATUS DAN JUGA KONDISI STOK GUDANG
				$data = mysqli_query($conn,"SELECT a.* FROM opname_detail a WHERE a.opname_id='$_POST[opname_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 
					//UPDATE STOK GUDANG PER KONDISI
					$jumlah_berubah = $r['jumlah_aktual']-$r['jumlah_tercatat'];
					if($r['stok_kondisi_id']!='0'){
						mysqli_query($conn,"UPDATE stok_kondisi SET jumlah='$r[jumlah_aktual]' WHERE id='$r[stok_kondisi_id]'");

						//CATAT DI LOG STOK
						$st=mysqli_fetch_array(mysqli_query($conn,"SELECT a.id, a.jumlah FROM stok a INNER JOIN stok_kondisi b ON a.id=b.stok_id WHERE b.id='$r[stok_kondisi_id]' AND a.deleted_at IS NULL"));
						$balance_current = $st['jumlah']+$jumlah_berubah;

						if($jumlah_berubah>0){
							$sql="INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name,  act_type_id, act_table_id, transaction_number) VALUES ('$st[id]', '$jumlah_berubah', '0', '$balance_current', '$waktu_sekarang', 'Stok Opname', '$r[id]', '110', 'opname_detail', '3', '$_POST[opname_id]', '$number')";
						}
						else{
							$sql="INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$st[id]', '0', '$jumlah_berubah', '$balance_current', '$waktu_sekarang', 'Stok Opname', '$r[id]', '110', 'opname_detail', '3', '$_POST[opname_id]', '$number')";
						}

						mysqli_query($conn,$sql);

						//UPDATE STOK DI GUDANG ASAL
						mysqli_query($conn,"UPDATE stok SET jumlah='$balance_current' WHERE id='$st[id]'");
					}
					else{
						$st = mysqli_fetch_array(mysqli_query($conn,"SELECT id, jumlah FROM stok WHERE master_cabang_id='$created_master_cabang_id' AND master_gudang_id='$r[master_gudang_id]' AND master_material_id='$r[master_material_id]'"));
						if(isset($st['id'])!=''){
							mysqli_query($conn,"INSERT INTO stok_kondisi (stok_id, master_kondisi_id, created_at, updated_at, jumlah) VALUES ('$st[id]', '$r[master_kondisi_id]', '$waktu_sekarang', '$waktu_sekarang', '$r[jumlah_aktual]')");

							$stok_kondisi_id = mysqli_insert_id($conn);

							$balance_current = $st['jumlah']+$jumlah_berubah;
							if($jumlah_berubah>0){
								$sql="INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name,  act_type_id, act_table_id, transaction_number) VALUES ('$st[id]', '$jumlah_berubah', '0', '$balance_current', '$waktu_sekarang', 'Stok Opname', '$r[id]', '110', 'opname_detail', '3', '$_POST[opname_id]', '$number')";
							}
							else{
								$sql="INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$st[id]', '0', '$jumlah_berubah', '$balance_current', '$waktu_sekarang', 'Stok Opname', '$r[id]', '110', 'opname_detail', '3', '$_POST[opname_id]', '$number')";
							}

							mysqli_query($conn,$sql);

							//UPDATE STOK DI GUDANG ASAL
							mysqli_query($conn,"UPDATE stok SET jumlah='$balance_current' WHERE id='$st[id]'");

						}
						else{
							mysqli_query($conn,"INSERT INTO stok (master_cabang_id, master_gudang_id, master_material_id, jumlah, created_at, updated_at) VALUES ('$created_master_cabang_id', '$r[master_gudang_id]', '$r[master_material_id]', '$r[jumlah_aktual]', '$waktu_sekarang', '$waktu_sekarang')");

							$stok_id = mysqli_insert_id($conn);

							$sql="INSERT INTO stok_log (stok_id, masuk, keluar, balance, created_at, remark, table_id, status_id, table_name, act_type_id, act_table_id, transaction_number) VALUES ('$stok_id', '$r[jumlah_aktual]', '0', '$r[jumlah_aktual]', '$waktu_sekarang', 'Stok Opname $number', '$r[id]', '110', 'opname_detail', '3', '$_POST[opname_id]', '$number')";
							
							mysqli_query($conn, $sql);

							mysqli_query($conn,"INSERT INTO stok_kondisi (stok_id, master_kondisi_id, created_at, updated_at, jumlah) VALUES ('$stok_id', '$r[master_kondisi_id]', '$waktu_sekarang', '$waktu_sekarang', '$r[jumlah_aktual]')");
						}
					}
				}

				//UPDATE POSISI MATERIAL SN
				$data = mysqli_query($conn,"SELECT a.*, b.serial_number, b.material_sn_id, b.status, b.harga, b.material_sn_status_id, b.harga, b.master_klasifikasi_material_id FROM opname_detail a INNER JOIN opname_sn b ON a.id=b.opname_detail_id WHERE a.opname_id='$_POST[opname_id]' AND a.deleted_at IS NULL");
				while($r=mysqli_fetch_array($data)){ 
					if($r['material_sn_id']!='0'){
						//UPDATE INFO MATERIAL SN
						mysqli_query($conn,"UPDATE material_sn SET status_id='$r[material_sn_status_id]' WHERE id='$r[material_sn_id]'");

						$material_sn_id = $r['material_sn_status_id'];
					}
					else{
						mysqli_query($conn,"INSERT INTO material_sn (master_material_id, status_id, keterangan, serial_number, master_gudang_id, created_at, harga, master_kategori_material_id, master_kondisi_id, master_klasifikasi_material_id) VALUES ('$r[master_material_id]', '$r[material_sn_status_id]', '$r[remark]', '$r[serial_number]', '$r[master_gudang_id]', '$waktu_sekarang', '$r[harga]', '$r[master_kategori_material_id]', '$r[master_kondisi_id]', '$r[master_klasifikasi_material_id]')");

						$material_sn_id = mysqli_insert_id($conn);
					}

					mysqli_query($conn,"UPDATE opname_sn SET status='2', material_sn_id='$material_sn_id' WHERE id='$r[id]'");

					//CATAT DI LOG MATERIAL SN
					mysqli_query($conn,"INSERT INTO material_sn_log (material_sn_id, status_id, created_at, remark, act_type_id, act_table_id, transaction_number) VALUES ('$material_sn_id', '$r[material_sn_status_id]', '$waktu_sekarang', 'Stok Opname dengan keterangan $r[remark]', '3', '$_POST[opname_id]', '$number')");
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

			mysqli_query($conn,"UPDATE opname_detail SET deleted_at='$waktu_sekarang' WHERE opname_id='$_POST[opname_id]'");

			mysqli_commit($conn);
		}
		catch (Exception $e) {
			// Tangkap kesalahan dan lakukan rollback
			mysqli_rollback($conn);
		}
		header("location: opname-view-$_POST[opname_id]");
	}

	else if($act=='table_sn_material'){
		include "sn/table.php";
	}
	else if($act=='sn_tambah'){
		include "sn/tambah.php";
	}

	else if($act=='sn_input'){
		//CEK APAKAH SERIAL NUMBER ITU MEMANG ADA DI GUDANG ITU
		$cek = mysqli_fetch_array(mysqli_query($conn,"SELECT a.* FROM material_sn a 
		INNER JOIN opname_detail b ON a.master_gudang_id=b.master_gudang_id AND b.deleted_at IS NULL AND a.master_material_id=b.master_material_id
		WHERE a.serial_number='$_POST[serial_number]' AND b.id='$_POST[opname_detail_id]' AND a.status_id IN (500,501)"));

		$harga = str_replace(".","",$_POST['harga']);

		if(isset($cek['id'])!=''){
			//CEK APAKAH SERIAL NUMBER INI SUDAH ADA DI DALAM LIST opname SN ATAU BELUM DIKECUALIKAN UNTUK '0'
			// $d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE opname_detail_id='$_POST[opname_detail_id]' AND serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d1=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d2=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d3=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM po_terima_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d4=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM mutasi_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			if(isset($d1['id'])=='' AND isset($d2['id'])=='' AND isset($d3['id'])=='' AND isset($d4['id'])==''){
				mysqli_query($conn,"INSERT INTO opname_sn (opname_detail_id, serial_number, created_at, material_sn_id, harga, status, material_sn_status_id, master_klasifikasi_material_id) VALUES ('$_POST[opname_detail_id]', '$_POST[serial_number]', '$waktu_sekarang', '$cek[id]', '$harga', '1', '$cek[status_id]', '$_POST[master_klasifikasi_material_id]')");

				// header("location: opname-sn-$_POST[opname_detail_id]");
			}
			else{
				?>
				<div class="alert alert-danger">
					Serial Number tidak dapat diinput. Hal ini bisa disebabkan oleh beberapa kemungkinan berikut:
					<ol>
						<li>Serial Number sedang dalam proses stok opname</li>
						<li>Serial Number sedang dalam proses penerimaan</li>
						<li>Serial Number sudah pernah digunakan</li>
						<li>Serial Number sudah diinput oleh cabang lainnya.</li>
						<li>Serial Number sedang dalam proses mutasi</li>
					</ol>
				</div>
				<?php
			}
		}
		else{
			$d1=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM guna_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d2=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d3=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM po_terima_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d4=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM mutasi_sn WHERE serial_number='$_POST[serial_number]' AND serial_number!='0' AND status='1'"));
			$d5=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM material_sn WHERE serial_number='$_POST[serial_number]'"));
			if(isset($d1['id'])=='' AND isset($d2['id'])=='' AND isset($d3['id'])=='' AND isset($d4['id'])=='' AND isset($d5['id'])==''){
				mysqli_query($conn,"INSERT INTO opname_sn (opname_detail_id, serial_number, created_at, harga, status, material_sn_id, material_sn_status_id, remark, master_klasifikasi_material_id) VALUES ('$_POST[opname_detail_id]', '$_POST[serial_number]', '$waktu_sekarang', '$harga', '1', '0', '500', '$_POST[remark]', '$_POST[master_klasifikasi_material_id]')");
			}
			else{
				?>
				<div class="alert alert-danger">
					Serial Number tidak dapat diinput. Hal ini bisa disebabkan oleh beberapa kemungkinan berikut:
					<ol>
						<li>Serial Number sedang dalam proses stok opname</li>
						<li>Serial Number sedang dalam proses penerimaan</li>
						<li>Serial Number sudah pernah digunakan</li>
						<li>Serial Number sudah diinput oleh cabang lainnya.</li>
						<li>Serial Number sedang dalam proses mutasi</li>
					</ol>
				</div>
				<?php
			}
			
			// header("location: opname-sn-$_POST[opname_detail_id]");
		}

	}

	else if($act=='sn_edit'){
		include "sn/edit.php";
	}

	else if($act=='sn_update'){
		mysqli_query($conn,"UPDATE opname_sn SET material_sn_status_id='$_POST[material_sn_status_id]', remark='$_POST[remark]' WHERE id='$_POST[id]'");
	}

	else if($act=='sn_delete'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_sn WHERE id='$_POST[id]'"));

		mysqli_query($conn,"DELETE FROM opname_sn WHERE id='$_POST[id]'");

		// header("location: opname-sn-$d[opname_detail_id]");
	}


	mysqli_close($conn);
	
}
?>