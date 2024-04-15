<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";

	$act=$_GET['act'];
    
	if($act=='material'){
        $tampil=mysqli_query($conn,"SELECT a.id, a.merk_type, b.nama AS nama_satuan FROM master_material a INNER JOIN master_satuan b ON a.master_satuan_id=b.id AND b.deleted_at IS NULL WHERE a.deleted_at IS NULL AND a.master_kategori_material_id='$_POST[kategori_material_id]' ORDER BY a.merk_type");
        while($r=mysqli_fetch_array($tampil)){
            echo "<option value='$r[id]'>$r[merk_type] ($r[nama_satuan])</option>";
        }
    }

	else if($act=='satuan_material'){
		$tampil=mysqli_query($conn,"SELECT b.* FROM master_material a INNER JOIN master_satuan b ON a.master_satuan_id=b.id AND b.deleted_at IS NULL WHERE a.deleted_at IS NULL AND a.id='$_POST[material_id]'");
        while($r=mysqli_fetch_array($tampil)){
            echo "<option value='$r[id]'>$r[nama]</option>";
        }
	}

	mysqli_close($conn);
	
}
?>