<?php
$sql="SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_item, (SELECT SUM(e.jumlah * e.harga) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_harga 
FROM po a 
LEFT JOIN master_cabang b ON a.request_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON a.master_vendor_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";
if($pegawai['master_cabang_id']!='1'){
    $sql.=" AND a.request_master_cabang_id='$pegawai[master_cabang_id]'";
}

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Purchase Order</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="po">Purchase Order</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col fw-bold">
                        <?php
                        echo $d['nomor'];
                        ?>
                    </div>
                    <div class="col text-end">
                        <?php
                        if($d['status_id']=='1' AND $pegawai['master_cabang_id']=='1'){
                            ?>
                            <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> Submitted</button>
                            <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                            <?php
                        }
                        else if($d['status_id']=='10' AND $pegawai['master_cabang_id']==$d['created_master_cabang_id']){
                            ?>
                            <button class='btn btn-warning btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> On Delivery</button>
                            <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                            <?php
                        }
                        ?>
                        <!-- <button class='btn btn-danger btn-sm ml-2 btnCetak' id='<?php echo $d['id'];?>'><i class='fas fa-print'></i> Cetak</button> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <iframe src="<?php echo $BASE_URL;?>/stock/po-cetak-<?php echo $d['id'];?>" style="width:100%; height:100%; border:none" id="Iframe"></iframe>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                Log Status PO
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead class="table-info">
                        <tr>
                            <th width="20%">Tanggal / Jam</th>
                            <th width="15%">Status</th>
                            <th width="15%">Oleh</th>
                            <th>Dokumen</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_pegawai FROM po_log a
                        INNER JOIN master_status b ON a.status_id=b.id 
                        INNER JOIN pegawai c ON a.pegawai_id=c.id AND c.deleted_at IS NULL 
                        WHERE a.po_id='$_GET[id]' ORDER BY a.created_at ASC");
                        while($r=mysqli_fetch_array($tampil)){
                            $status="<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                            $dokumen="-";
                            if($r['dokumen']!=''){
                                $dokumen="<a href='$BASE_URL/files/po/$r[dokumen]' target='_blank' class='btn btn-dark btn-sm'>Unduh</a>";
                            }
                            ?>
                            <tr>
                                <td><?php echo WaktuIndo($r['created_at']);?></td>
                                <td><?php echo $status;?></td>
                                <td><?php echo $r['nama_pegawai'];?></td>
                                <td><?php echo $dokumen;?></td>
                                <td><?php echo $r['remark'];?></td>
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