<?php
$columns = array( 
    0 =>'a.id', 
    1=> 'a.serial_number',
    2=> 'c.nama',
    3=> 'f.merk_type',
    4=> 'd.nama',
    5=> 'h.nama',
    6=> 'b.nama',
    7=> 'g.nama',
    8=> 'a.harga',
    9=> 'e.nama'
);

$pencarian = array('a.id', 'a.serial_number', 'c.nama', 'f.merk_type', 'd.nama', 'h.nama', 'b.nama', 'g.nama', 'a.harga', 'e.nama');

$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));

$query = "SELECT a.*, b.nama AS nama_gudang, c.nama AS nama_kategori, d.nama AS nama_kondisi, e.nama AS nama_status, e.warna AS warna_status, f.merk_type, g.nama AS nama_cabang, h.nama AS nama_klasifikasi FROM material_sn a
LEFT JOIN master_gudang b ON a.master_gudang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_kategori_material c on a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_kondisi d ON a.master_kondisi_id=d.id AND d.deleted_at IS NULL
LEFT JOIN master_material f ON a.master_material_id=f.id AND f.deleted_at IS NULL
LEFT JOIN master_cabang g ON b.master_cabang_id=g.id AND g.deleted_at IS NULL
LEFT JOIN master_status e ON a.status_id=e.id 
LEFT JOIN master_klasifikasi_material h ON a.master_klasifikasi_material_id=h.id
WHERE b.deleted_at IS NULL";

if($_SESSION['master_cabang_id']!='1'){
    $query.=" AND g.id='$_SESSION[master_cabang_id]' ";
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
    $nestedData[] = "<a href='material-view-$row[id]' target='_blank' class='text-primary'>$row[serial_number]</a>";
    $nestedData[] = $row['nama_kategori'];
    $nestedData[] = $row['merk_type'];
    $nestedData[] = $row['nama_kondisi'];
    $nestedData[] = $row['nama_klasifikasi'];
    $nestedData[] = $row['nama_gudang'];
    $nestedData[] = $row['nama_cabang'];
    $nestedData[] = formatAngka($row['harga']);
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