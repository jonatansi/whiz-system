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
	include "../../../konfig/fungsi_tanggal.php";
    include "../../../konfig/base_url.php";
    include "../../../konfig/myencrypt.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
	$act=$_GET['act'];
    if($act=='update'){
        $pass_baru = encrypt($_POST['pass_baru']);

        $sql="UPDATE pegawai SET password='$pass_baru' WHERE id='$_SESSION[login_user]' AND deleted_at IS NULL";
    
        mysqli_query($conn,$sql);

        header("location: profile");
	}

	elseif($act=='cekpass'){
        
        $sql="SELECT password, id FROM pegawai WHERE id='$_SESSION[login_user]' AND deleted_at IS NULL";
       
        $d=mysqli_fetch_array(mysqli_query($conn,$sql));
        $password = $d['password'];
        $pass_lama = encrypt($_POST['pass_lama']);
        if ($pass_lama == $password){
            echo $d['id'];
        }
	}
	mysqli_close($conn);
	
}
?>