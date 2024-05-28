<?php
$columns = array( 
    0 =>'created_at', 
    1=> 'remark',
    2 =>'masuk',
    3=> 'keluar',
    4=> 'balance'
);

$pencarian = array('created_at', 'remark', 'masuk', 'keluar', 'balance');

$tanggal_awal = "$_POST[tahun]-$_POST[id_bulan]-01";
$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));

$query = "SELECT * FROM stok_log WHERE stok_id='$_POST[stok_id]' AND created_at BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'";

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
    $transaksi="";
    if($row['act_type_id']=='1'){
        $transaksi = "<a href='terimapo-view-$row[act_table_id]'>$row[transaction_number]</a>";
    }
    else if($row['act_type_id']=='2'){
        $transaksi = "<a href='mutasi-view-$row[act_table_id]'>$row[transaction_number]</a>";
    }
    else if($row['act_type_id']=='3'){
        $transaksi = "<a href='opname-view-$row[act_table_id]'>$row[transaction_number]</a>";
    }
    else if($row['act_type_id']=='4'){
        $transaksi = "<a href='dismantle-view-$row[act_table_id]'>$row[transaction_number]</a>";
    }
    else if($row['act_type_id']=='5'){
        $transaksi = "<a href='guna-view-$row[act_table_id]'>$row[transaction_number]</a>";
    }
    
    $nestedData=array(); 
    $nestedData[] = WaktuIndo($row['created_at']);
    $nestedData[] = $row["remark"];
    $nestedData[] = $transaksi;
    $nestedData[] = formatAngka($row['masuk']);
    $nestedData[] = formatAngka($row['keluar']);
    $nestedData[] = formatAngka($row['balance']);
                    
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