<?php
$tampil=mysqli_query($conn,"SELECT * FROM lok_kelurahan WHERE lok_kecamatan_id='$_POST[id_kecamatan]'");
while($r=mysqli_fetch_array($tampil)){
    if($r['id']==$_POST['id_kelurahan_recent']){
        echo"<option value='$r[id]' selected>$r[nama]</option>";
    }
    else{
        echo"<option value='$r[id]'>$r[nama]</option>";
    }
}
?>