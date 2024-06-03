<?php
$sql="SELECT a.*, b.id AS po_id, b.request_master_cabang_id, b.nomor AS nomor_po, b.tanggal AS tanggal_po, c.nama AS nama_cabang, d.nama AS nama_vendor, e.nama AS nama_status, e.warna AS warna_status, f.nama AS nama_status_po, f.warna AS warna_status_po
FROM po_terima a
LEFT JOIN po b ON a.po_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_cabang c ON b.request_master_cabang_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_vendor d ON b.master_vendor_id=d.id AND d.deleted_at IS NULL
LEFT JOIN master_status e ON a.status_id=e.id
LEFT JOIN master_status f ON b.status_id=f.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";
if($pegawai['master_cabang_id']!='1'){
    $sql.=" AND b.request_master_cabang_id='$pegawai[master_cabang_id]'";
}

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Penerimaan Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="terimapo">Penerimaan Material</a></li>
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
                        <h6><?php echo $d['nomor'];?></h6>
                    </div>
                    <div class="col text-end">
                       <?php
                       if($d['status_id']=='200' AND $pegawai['master_cabang_id']==$d['request_master_cabang_id']){
                        ?>
                        <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> Completed</button>
                        <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- <div class="row mb-5">
                    <div class="col-md-6">
                        <h2>Penerimaan Material</h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <img src="<?php echo $BASE_URL;?>/images/logo.png">
                    </div>
                </div> -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">No. Penerimaan</td>
                                <td class="text-end"><?php echo $d['nomor'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Terima</td>
                                <td class="text-end"><?php echo dateFormat($d['tanggal']);?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td class="text-end"><?php echo "<span class='badge bg-$d[warna_status]'>$d[nama_status]</span>";?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Remark</td>
                                <td class="text-end"><?php echo $d['remark'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">No. Purchase Order</td>
                                <td class="text-end"><?php echo $d['nomor_po'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal PO</td>
                                <td class="text-end"><?php echo dateFormat($d['tanggal_po']);?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td class="text-end"><?php echo "<span class='badge bg-$d[warna_status_po]'>$d[nama_status_po]</span>";?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <table class="table" id="my_datatable">
                        <thead class="table-info">
                            <tr>
                                <th width="50px">NO</th>
                                <th>KATEGORI</th>
                                <th>MERK/TYPE</th>
                                <th>KONDISI PEMBELIAN AWAL</th>
                                <th>JLH DITERIMA</th>
                                <th>JLH ITEM</th>
                                <th>GUDANG</th>
                                <th>SN DIINPUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status_completed = true;
                            $total_sn = 0;
                            $total_item = 0;
                            $tampil=mysqli_query($conn,"SELECT a.*, b.jumlah_konversi, c.merk_type, d.nama AS nama_kondisi, e.nama AS nama_kategori_material, f.nama AS nama_satuan_besar, g.nama AS nama_gudang, h.nama AS nama_satuan_kecil, (SELECT COUNT(j.id) AS tot FROM po_terima_sn j WHERE j.po_terima_detail_id=a.id AND j.status IN (1,2)) AS total_sn
                            FROM po_terima_detail a
                            LEFT JOIN po_detail b ON a.po_detail_id=b.id
                            LEFT JOIN master_material c ON b.master_material_id=c.id AND c.deleted_at IS NULL
                            LEFT JOIN master_kondisi d ON b.master_kondisi_id=d.id AND d.deleted_at IS NULL
                            LEFT JOIN master_kategori_material e ON b.master_kategori_material_id=e.id AND e.deleted_at IS NULL
                            LEFT JOIN master_satuan f ON b.master_satuan_besar_id=f.id AND f.deleted_at IS NULL
                            LEFT JOIN master_satuan h ON b.master_satuan_kecil_id=h.id AND h.deleted_at IS NULL
                            LEFT JOIN master_gudang g ON a.master_gudang_id=g.id AND g.deleted_at IS NULL
                            WHERE a.po_terima_id='$_GET[id]' AND a.deleted_at IS NULL");
                            $no=1;
                            while($r=mysqli_fetch_array($tampil)){
                                $total_item_detail = $r['jumlah_diterima']*$r['jumlah_konversi'];
                                
                                $total_item +=$total_item_detail;
                                $total_sn +=$r['total_sn'];
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $r['nama_kategori_material'];?></td>
                                    <td><a href="terimapo-sn-<?php echo $r['id'];?>" class="text-primary"><?php echo $r['merk_type'];?></a></td>
                                    <td><?php echo $r['nama_kondisi'];?></td>
                                    <td><?php echo formatAngka($r['jumlah_diterima']).' '.$r['nama_satuan_besar'];?></td>
                                    <td><?php echo formatAngka($total_item_detail).' '.$r['nama_satuan_kecil'];?></td>
                                    <td><?php echo $r['nama_gudang'];?></td>
                                    <td><?php echo formatAngka($r['total_sn']).' '.$r['nama_satuan_kecil'];?></td>
                                </tr>
                                <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="total_item" value="<?php echo $total_item;?>">
                    <input type="hidden" id="total_sn" value="<?php echo $total_sn;?>">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                Log Status
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
                        $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_pegawai FROM po_terima_log a
                        INNER JOIN master_status b ON a.status_id=b.id 
                        INNER JOIN pegawai c ON a.pegawai_id=c.id AND c.deleted_at IS NULL 
                        WHERE a.po_terima_id='$_GET[id]' ORDER BY a.created_at ASC");
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
<?php
echo general_default_datatable();
echo generate_javascript_action("btnNext", "terimapo-next");
echo generate_javascript_action("btnCancel", "terimapo-cancel");
?>

$(document).ready(function (){
    var total_item = $("#total_item").val();
    var total_sn = $("#total_sn").val();

    if(total_item!=total_sn){
        $(".btnNext").prop("disabled",true);
    }
});
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