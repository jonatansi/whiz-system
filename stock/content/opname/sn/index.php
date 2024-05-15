<?php
$sql="SELECT a.id, a.opname_id,  a.jumlah_tercatat, a.jumlah_aktual, b.merk_type, c.nama AS nama_kategori_material, d.nama AS nama_satuan_kecil, e.nama AS nama_kondisi, (SELECT COUNT(f.id) FROM opname_sn f WHERE a.id=f.opname_detail_id AND f.material_sn_status_id='500') AS total_sn 
FROM opname_detail a
LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_kategori_material c ON b.master_kategori_material_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_satuan d ON a.master_satuan_kecil_id=d.id AND d.deleted_at IS NULL
LEFT JOIN master_kondisi e ON a.master_kondisi_id=e.id
WHERE a.deleted_at IS NULL AND  a.id='$_GET[id]'";
// if($pegawai['master_cabang_id']!='1'){
//     $sql.=" AND c.master_cabang_id='$pegawai[master_cabang_id]'";
// }

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
$opname_id=$d['opname_id'];
$jumlah_item = $d['jumlah_aktual'];
$opname_detail_id=$d['id'];

$m=mysqli_fetch_array(mysqli_query($conn,"SELECT status_id FROM opname WHERE id='$opname_id'"));
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Stock Opname</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="opname">Stock Opname</a></li>
                <li class="breadcrumb-item active" aria-current="page">Input Serial Number</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold" width="250px">Merk/Type</td>
                                <td class="text">: <?php echo $d['merk_type'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kategori</td>
                                <td class="text">: <?php echo $d['nama_kategori_material'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kondisi</td>
                                <td class="text">: <?php echo $d['nama_kondisi'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold" width="250px">Jumlah Tercatat</td>
                                <td class="text">: <?php echo formatAngka($d['jumlah_tercatat']).' '.$d['nama_satuan_kecil'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jumlah Aktual</td>
                                <td class="text">: <?php echo formatAngka($d['jumlah_aktual']).' '.$d['nama_satuan_kecil'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total SN Diinput</td>
                                <td class="text">: <?php echo formatAngka($d['total_sn']);?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <?php
                        if($d['jumlah_aktual']>$d['total_sn']){
                        ?>
                        <button type="button" class="btn btn-primary mb-3 btnAdd" id="<?php echo $_GET['id'];?>"><i class="fa fa-plus"></i> Tambah</button>
                        <?php
                        }
                        ?>
                        <div class="table-responsive" id="table_sn_material"></div>
                        
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="opname-view-<?php echo $opname_id;?>" class="btn btn-dark">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready( function () {
var id = '<?php echo $_GET['id'];?>'
$.ajax({
    type: 'POST',
    url: 'opname-table-material-sn',
    data:{
        'opname_detail_id' : id
    },
    beforeSend: function() {
        $('.preloader').show();
        $('#table_sn_material').html("Loading...");
    },
    complete: function() {
        $('.preloader').hide();
    },

    success: function(msg) {
        $('#table_sn_material').html(msg);
    }
});
});  
<?php
echo generate_javascript_action("btnAdd", "opname-sn-tambah");
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