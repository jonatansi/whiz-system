<?php
$sql="SELECT a.id, a.jumlah, b.merk_type, c.nama AS nama_gudang, d.nama AS nama_satuan, e.nama AS nama_kategori FROM stok a 
INNER JOIN master_material b ON a.master_material_id=b.id
INNER JOIN master_gudang c ON a.master_gudang_id=c.id
INNER JOIN master_satuan d ON b.master_satuan_id=d.id
INNER JOIN master_kategori_material e ON b.master_kategori_material_id=e.id
WHERE a.id='$_GET[id]'";

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Log Stok</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="persediaan">Persediaan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table>
                    <tr>
                        <td width="150px">Kategori</td>
                        <td width="10px">:</td>
                        <td class="fw-bold"><?php echo $d['nama_kategori'];?></td>
                    </tr>
                    <tr>
                        <td>Merk/Type</td>
                        <td>:</td>
                        <td><?php echo $d['merk_type'];?></td>
                    </tr>
                    <tr>
                        <td width="150px">Gudang</td>
                        <td width="10px">:</td>
                        <td class="fw-bold"><?php echo $d['nama_gudang'];?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table>
                    <?php
                    $total=0;
                    $tampil=mysqli_query($conn,"SELECT a.nama, b.* FROM master_kondisi a LEFT JOIN stok_kondisi b ON a.id=b.master_kondisi_id AND b.stok_id='$_GET[id]' ORDER BY a.nama");
                    while($r=mysqli_fetch_array($tampil)){
                        ?>
                        <tr>
                            <td width="150px"><?php echo $r['nama'];?></td>
                            <td width="10px">:</td>
                            <td class="fw-bold text-end" width="50px"><?php echo formatAngka($r['jumlah']);?></td>
                        </tr>
                        <?php
                        $total+=$r['jumlah'];
                    }
                    ?>
                    <tr>
                        <td style="border-top:1px solid #000;">Total</td>
                        <td style="border-top:1px solid #000;">:</td>
                        <td  style="border-top:1px solid #000;" class="fw-bold text-end"><?php echo formatAngka($total);?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <b>Barang Serial Number</b>
            </div>
            <div class="card-body table-responsive">
                
                <table class="table" id="datatable_ajax_sn">
                    <thead class="table-info">
                        <tr>
                            <th width="15%">Tanggal / Jam</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Klasifikasi</th>
                            <th>Kondisi Pembelian Awal</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <b>Log Stok</b>
            </div>
            <div class="card-body table-responsive">
                <fieldset class="mb-3">
                    <legend>Filter Data</legend>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" id="stok_id">
                    <div class="row justify-content-center">
                        <label class="col-md-2 text-end pt-2">Bulan</label>
                        <div class="col-md-3">
                            <select name="id_bulan" class="form-control" id="id_bulan">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_bulan");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$bln_sekarang){
                                        echo "<option value='$r[id]' selected>$r[nama]</option>";
                                    }
                                    else{
                                        echo "<option value='$r[id]'>$r[nama]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <label class="col-md-2 text-end pt-2">Tahun</label>
                        <div class="col-md-2">
                            <input type="number" name="tahun" value="<?php echo $thn_sekarang;?>" class="form-control" id="tahun"/>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary" id="btnFilter"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </fieldset>

                <table class="table" id="datatable_ajax">
                    <thead class="table-info">
                        <tr>
                            <th width="15%">Tanggal / Jam</th>
                            <th width="30%">Keterangan</th>
                            <th>No Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
</script>
<?php
$order_column_add = datatable_column("3", "text-end", "true");
$order_column_add.= datatable_column("4", "text-end", "true");
$order_column_add.= datatable_column("5", "text-end", "true");

$filter = datatable_filter("id_bulan");
$filter.= datatable_filter("tahun");
$filter.= datatable_filter("stok_id");

echo generate_datatable("persediaan-view-data", "0", "asc", $order_column_add, '', $filter, "datatable_ajax");

$order_column_add= datatable_column("2", "text-center", "true");
$order_column_add.= datatable_column("3", "text-center", "true");
$order_column_add.= datatable_column("4", "text-center", "true");
$order_column_add.= datatable_column("5", "text-end", "true");
$filter= datatable_filter("stok_id");
echo generate_datatable("persediaan-view-data-sn", "0", "asc", $order_column_add, '', $filter, "datatable_ajax_sn");
}
else{
    ?>
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
        <img src="<?php echo $BASE_URL;?>/images/nodata.jpg" class="img-fluid">
        </div>
    </div>
    <?php
}
?>