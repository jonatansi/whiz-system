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
                <li class="breadcrumb-item"><a href="javascript:void(0);">Persediaan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
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
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td width="150px">Gudang</td>
                                <td width="10px">:</td>
                                <td class="fw-bold"><?php echo $d['nama_gudang'];?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                Log Stok
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead class="table-info">
                        <tr>
                            <th width="20%">Tanggal / Jam</th>
                            <th width="15%">Keterangan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil=mysqli_query($conn,"SELECT * FROM stok_log WHERE stok_id='$_GET[id]' ORDER BY created_at ASC");
                        while($r=mysqli_fetch_array($tampil)){
                            ?>
                            <tr>
                                <td><?php echo WaktuIndo($r['created_at']);?></td>
                                <td><?php echo $r['remark'];?></td>
                                <td><?php echo $r['masuk'];?></td>
                                <td><?php echo $r['keluar'];?></td>
                                <td><?php echo $r['balance'];?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".btnCetak").click(function() {
        var id = this.id;
        window.open("po-cetak-"+id, "popupWindow", "width=600,height=600,scrollbars=yes");
    });

    var frame = document.getElementById("Iframe");    
    frame.onload = function(){
        frame.style.height = 
        frame.contentWindow.document.body.scrollHeight + 'px';
        frame.style.width  = 
        frame.contentWindow.document.body.scrollWidth+'px';   
    }
    <?php
    echo generate_javascript_action("btnNext", "po-next");
    echo generate_javascript_action("btnCancel", "po-cancel");
    ?>
</script>
<?php
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