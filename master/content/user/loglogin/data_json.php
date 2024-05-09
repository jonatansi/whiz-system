<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../../konfig/koneksi.php";
	include "../../../../konfig/library.php";
    include "../../../../konfig/fungsi_tanggal.php";
	
    include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
    
    $columns = array( 
        0 =>'a.id', 
        1=> 'a.login_at',
        2 =>'b.email',
        3=> 'b.nama',
        4=> 'a.ip_address',
        5=> 'a.browser',
        6=> 'a.id_session',
        7=> 'a.logout_at',
    );

    $pencarian = array('a.id', 'a.login_at', 'b.email', 'b.nama', 'a.ip_address', 'a.browser', 'a.id_session', 'a.logout_at');

    $d = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(a.id) AS jumlah  FROM pegawai_log_login a LEFT JOIN pegawai b ON a.pegawai_id=b.id WHERE a.login_at BETWEEN '$_POST[tanggal_awal] 00:00:00' AND '$_POST[tanggal_akhir] 23:59:59' AND b.deleted_at IS NULL"));
    $totalData = $d['jumlah'];

    $totalFiltered = $totalData; 

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    $query = "SELECT a.*, b.email, b.nama FROM pegawai_log_login a LEFT JOIN pegawai b ON a.pegawai_id=b.id WHERE a.login_at BETWEEN '$_POST[tanggal_awal] 00:00:00' AND '$_POST[tanggal_akhir] 23:59:59' AND b.deleted_at IS NULL";

    if(!empty($_POST['search']['value'])){ 
        $search = $_POST['search']['value']; 

        $query.=" AND (";		
        for($i=0;$i<count($pencarian);$i++){
            $query.="$pencarian[$i] LIKE '%$search%'";
            if($i<(count($pencarian)-1)){
                $query.=" OR ";
            }
        }
        $query.=")";
    }


    for($i=0;$i<count($pencarian);$i++)
    {
        // echo $_POST['columns'][$i]['search']['value'].'<br>';
        if( !empty($_POST['columns'][$i]['search']['value'])){
            $query.=" AND ". $pencarian[$i] ." LIKE '%".$_POST['columns'][$i]['search']['value']."%' ";
        }
    }

    if($limit!='-1'){
        $query_all=$query." ORDER BY $order $dir LIMIT $limit OFFSET $start";
    }
    else{
        $query_all=$query." ORDER BY $order $dir";
    }

    // echo $query_all;
    $sql_data = mysqli_query($conn, $query_all); // Query untuk data yang akan di tampilkan
        
    $sql_filter = mysqli_query($conn, $query);

    $totalFiltered = mysqli_num_rows($sql_filter); // Hitung data yg ada pada query $sql_filter
    
    $data = array();
    $no=$start+1;

    while( $row=mysqli_fetch_array($sql_data)) {  // preparing an array
        $nestedData=array(); 
        $nestedData[] = $no;
        $nestedData[] = WaktuIndo($row['login_at']);
        $nestedData[] = $row['email'];
        $nestedData[] = $row["nama"];

        $nestedData[] = $row['ip_address'];
        $nestedData[] = $row['browser'];
        $nestedData[] = $row['id_session'];
        $nestedData[] = $row['logout_at'];
                        
        //$nestedData[] = $sql;
        $data[] = $nestedData;
        $no++;
    }
    
    
    
    $json_data = array(
        "draw"            => intval($_POST['draw']  ),   //   for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data"            => $data   // total data array
    );
    
    echo json_encode($json_data);  // send data as json formal
	mysqli_close($conn);
}
?>