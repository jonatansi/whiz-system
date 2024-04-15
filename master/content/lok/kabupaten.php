<?php
echo "<option value=''>Pilih Kabupaten</option>";
$tampil=mysqli_query($conn,"SELECT * FROM lok_kabupaten WHERE lok_provinsi_id='$_POST[id_propinsi]'");
while($r=mysqli_fetch_array($tampil)){
    if($r['id']==$_POST['id_kabupaten_recent']){
        echo"<option value='$r[id]' selected>$r[nama]</option>";
    }
    else{
        echo"<option value='$r[id]'>$r[nama]</option>";
    }
}
?>