<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Buat Penggunaan Material Baru</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="guna">Penggunaan Material</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="guna-input" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">
                    <legend>Data Penggunaan</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Nama Requester <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="request_pegawai_id" class="form-control" required>
                                        <?php
                                        $tampil=mysqli_query($conn,"SELECT * FROM pegawai WHERE master_cabang_id='$pegawai[master_cabang_id]' AND deleted_at IS NULL ORDER BY nama");
                                        while($r=mysqli_fetch_array($tampil)){
                                            echo "<option value='$r[id]'>$r[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Jabatan Requester <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="text" name="request_pegawai_jabatan" class="form-control" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Tanggal <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="date" name="tanggal" class="form-control" required max="<?php echo $tgl_sekarang;?>" value="<?php echo $tgl_sekarang;?>">
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6"> 
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Kategori Penggunaan <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="master_guna_id" class="form-control" required>
                                    <?php
                                        $tampil=mysqli_query($conn, "SELECT * FROM master_guna ORDER BY nama");
                                        while($r=mysqli_fetch_array($tampil)){
                                            echo "<option value='$r[id]'>$r[kode] - $r[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Nomor Referensi <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="text" name="no_ref" class="form-control" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Remark / Note
                                </label>
                                <div class="col-md-7">
                                    <textarea name="deskripsi" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>

                <fieldset class="mb-3">
                    <legend>Alamat Penggunaan</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Provinsi <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="lok_provinsi_id" class="form-control select2" id="id_propinsi" style="width:100%">
                                        <option value="">Pilih Propinsi</option>
                                        <?php
                                        $tampil=mysqli_query($conn,"SELECT * FROM lok_provinsi ORDER BY id ASC");
                                        while($r=mysqli_fetch_array($tampil)){
                                            echo"<option value='$r[id]'>$r[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Kecamatan <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="lok_kecamatan_id" class="form-control select2" id="id_kecamatan" style="width:100%"></select>
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Alamat <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <textarea name="alamat_tujuan" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Tujuan Penggunaan <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="text"  name="tujuan_penggunaan" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Kabupaten/Kota <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="lok_kabupaten_id" class="form-control select2" id="id_kabupaten" style="width:100%"> </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Kelurahan/Desa <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <select name="lok_kelurahan_id" class="form-control select2" id="id_kelurahan" style="width:100%"></select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 pt-2 text-end">
                                    Kode POS <small class="text-danger">*</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="text" name="tujuan_kode_pos" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <button type="button" class="btn btn-primary mb-3 btnAdd"><i class="fa fa-plus"></i> Tambah Material</button>
                <div class="table-responsive" id="table_add_material"></div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success has-ripple"  onclick="showSweetAlert()"><i class="bi bi-check"></i> Simpan</button>
                <a href="guna"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready( function () {
    $.ajax({
        type: 'POST',
        url: 'guna-table-material-add',
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
    if(validateForm()){
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
echo generate_javascript_action("btnAdd", "guna-tambah-material");
?>
</script>
<script src="<?php echo $BASE_URL_STOCK;?>/addons/js/location_general.js"></script>