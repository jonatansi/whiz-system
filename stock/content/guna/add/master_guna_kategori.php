<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_guna WHERE id='$_POST[master_guna_id]'"));
if($d['type']=='1'){
    $sql="SELECT id, kode, nama FROM master_customer WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND deleted_at IS NULL";
}
else if($d['type']=='2'){
    $sql="SELECT id, nama FROM master_guna_kategori WHERE master_guna_id='$_POST[master_guna_id]' AND deleted_at IS NULL";
}
$tampil=mysqli_query($conn, $sql);
while($r=mysqli_fetch_array($tampil)){
    $nama = $r['nama'];
    if(isset($r['kode'])!=''){
        $nama = "$r[kode] - $r[nama]";
    }
    echo "<option value='$r[id]'>$nama</option>";
}
?>