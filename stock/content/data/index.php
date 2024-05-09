<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";
	include "../../../konfig/fungsi_angka.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
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

	else if($act=='material_gudang'){
        $tampil=mysqli_query($conn,"SELECT b.merk_type, b.id, c.nama AS nama_satuan FROM stok a 
		INNER JOIN master_material b ON a.master_material_id=b.id 
		INNER JOIN master_satuan c ON b.master_satuan_id=c.id 
		WHERE a.master_gudang_id='$_POST[master_gudang_asal_id]' AND a.deleted_at IS NULL");
        while($r=mysqli_fetch_array($tampil)){
            echo "<option value='$r[id]'>$r[merk_type] ($r[nama_satuan])</option>";
        }
    }

	else if($act=='stok_gudang'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT id FROM stok WHERE master_material_id='$_POST[material_id]' AND master_gudang_id='$_POST[gudang_id]'"));
		if(isset($d['id'])!=''){
		?>
		<table>
			<?php
			$total=0;
			$tampil=mysqli_query($conn,"SELECT a.nama, b.* FROM master_kondisi a 
			LEFT JOIN stok_kondisi b ON a.id=b.master_kondisi_id AND b.stok_id='$d[id]' ORDER BY a.nama");
			while($r=mysqli_fetch_array($tampil)){
				?>
				<tr>
					<td width="150px"><?php echo $r['nama'];?></td>
					<td width="10px">:</td>
					<td class="fw-bold text-end" width="50px"><?php echo formatAngka($r['jumlah']);?></td>
				</tr>
				<?php
				$total+=$r['jumlah'];
			}
			?>
			<tr>
				<td style="border-top:1px solid #000;">Total</td>
				<td style="border-top:1px solid #000;">:</td>
				<td  style="border-top:1px solid #000;" class="fw-bold text-end"><?php echo formatAngka($total);?></td>
			</tr>
		</table>
		<?php
		}
		else{
			?>
			<div class="alert alert-warning text-dark">Tidak ada stok barang</div>
			<?php
		}
	}

	mysqli_close($conn);
	
}
?>