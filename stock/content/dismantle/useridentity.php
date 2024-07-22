<?php
$tampil=mysqli_query($conn,"SELECT a.master_guna_kategori_id, a.user_identity FROM material_sn a INNER JOIN master_gudang b ON a.master_gudang_id=b.id AND b.deleted_at IS NULL WHERE b.master_cabang_id='$_SESSION[master_cabang_id]' AND a.master_klasifikasi_material_id='1' AND a.master_guna_kategori_id IS NOT NULL AND a.master_guna_id='$_POST[master_guna_id]' GROUP BY master_guna_kategori_id, user_identity");
while($r=mysqli_fetch_array($tampil)){
    echo "<option value='$r[master_guna_kategori_id]'>$r[user_identity]</option>";
}
?>