<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Buat Stok Opname Material Baru</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="opname">Stok Opname Material</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="opname-input" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row form-group">
                            <label class="col-md-5 pt-2 text-end">
                                Admin Branch <small class="text-danger">*</small>
                            </label>
                            <div class="col-md-7">
                                <select name="pic_pegawai_id" class="form-control" required>
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
                                PIC Opname <small class="text-danger">*</small>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="pic_pegawai_jabatan" class="form-control" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 pt-2 text-end">
                                Gudang <small class="text-danger">*</small>
                            </label>
                            <div class="col-md-7">
                                <select name="master_gudang_id" class="form-control" required id="master_gudang_id">
                                    <?php
                                    $tampil=mysqli_query($conn,"SELECT * FROM master_gudang WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND deleted_at IS NULL ORDER BY nama");
                                    while($r=mysqli_fetch_array($tampil)){
                                        echo "<option value='$r[id]'>$r[kode] - $r[nama]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5"> 
                        <div class="row form-group">
                            <label class="col-md-5 pt-2 text-end">
                                Tanggal <small class="text-danger">*</small>
                            </label>
                            <div class="col-md-7">
                                <input type="date" name="tanggal" class="form-control" required max="<?php echo $tgl_sekarang;?>" value="<?php echo $tgl_sekarang;?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 pt-2 text-end">
                                Keterangan / Remark 
                            </label>
                            <div class="col-md-7">
                                <textarea name="remark" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12" id="table_add_material"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success has-ripple"  onclick="showSweetAlert()"><i class="bi bi-check"></i> Simpan</button>
                <a href="opname"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready( function () {
    function fetchTableData() {
        var master_gudang_id = $("#master_gudang_id").val();
        $.ajax({
            type: 'POST',
            url: 'opname-table-material-add',
            data: {
                'master_gudang_id': master_gudang_id
            },
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
    }

    // Fetch table data on document ready
    fetchTableData();

    // Fetch table data on master_gudang_id change
    $("#master_gudang_id").change(function() {
        fetchTableData();
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
// echo generate_javascript_action("btnAdd", "opname-tambah-material");
?>
</script>