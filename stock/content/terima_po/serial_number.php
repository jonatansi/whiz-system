<?php
$sql="SELECT a.*, b.jumlah_konversi, c.nama AS nama_gudang, d.nama AS nama_satuan_besar, e.nama AS nama_satuan_kecil, f.merk_type, (SELECT COUNT(j.id) AS tot FROM material_sn j WHERE j.status_id='1' AND j.table_id=a.id AND j.table_name='po_terima_detail') AS total_sn
FROM po_terima_detail a
LEFT JOIN po_detail b ON a.po_detail_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_material f ON b.master_material_id=f.id AND f.deleted_at IS NULL
LEFT JOIN master_gudang c ON a.master_gudang_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_satuan d ON b.master_satuan_besar_id=d.id AND d.deleted_at IS NULL
LEFT JOIN master_satuan e ON b.master_satuan_kecil_id=e.id AND e.deleted_at IS NULL
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'";
// if($pegawai['master_cabang_id']!='1'){
//     $sql.=" AND c.master_cabang_id='$pegawai[master_cabang_id]'";
// }

$d=mysqli_fetch_array(mysqli_query($conn,$sql));
if(isset($d['id'])!=''){
$terima_po_id=$d['po_terima_id'];
$jumlah_item = $d['jumlah_diterima']*$d['jumlah_konversi'];

?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Penerimaan Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Penerimaan Material</a></li>
                <li class="breadcrumb-item active" aria-current="page">Input Serial Number</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">Merk/Type</td>
                                <td class="text">: <?php echo $d['merk_type'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jumlah Terima</td>
                                <td class="text">: <?php echo $d['jumlah_diterima'].' '.$d['nama_satuan_besar'];?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jumlah Item</td>
                                <td class="text">: <?php echo ($d['jumlah_diterima']*$d['jumlah_konversi']).' '.$d['nama_satuan_kecil'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="mytable">
                            <tr>
                                <td class="fw-bold">Gudang Penyimpanan</td>
                                <td class="text">: <?php echo $d['nama_gudang'];?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <fieldset class="mb-3">
                            <legend>Input Serial Number</legend>
                            <form method="POST" action="terimapo-sn-input">
                                <input type="hidden" name="po_terima_detail_id" value="<?php echo $_GET['id'];?>">
                                <div class="row">
                                    <label class="col-md-2 text-end pt-2">
                                        Serial Number <small class="text-danger">*</small>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="serial_number" required autofocus <?php if($jumlah_item<=$d['total_sn']){echo "disabled";}?>>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <small>NB : Silahkan diinput SN "0" jika tidak punya SN.<br>Jika diinput 0, maka langsung terisi seluruh item</small>
                            </form>
                        </fieldset>
                        <fieldset>
                            <legend>Data yang telah masuk</legend>
                            <!-- <div id="terimapo_sn_data"></div> -->
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
                                        $tampil=mysqli_query($conn,"SELECT * FROM material_sn WHERE table_id='$_GET[id]' AND table_name='po_terima_detail' AND status_id='1'");
                                        while($r=mysqli_fetch_array($tampil)){
                                            ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $r['serial_number'];?></td>
                                                <td><?php echo formatAngka($r['harga']);?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>"><i class="bi bi-trash"></i> Delete</button>
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
                <a href="terimapo-view-<?php echo $terima_po_id;?>" class="btn btn-dark">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php
echo generate_javascript_action("btnDelete", "terimapo-sn-delete");

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