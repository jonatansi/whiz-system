<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Buat Purchase Order Baru</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Purcase Order</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="po-input" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">
                    <legend>Data PO</legend>
                    <div class="row mb-3">
                        <label class="col-md-2 pt-2 text-end">
                            No. Purchase Order <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="nomor" required>
                        </div>
                        <label class="col-md-2 pt-2 text-end">
                            Tanggal <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="tanggal" max="<?php echo $tgl_sekarang;?>" value="<?php echo $tgl_sekarang;?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 pt-2 text-end">
                            Requester <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <select name="request_master_cabang_id" class="form-control" required>
                                <?php
                                $tampil=mysqli_query($conn, "SELECT * FROM master_cabang WHERE deleted_at IS NULL ORDER BY nama");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo "<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label class="col-md-2 pt-2 text-end">
                            Vendor <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <select name="master_vendor_id" class="form-control" required>
                            <?php
                                $tampil=mysqli_query($conn, "SELECT * FROM master_vendor WHERE deleted_at IS NULL ORDER BY nama");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo "<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 pt-2 text-end">
                            PIC Penerima <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="pic_nama" class="form-control" required>
                        </div>
                        <label class="col-md-2 pt-2 text-end">
                            No. Penawaran <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="nomor_penawaran" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-2 pt-2 text-end">
                            No. HP PIC <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="pic_hp" class="form-control mob_no" required>
                        </div>
                        <label class="col-md-2 pt-2 text-end">
                            Dikirim ke <small class="text-danger">*</small>
                        </label>
                        <div class="col-md-3">
                            <textarea name="alamat_tujuan" class="form-control" required></textarea>
                        </div>
                    </div>
                </fieldset>
                <button type="button" class="btn btn-primary mb-3 btnAdd"><i class="fa fa-plus"></i> Tambah Material</button>
                <div class="table-responsive" id="table_add_material">
                   
                </div>
                <div class="form-group mt-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Simpan</button>
                <a href="po"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready( function () {
    $.ajax({
        type: 'POST',
        url: 'po-table-material-add',
        beforeSend: function() {
            $('.preloader').show();
            $('#table_add_material').html("Loading...");
        },
        complete: function() {
            $('.preloader').hide();
        },

        success: function(msg) {
            $('#table_add_material').html(msg);
        }
    });
});    
<?php
echo generate_javascript_action("btnAdd", "po-tambah-material");
?>
</script>