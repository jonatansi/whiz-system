<?php
$sql="SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_gudang, d.nama AS nama_status, d.warna AS warna_status, e.nama AS nama_pic, 
COALESCE(total_aktual_query.total_aktual, 0) AS total_aktual, 
COALESCE(total_item_query.total_tercatat, 0) AS total_item, -- Perbaikan alias kolom
COALESCE(total_sn_query.total_sn, 0) AS total_sn
FROM opname a 
LEFT JOIN master_cabang b ON a.created_master_cabang_id = b.id AND b.deleted_at IS NULL
LEFT JOIN master_gudang c ON a.master_gudang_id = c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id = d.id
LEFT JOIN pegawai e ON a.pic_pegawai_id = e.id AND e.deleted_at IS NULL
LEFT JOIN (
    SELECT opname_id, SUM(jumlah_aktual) AS total_aktual 
    FROM opname_detail 
    WHERE deleted_at IS NULL AND opname_id IS NOT NULL 
    GROUP BY opname_id
) AS total_aktual_query ON a.id = total_aktual_query.opname_id
LEFT JOIN (
    SELECT opname_id, SUM(jumlah_tercatat) AS total_tercatat -- Perbaikan alias kolom
    FROM opname_detail 
    WHERE deleted_at IS NULL AND opname_id IS NOT NULL 
    GROUP BY opname_id
) AS total_item_query ON a.id = total_item_query.opname_id
LEFT JOIN (
    SELECT g.opname_id, COUNT(f.id) AS total_sn 
    FROM opname_sn f INNER JOIN opname_detail g ON f.opname_detail_id = g.id AND g.deleted_at IS NULL AND f.material_sn_status_id='500'
    GROUP BY g.opname_id
) AS total_sn_query ON a.id = total_sn_query.opname_id
WHERE  a.deleted_at IS NULL AND a.id='$_GET[id]'";
if($pegawai['master_cabang_id']!='1'){
    $sql.=" AND a.created_master_cabang_id='$pegawai[master_cabang_id]'";
}

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Stock Opname</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="opname">Stock Opname</a></li>
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
                        if($d['status_id']=='350' AND $d['created_master_cabang_id']==$_SESSION['master_cabang_id']){
                            ?>
                            <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>'><i class='fas fa-check'></i> On Progress</button>
                            <button class='btn btn-danger btn-sm ml-2 btnCancel' id='<?php echo $d['id'];?>'><i class='fas fa-times'></i> Cancel</button>
                            <?php
                        }
                        if($d['status_id']=='360' AND $_SESSION['master_cabang_id']=='1'){
                            ?>
                            <button class='btn btn-success btn-sm ml-2 btnNext' id='<?php echo $d['id'];?>' <?php if($d['total_aktual']!=$d['total_sn']){echo "disabled";}?>><i class='fas fa-check'></i> Completed</button>
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
                                <td class="fw-bold">No. Penerimaan</td>
                                <td class="text-end"><?php echo $d['nomor'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal</td>
                                <td class="text-end"><?php echo dateFormat($d['tanggal']);?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td class="text-end"><?php echo "<span class='badge bg-$d[warna_status]'>$d[nama_status]</span>";?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">PIC</td>
                                <td class="text-end"><?php echo $d['nama_pic'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jabatan PIC</td>
                                <td class="text-end"><?php echo $d['pic_pegawai_jabatan'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Gudang</td>
                                <td class="text-end"><?php echo $d['nama_gudang'];?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <?php
                    if($d['status_id']!='365' AND $d['status_id']!='355'){
                    ?>
                    <button type="button" class="btn btn-primary mb-3 btnAdd" id="<?php echo $_GET['id'];?>"><i class="fa fa-plus"></i> Tambah Material Lainnya</button>
                    <?php
                    }
                    ?>
                    <div class="table-responsive" id="table_view_material"></div>
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
                        $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status, b.warna AS warna_status, c.nama AS nama_pegawai FROM opname_log a
                        INNER JOIN master_status b ON a.status_id=b.id 
                        INNER JOIN pegawai c ON a.pegawai_id=c.id AND c.deleted_at IS NULL 
                        WHERE a.opname_id='$_GET[id]' ORDER BY a.created_at ASC");
                        while($r=mysqli_fetch_array($tampil)){
                            $status="<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                            $dokumen="-";
                            if($r['dokumen']!=''){
                                $dokumen="<a href='$BASE_URL/files/opname/$r[dokumen]' target='_blank' class='btn btn-dark btn-sm'>Unduh</a>";
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
$(document).ready( function () {
    var id = '<?php echo $_GET['id'];?>'
    $.ajax({
        type: 'POST',
        url: 'opname-table-material-view',
        data:{
            'opname_id' : id
        },
        beforeSend: function() {
            $('.preloader').show();
            $('#table_view_material').html("Loading...");
        },
        complete: function() {
            $('.preloader').hide();
        },

        success: function(msg) {
            $('#table_view_material').html(msg);
        }
    });
});  

function validateForm() {
    var requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    var isValid = true;

    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            isValid = false;
            // Jika ada bidang yang kosong, tampilkan pesan kesalahan
            Swal.fire('Error', 'Harap lengkapi semua bidang yang diperlukan!', 'error');
            return;
        }
    });

    return isValid;
}

function showSweetAlert() {
    if (validateForm()) {
        // Tampilkan SweetAlert
        Swal.fire({
        title: 'Konfirmasi Pembuatan?',
        text: 'Apakah Anda yakin ingin menyimpan data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Submit!'
        }).then((result) => {
        if (result.isConfirmed) {
            // Lanjutkan untuk mengirim formulir setelah SweetAlert dikonfirmasi
            document.querySelector('form').submit();
        }
        });
    }
}
<?php
echo generate_javascript_action("btnAdd", "opname-tambah-material");
?>
</script>

<script type="text/javascript">
<?php
echo generate_javascript_action("btnNext", "opname-next");
echo generate_javascript_action("btnCancel", "opname-cancel");
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