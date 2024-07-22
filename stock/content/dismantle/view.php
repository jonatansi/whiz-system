<?php
$sql="SELECT a.*, c.nama AS nama_cabang, e.nama AS nama_status, e.warna AS warna_status, (SELECT COUNT(f.id) FROM dismantle_sn f WHERE f.dismantle_id=a.id) AS total_item
FROM dismantle a
LEFT JOIN master_cabang c ON a.created_master_cabang_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status e ON a.status_id=e.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";
if($pegawai['master_cabang_id']!='1'){
    $sql.=" AND a.created_master_cabang_id='$pegawai[master_cabang_id]'";
}

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Dismantle Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="dismantle">Dismantle Material</a></li>
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
                       if($d['status_id']=='550' AND $pegawai['master_cabang_id']==$d['created_master_cabang_id']){
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
                <div class="row mb-4">
                    <div class="col-md-4">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">Nomor Transaksi</td>
                                <td class="text-end"><?php echo $d['nomor'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal</td>
                                <td class="text-end"><?php echo dateFormat($d['tanggal']);?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">User Identity</td>
                                <td class="text-end"><?php echo $d['user_identity'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">Branch</td>
                                <td class="text-end"><?php echo $d['nama_cabang'];?></td>
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
                </div>

                <div class="mb-3">
                    <table class="table" id="my_datatable">
                        <thead class="table-info">
                            <tr>
                                <th width="50px">NO</th>
                                <th>Serial Number</th>
                                <th>Kategori</th>
                                <th>Merk/Type</th>
                                <th>Kondisi Pembelian Awal</th>
                                <th>Klasifikasi</th>
                                <th>Status</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT a.*, c.nama AS nama_gudang, d.nama AS nama_kategori, e.nama AS nama_kondisi, f.merk_type, g.nama AS nama_klasifikasi, h.nama AS nama_status, h.warna AS warna_status 
                            FROM dismantle_sn a
                            LEFT JOIN material_sn b ON a.material_sn_id=b.id 
                            LEFT JOIN master_gudang c ON a.master_gudang_id=c.id AND c.deleted_at IS NULL
                            LEFT JOIN master_kategori_material d ON b.master_kategori_material_id=d.id AND d.deleted_at IS NULL
                            LEFT JOIN master_kondisi e ON b.master_kondisi_id=e.id AND e.deleted_at IS NULL
                            LEFT JOIN master_material f ON b.master_material_id=f.id AND f.deleted_at IS NULL
                            LEFT JOIN master_klasifikasi_material g ON b.master_klasifikasi_material_id=g.id
                            LEFT JOIN master_status h ON a.material_sn_status_id=h.id
                            WHERE a.dismantle_id='$_GET[id]'";
    
                            $tampil=mysqli_query($conn,$query);
                            $no=1;
                            while($r=mysqli_fetch_array($tampil)){
                                $status = "<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $r['serial_number'];?></td>
                                    <td><?php echo $r['nama_kategori'];?></td>
                                    <td><?php echo $r['merk_type'];?></td>
                                    <td><?php echo $r['nama_kondisi'];?></td>
                                    <td><?php echo $r['nama_klasifikasi'];?></td>
                                    <td><?php echo $status;?></td>
                                    <td><?php echo $r['nama_gudang'];?></td>
                                    <td><?php echo $r['remark'];?></td>
                                </tr>
                                <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
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
                        $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_pegawai FROM dismantle_log a
                        INNER JOIN master_status b ON a.status_id=b.id 
                        INNER JOIN pegawai c ON a.pegawai_id=c.id AND c.deleted_at IS NULL 
                        WHERE a.dismantle_id='$_GET[id]' ORDER BY a.created_at ASC");
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
echo generate_javascript_action("btnNext", "dismantle-next");
echo generate_javascript_action("btnCancel", "dismantle-cancel");
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