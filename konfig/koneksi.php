<?php
$conn = mysqli_connect("103.167.237.229","uv-tel","JO2205ys12,.","staging");
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>