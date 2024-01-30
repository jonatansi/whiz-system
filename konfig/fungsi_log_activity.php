<?php
function log_activity($conn_api, $id_user, $id_module, $table_name, $id_table, $aksi, $id_login, $created_at){
    
    mysqli_query($conn_api,"INSERT INTO pegawai_log_activity (pegawai_id,  modul, origin_table, table_primary_id, aksi, id_session, created_at) VALUES ('$id_user', '$id_module', '$table_name', '$id_table', '$aksi', '$id_login', '$created_at')");

}
?>