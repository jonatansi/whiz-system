<?php
//error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "konfig/koneksi.php";
include "konfig/library.php";
include "konfig/myencrypt.php";
include "konfig/fungsi_get_ip.php";

include "services/send_discord.php";
include "services/get_error.php";
include "services/send_wa.php";
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
	// username and password sent from Form
	$username = mysqli_escape_string($conn, $_POST['username']);
	//Here converting passsword into MD5 encryption.
	$password = mysqli_escape_string($conn, $_POST['password']);
	$token = $_POST['token'];
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => '6LdQhl0pAAAAAEyHFghQwbzq6Zm-220gO-fyjDZ_', 'response' => $token)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	$arrResponse = json_decode($response, true);
	
	// verify the response 
	if($arrResponse["success"] == '1'  && $arrResponse["score"] >= 0.5) {
        $password=encrypt($password);

		$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pegawai WHERE username='$username' AND password='$password' AND deleted_at IS NULL"));
		if (isset($row['id']) != '') {
            include "timeout.php";
            timer();

            session_regenerate_id();
            
            $sid = session_id();
            $_SESSION['login_user'] = $row['id'];
            $_SESSION['id_session'] = $sid;
            $_SESSION['username']   = $_POST['username'];
            $_SESSION['login_system'] = 1;
			$_SESSION['master_cabang_id'] = $row['master_cabang_id'];

            mysqli_query($conn,"UPDATE pegawai SET is_login='Y', last_login_at='$waktu_sekarang' WHERE id='$row[id]'");

			$user_ip = getUserIP();

			$browser = getBrowser();

			mysqli_query($conn,"INSERT INTO pegawai_log_login (pegawai_id, login_at, id_session, ip_address, browser) VALUES ('$_SESSION[login_user]', '$waktu_sekarang', '$_SESSION[id_session]', '$user_ip', '$browser')");

            echo "ok";
        }
		else{
			echo "wrong";
		}
	} else {	
		echo "-1";
	}
}
?>