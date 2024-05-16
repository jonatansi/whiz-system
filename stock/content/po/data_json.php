<?php
$columns = array( 
    0 =>'sub.id', 
    1=> 'sub.created_at',
    2=> 'sub.nomor',
    3 =>'sub.tanggal',
    4=> 'b.nama',
    5=> 'c.nama',
    6=> 'd.nama',
    7=> 'sub.total_item',
    8=> 'sub.total_harga'
);

$pencarian = array('sub.id', 'sub.created_at', 'sub.nomor', 'sub.tanggal', 'b.nama', 'c.nama', 'd.nama', 'sub.total_item', 'sub.total_harga');

$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));

$query = "SELECT sub.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status FROM (
SELECT 
    a.*, 
    COALESCE(SUM(e.jumlah), 0) AS total_item, 
    COALESCE(SUM(e.jumlah * e.harga), 0) AS total_harga
FROM po a
LEFT JOIN po_detail e ON a.id = e.po_id AND e.deleted_at IS NULL
WHERE a.deleted_at IS NULL
GROUP BY a.id
) sub
LEFT JOIN master_cabang b ON sub.request_master_cabang_id = b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON sub.master_vendor_id = c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON sub.status_id = d.id WHERE sub.tanggal BETWEEN '$_POST[tanggal_awal]' AND '$_POST[tanggal_akhir]'";

if($pegawai['master_cabang_id']!='1'){
    $query.=" AND sub.request_master_cabang_id='$pegawai[master_cabang_id]'";
}

if($_POST['status_id']!='0'){
    $query.=" AND sub.status_id='$_POST[status_id]'";
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
    $nestedData[] = "<a href='po-view-$row[id]' class='text-primary'>$row[nomor]</a>";
    $nestedData[] = DateIndo($row["tanggal"]);
    $nestedData[] = $row['nama_cabang'];
    $nestedData[] = $row['nama_vendor'];
    $nestedData[] = $status;
    $nestedData[] = formatAngka($row['total_item']);
    $nestedData[] = formatAngka($row['total_harga']);
                    
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