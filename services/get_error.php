<?php
// session_start();

//Fungsi untuk mendapatkan error dan kemudian dikirim ke discord
function customError($errno, $errstr, $error_file, $error_line) {
    $login="Belum login";
    // if(isset($_SESSION['login_user_sibisa_desktop'])){
    //     $login="Masyarakat : $_SESSION[login_user_sibisa_desktop]";
    // }

    // if(isset($_SESSION['login_user'])){
    //     $login="Pegawi : $_SESSION[login_user]";
    // }

    $message_error = "Error [$errno] $errstr - $error_file:$error_line \n";

    $webhook = "https://discord.com/api/webhooks/1234899948752080968/6JzN1_pFf6F1MpMORapovn_CCfcMWhKOul-5baD_MR1zXTkibbHZxGuEwSQw38q75vbO"; 
    
    $message = json_encode(
        ["content" => $message_error],JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE 
    );
    discordmsg($message, $webhook);
}
//set error handler
set_error_handler("customError");
?>