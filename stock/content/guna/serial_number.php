<?php
$sql="SELECT a.*, b.merk_type, c.nama AS nama_kategori_material, e.nama AS nama_satuan_kecil, f.nama AS nama_gudang, g.nama AS nama_kondisi,
(SELECT COUNT(f.id) FROM guna_sn f WHERE a.id=f.guna_detail_id) AS total_sn 
FROM guna_detail a
LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_kategori_material c ON a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_satuan e ON a.master_satuan_kecil_id=e.id AND e.deleted_at IS NULL
LEFT JOIN master_gudang f ON a.master_gudang_asal_id=f.id AND f.deleted_at IS NULL
LEFT JOIN master_kondisi g ON a.master_kondisi_id=g.id AND g.deleted_at IS NULL
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";
// if($pegawai['master_cabang_id']!='1'){
//     $sql.=" AND c.master_cabang_id='$pegawai[master_cabang_id]'";
// }

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
$guna_id=$d['guna_id'];
$jumlah_item = $d['jumlah'];

$m=mysqli_fetch_array(mysqli_query($conn,"SELECT status_id FROM guna WHERE id='$guna_id'"));
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Penggunaan Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="guna">Penggunaan Material</a></li>
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
                                <td class="fw-bold">Merk/Type</td>
                                <td class="text">: <?php echo $d['merk_type'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jumlah Item</td>
                                <td class="text">: <?php echo $d['jumlah'].' '.$d['nama_satuan_kecil'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">Gudang Asal</td>
                                <td class="text">: <?php echo $d['nama_gudang'];?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <?php
                        if($m['status_id']=='310'){
                        ?>
                        <fieldset class="mb-3">
                            <legend>Input Serial Number</legend>
                            <form method="POST" action="guna-sn-input">
                                <input type="hidden" name="guna_detail_id" value="<?php echo $_GET['id'];?>">
                                <div class="row">
                                    <label class="col-md-2 text-end pt-2">
                                        Serial Number <small class="text-danger">*</small>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="serial_number" required autofocus <?php if($jumlah_item<=$d['total_sn']){echo "disabled";}?>>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success"  <?php if($jumlah_item<=$d['total_sn']){echo "disabled";}?>>Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                        <?php
                        }
                        ?>
                        <fieldset>
                            <legend>Data Serial Number yang digunakan</legend>
                            <div class="table table-responsive">
                                <table class="table table-sm" id="my_datatable">
                                    <thead class="table-info text-center">
                                        <tr>
                                            <th width="100">No.</th>
                                            <th>Serial Number</th>
                                            <th>Harga</th>
                                            <th width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no=1;
                                        $tampil=mysqli_query($conn,"SELECT a.*, b.harga FROM guna_sn a INNER JOIN material_sn b ON a.material_sn_id=b.id WHERE a.guna_detail_id='$_GET[id]'");
                                        while($r=mysqli_fetch_array($tampil)){
                                            $datetimeObj = new DateTime($r['created_at']);
                                            $tanggal_sn = $datetimeObj->format('Y-m-d');
                                            ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $r['serial_number'];?></td>
                                                <td><?php echo formatAngka($r['harga']);?></td>
                                                <td>
                                                    <?php
                                                    if($r['status']=='1'){
                                                    ?>
                                                    <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>"><i class="bi bi-trash"></i> Delete</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="guna-view-<?php echo $guna_id;?>" class="btn btn-dark">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php
echo generate_javascript_action("btnDelete", "guna-sn-delete");

echo general_default_datatable();
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