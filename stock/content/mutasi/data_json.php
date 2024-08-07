<?php
$columns = array( 
    0 =>'a.id', 
    1=> 'a.created_at',
    2=> 'a.nomor',
    3=> 'a.tanggal',
    4=> 'b.nama',
    5=> 'c.nama',
    6=> 'total_item',
    7=> 'total_sn',
    8=> 'd.nama'
);

$pencarian = array('a.id', 'a.created_at', 'a.nomor', 'a.tanggal', 'b.nama', 'c.nama', 'total_item', 'total_sn', 'd.nama');

$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));

$query = "SELECT a.*,  b.nama AS nama_cabang, c.nama AS nama_gudang, d.nama AS nama_status, d.warna AS warna_status, COALESCE(total_item_query.total_item, 0) AS total_item, COALESCE(total_sn_query.total_sn, 0) AS total_sn
FROM mutasi a 
LEFT JOIN master_cabang b ON a.created_master_cabang_id = b.id AND b.deleted_at IS NULL
LEFT JOIN master_gudang c ON a.master_gudang_tujuan_id = c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id = d.id
LEFT JOIN (SELECT mutasi_id, SUM(jumlah) AS total_item FROM mutasi_detail WHERE deleted_at IS NULL AND mutasi_id IS NOT NULL GROUP BY mutasi_id) AS total_item_query 
ON a.id = total_item_query.mutasi_id
LEFT JOIN (SELECT g.mutasi_id, COUNT(f.id) AS total_sn FROM mutasi_sn f INNER JOIN mutasi_detail g ON f.mutasi_detail_id = g.id AND g.deleted_at IS NULL GROUP BY g.mutasi_id) AS total_sn_query ON a.id = total_sn_query.mutasi_id
WHERE a.deleted_at IS NULL AND a.tanggal BETWEEN '$_POST[tanggal_awal]' AND '$_POST[tanggal_akhir]'";

if($_POST['status_id']!='0'){
    $query.=" AND a.status_id='$_POST[status_id]'";
}

if($_SESSION['master_cabang_id']!='1'){
    $query.=" AND a.created_master_cabang_id='$_SESSION[master_cabang_id]'";
}

$totalData = mysqli_num_rows(mysqli_query($conn, $query));

$totalFiltered = $totalData; 

$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$_POST['order']['0']['column']];
$dir = $_POST['order']['0']['dir'];

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
    $status = "<span class='badge bg-$row[warna_status]'>$row[nama_status]</span>";
    $nestedData=array(); 
    $nestedData[] = $no;
    $nestedData[] = WaktuIndo($row['created_at']);
    $nestedData[] = "<a href='mutasi-view-$row[id]' class='text-primary'>$row[nomor]</a>";
    $nestedData[] = DateIndo($row["tanggal"]);
    $nestedData[] = $row['nama_cabang'];
    $nestedData[] = $row['nama_gudang'];
    $nestedData[] = formatAngka($row['total_item']);
    $nestedData[] = formatAngka($row['total_sn']);
    $nestedData[] = $status;
                    
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
?>