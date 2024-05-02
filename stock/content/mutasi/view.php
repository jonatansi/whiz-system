<?php
$sql="SELECT a.*,  b.nama AS nama_cabang, c.nama AS nama_gudang, d.nama AS nama_status, d.warna AS warna_status, COALESCE(total_item_query.total_item, 0) AS total_item, COALESCE(total_sn_query.total_sn, 0) AS total_sn
FROM mutasi a 
LEFT JOIN master_cabang b ON a.created_master_cabang_id = b.id AND b.deleted_at IS NULL
LEFT JOIN master_gudang c ON a.master_gudang_tujuan_id = c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id = d.id
LEFT JOIN (SELECT mutasi_id, SUM(jumlah) AS total_item FROM mutasi_detail WHERE deleted_at IS NULL AND mutasi_id IS NOT NULL GROUP BY mutasi_id) AS total_item_query 
ON a.id = total_item_query.mutasi_id
LEFT JOIN (SELECT g.mutasi_id, COUNT(f.id) AS total_sn FROM mutasi_sn f INNER JOIN mutasi_detail g ON f.mutasi_detail_id = g.id AND g.deleted_at IS NULL GROUP BY g.mutasi_id) AS total_sn_query ON a.id = total_sn_query.mutasi_id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Mutasi Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="mutasi">Mutasi Material</a></li>
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
                        if($d['status_id']=='250' AND $d['created_master_cabang_id']==$_SESSION['master_cabang_id']){
                            ?>
                            <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> On Progress</button>
                            <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                            <?php
                        }
                        if($d['status_id']=='260' AND $d['created_master_cabang_id']==$_SESSION['master_cabang_id'] AND $d['total_item']==$d['total_sn']){
                            ?>
                            <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> Completed</button>
                            <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                            <?php
                        }
                        ?>
                        <!-- <button class='btn btn-danger btn-sm ml-2 btnCetak' id='<?php echo $d['id'];?>'><i class='fas fa-print'></i> Cetak</button> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- <iframe src="<?php echo $BASE_URL;?>/stock/mutasi-cetak-<?php echo $d['id'];?>" style="width:100%; height:100%; border:none" id="Iframe"></iframe> -->

                <table class="table" id="my_datatable">
                    <thead class="table-info">
                        <tr>
                            <th width="50px">NO</th>
                            <th>KATEGORI</th>
                            <th>MERK/TYPE</th>
                            <th>JLH ITEM</th>
                            <th>KONDISI</th>
                            <th>GUDANG ASAL</th>
                            <th>SN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil=mysqli_query($conn,"SELECT a.*, b.merk_type, c.nama AS nama_kategori_material, e.nama AS nama_satuan_kecil, f.nama AS nama_gudang, g.nama AS nama_kondisi, (SELECT COUNT(f.id) FROM mutasi_sn f WHERE a.id=f.mutasi_detail_id) AS total_sn 
                        FROM mutasi_detail a
                        LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
                        LEFT JOIN master_kategori_material c ON a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
                        LEFT JOIN master_satuan e ON a.master_satuan_kecil_id=e.id AND e.deleted_at IS NULL
                        LEFT JOIN master_gudang f ON a.master_gudang_asal_id=f.id AND f.deleted_at IS NULL
                        LEFT JOIN master_kondisi g ON a.master_kondisi_id=g.id AND g.deleted_at IS NULL
                        WHERE a.mutasi_id='$_GET[id]' AND a.deleted_at IS NULL");
                        $no=1;
                        while($r=mysqli_fetch_array($tampil)){
                            ?>
                            <tr>
                                <td><?php echo $no;?></td>
                                <td><?php echo $r['nama_kategori_material'];?></td>
                                <td><a href="mutasi-sn-<?php echo $r['id'];?>" class="text-primary"><?php echo $r['merk_type'];?></a></td>
                                <td class="text-center"><?php echo formatAngka($r['jumlah']).' '.$r['nama_satuan_kecil'];?></td>
                                <td><?php echo $r['nama_kondisi'];?></td>
                                <td><?php echo $r['nama_gudang'];?></td>
                                <td><?php echo formatAngka($r['total_sn']);?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                Log Status Mutasi
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
                        $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_pegawai FROM mutasi_log a
                        INNER JOIN master_status b ON a.status_id=b.id 
                        INNER JOIN pegawai c ON a.pegawai_id=c.id AND c.deleted_at IS NULL 
                        WHERE a.mutasi_id='$_GET[id]' ORDER BY a.created_at ASC");
                        while($r=mysqli_fetch_array($tampil)){
                            $status="<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                            $dokumen="-";
                            if($r['dokumen']!=''){
                                $dokumen="<a href='$BASE_URL/files/mutasi/$r[dokumen]' target='_blank' class='btn btn-dark btn-sm'>Unduh</a>";
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
        window.open("mutasi-cetak-"+id, "popupWindow", "width=600,height=600,scrollbars=yes");
    });

    // var frame = document.getElementById("Iframe");    
    // frame.onload = function(){
    //     frame.style.height = 
    //     frame.contentWindow.document.body.scrollHeight + 'px';
    //     frame.style.width  = 
    //     frame.contentWindow.document.body.scrollWidth+'px';   
    // }
    <?php
    echo general_default_datatable();
    echo generate_javascript_action("btnNext", "mutasi-next");
    echo generate_javascript_action("btnCancel", "mutasi-cancel");
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