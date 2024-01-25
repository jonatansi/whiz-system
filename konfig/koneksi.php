<?php
$conn_api = mysqli_connect("127.0.0.1","root","","other_whiz_temp");
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>