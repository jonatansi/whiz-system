<?php
echo "<option value=''>Pilih Kecamatan</option>";
$tampil=mysqli_query($conn,"SELECT * FROM lok_kecamatan WHERE lok_kabupaten_id='$_POST[id_kabupaten]'");
while($r=mysqli_fetch_array($tampil)){
    if(isset($_POST['id_kecamatan_recent'])!=''){
        if($r['id']==$_POST['id_kecamatan_recent']){
            echo"<option value='$r[id]' selected>$r[nama]</option>";
        }
        else{
            echo"<option value='$r[id]'>$r[nama]</option>";
        }
    }
    else{
        echo"<option value='$r[id]'>$r[nama]</option>";
    }
}
?>