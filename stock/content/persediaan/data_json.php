<?php
$columns = array( 
    0 =>'a.id', 
    1=> 'e.nama',
    2 =>'b.merk_type',
    3=> 'a.jumlah',
    4=> 'd.nama',
    5=> 'c.nama',
);

$pencarian = array('a.id', 'e.nama', 'b.merk_type', 'a.jumlah', 'd.nama', 'c.nama');


$query = "SELECT a.id, a.jumlah, b.merk_type, c.nama AS nama_gudang, d.nama AS nama_satuan, e.nama AS nama_kategori FROM stok a 
INNER JOIN master_material b ON a.master_material_id=b.id
INNER JOIN master_gudang c ON a.master_gudang_id=c.id
INNER JOIN master_satuan d ON b.master_satuan_id=d.id
INNER JOIN master_kategori_material e ON b.master_kategori_material_id=e.id
WHERE a.master_cabang_id='$_POST[master_cabang_id]'";

if($_POST['master_gudang_id']!='0'){
    $query.=" AND a.master_gudang_id='$_POST[master_gudang_id]'";
}

if($_POST['master_kategori_material_id']!='0'){
    $query.=" AND b.master_kategori_material_id='$_POST[master_kategori_material_id]'";
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
    $nestedData=array(); 
    $nestedData[] = $no;
    $nestedData[] = $row['nama_kategori'];
    $nestedData[] = $row["merk_type"];
    $nestedData[] = "<a href='persediaan-view-$row[id]' target='_blank' class='text-primary'>$row[jumlah]</a>";
    $nestedData[] = $row['nama_satuan'];
    $nestedData[] = $row['nama_gudang'];
                    
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