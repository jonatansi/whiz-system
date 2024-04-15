<?php
$tampil=mysqli_query($conn,"SELECT * FROM lok_provinsi");
while($r=mysqli_fetch_array($tampil)){
    echo"<option value='$r[id]'>$r[nama]</option>";
}
?>