<?php
$columns = array( 
    0 =>'a.id', 
    1=> 'a.nomor',
    2 =>'a.tanggal',
    3=> 'b.nama',
    4=> 'c.nama',
    5=> 'd.nama',
);

$pencarian = array('a.id', 'a.nomor', 'a.tanggal', 'b.nama', 'c.nama', 'd.nama');

$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));

$query = "SELECT a.*, b.id AS po_id, b.nomor AS nomor_po, c.nama AS nama_cabang, d.nama AS nama_vendor, e.nama AS nama_status, e.warna AS warna_status
FROM po_terima a
LEFT JOIN po b ON a.po_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_cabang c ON b.request_master_cabang_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_vendor d ON b.master_vendor_id=d.id AND d.deleted_at IS NULL
LEFT JOIN master_status e ON a.status_id=e.id
WHERE a.deleted_at IS NULL AND a.tanggal BETWEEN '$_POST[tanggal_awal]' AND '$_POST[tanggal_akhir]'";

if($pegawai['master_cabang_id']!='1'){
    $query.=" AND b.request_master_cabang_id='$pegawai[master_cabang_id]'";
}

if($_POST['vendor_id']!='0'){
    $query.=" AND b.master_vendor_id='$_POST[vendor_id]'";
}

// echo $query;

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
    $nestedData[] = "<a href='terimapo-view-$row[id]' class='text-primary'>$row[nomor]</a>";
    $nestedData[] = "<a href='po-view-$row[po_id]' target='_blank' class='text-primary'>$row[nomor_po]</a>";
    $nestedData[] = DateIndo($row["tanggal"]);
    $nestedData[] = $row['nama_cabang'];
    $nestedData[] = $row['nama_vendor'];
    $nestedData[] = $status;
    // $nestedData[] = formatAngka($row['total_item']);
    // $nestedData[] = formatAngka($row['total_harga']);
                    
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