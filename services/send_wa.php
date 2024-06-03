<?php
function wamsg( $nomor, $pesan){
    $url_real = "https://wa-gateway.doiscode.com/send-message";

    $header = [
        'Content-type: application/json'
    ]; 
    $message_body = [
        "number" => "$nomor",
        "token_key" => "OhEwafRxc3PSjCDMCDbt",
        "message" => "$pesan"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_real);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message_body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $result = curl_exec($ch);  

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);

        echo $error_msg;
    }

    // echo $result;

    curl_close($ch);
}
?>