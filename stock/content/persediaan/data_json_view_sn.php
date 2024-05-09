<?php
$columns = array( 
    0 =>'a.created_at', 
    1=> 'a.serial_number',
    2 =>'b.nama',
    3=> 'c.nama',
    4=> 'a.harga'
);

$pencarian = array('a.created_at', 'a.serial_number', 'b.nama', 'c.nama', 'a.harga');

$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM stok WHERE id='$_POST[stok_id]'"));

$query = "SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_kondisi
FROM material_sn a 
LEFT JOIN master_status b ON a.status_id=b.id
LEFT JOIN master_kondisi c ON a.master_kondisi_id=c.id
WHERE a.master_gudang_id='$d[master_gudang_id]' AND a.master_material_id='$d[master_material_id]'";

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
    $status="<span class='badge bg-$row[warna_status]'>$row[nama_status]</span>";
    $nestedData=array(); 
    $nestedData[] = WaktuIndo($row['created_at']);
    $nestedData[] = $row["serial_number"];
    $nestedData[] = $status;
    $nestedData[] = $row['nama_kondisi'];
    $nestedData[] = formatAngka($row['harga']);
                    
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