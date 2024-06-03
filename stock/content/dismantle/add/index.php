
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Dismantle</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="dismantle">Dismantle</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>
<?php
if($_GET['master_guna_id']!='' AND $_GET['master_guna_kategori_id']!=''){
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_guna WHERE id='$_GET[master_guna_id]'"));
if($d['type']=='1'){
    $sql="SELECT id, kode, nama FROM master_customer WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND deleted_at IS NULL AND id='$_GET[master_guna_kategori_id]'";
}
else if($d['type']=='2'){
    $sql="SELECT id, nama FROM master_guna_kategori WHERE master_guna_id='$_GET[master_guna_id]' AND deleted_at IS NULL AND id='$_GET[master_guna_kategori_id]'";
}
$x=mysqli_fetch_array(mysqli_query($conn,$sql));
?>
<form method="POST" action="dismantle-input" enctype="multipart/form-data">
<input type="hidden" name="master_guna_id" value="<?php echo $_GET['master_guna_id'];?>">
<input type="hidden" name="master_guna_kategori_id" value="<?php echo $_GET['master_guna_kategori_id'];?>">
<input type="hidden" name="user_identity" value="<?php echo $x['nama'];?>">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-3">
                    <legend>Dismantle</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td width="250px">Kategori Penggunaan</td>
                                    <td width="10px">:</td>
                                    <td><?php echo $d['nama'];?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td width="250px">User identity</td>
                                    <td width="10px">:</td>
                                    <td><?php echo $x['nama'];?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </fieldset>
                <div class="row mb-3">
                    <label class="col-md-2 text-end pt-2">Tanggal <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="tanggal" value="<?php echo $tgl_sekarang;?>" max="<?php echo $tgl_sekarang;?>">
                    </div>
                    <label class="col-md-2 text-end pt-2">Note / Remark</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="remark">
                    </div>
                </div>
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th><input type="checkbox" onclick="toggle(this);" checked></th>
                            <th>Serial Number</th>
                            <th>Kategori</th>
                            <th>Merk/Type</th>
                            <th>Kondisi Pembelian Awal</th>
                            <th>Klasifikasi</th>
                            <th>Status</th>
                            <th>Gudang Penyimpanan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT a.*, b.nama AS nama_gudang, c.nama AS nama_kategori, d.nama AS nama_kondisi, e.nama AS nama_status, e.warna AS warna_status, f.merk_type, h.nama AS nama_klasifikasi FROM material_sn a
                        LEFT JOIN master_gudang b ON a.master_gudang_id=b.id AND b.deleted_at IS NULL
                        LEFT JOIN master_kategori_material c on a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
                        LEFT JOIN master_kondisi d ON a.master_kondisi_id=d.id AND d.deleted_at IS NULL
                        LEFT JOIN master_material f ON a.master_material_id=f.id AND f.deleted_at IS NULL
                        LEFT JOIN master_status e ON a.status_id=e.id 
                        LEFT JOIN master_klasifikasi_material h ON a.master_klasifikasi_material_id=h.id
                        WHERE b.deleted_at IS NULL AND b.master_cabang_id='$_SESSION[master_cabang_id]' AND master_guna_kategori_id='$_GET[master_guna_kategori_id]' AND a.master_klasifikasi_material_id='1' AND a.status_id='505' ORDER BY a.serial_number";

                        $tampil=mysqli_query($conn,$query);
                        $grand_total=0;
                        $no=1;
                        while($r=mysqli_fetch_array($tampil)){
                            ?>
                            <tr>
                                <td><input type="checkbox" name="material_sn_id[]" value="<?php echo $r['id'];?>" checked></td>
                                <td><?php echo $r['serial_number'];?></td>
                                <td><?php echo $r['nama_kategori'];?></td>
                                <td><?php echo $r['merk_type'];?></td>
                                <td><?php echo $r['nama_kondisi'];?></td>
                                <td><?php echo $r['nama_klasifikasi'];?></td>
                                <td>
                                    <select name="material_sn_status_id_<?php echo $r['id'];?>" class="form-control" required>
                                        <?php
                                        $status=mysqli_query($conn,"SELECT * FROM master_status WHERE id NOT IN (500,505) AND remark='SN' ORDER BY id");
                                        while($s=mysqli_fetch_array($status)){
                                            echo "<option value='$s[id]'>$s[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="master_gudang_id_<?php echo $r['id'];?>" class="form-control" required>
                                        <?php
                                        $gudang = mysqli_query($conn,"SELECT id, kode, nama fROM master_gudang WHERE deleted_at IS NULL AND master_cabang_id='$_SESSION[master_cabang_id]' ORDER BY kode");
                                        while($g=mysqli_fetch_array($gudang)){
                                            echo "<option value='$g[id]'>$g[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="remark_<?php echo $r['id'];?>"></td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
                <br><br><br>
                NB : <small class="text-danger">* </small> Barang yang diterima adalah dalam satuan besar
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success has-ripple" onclick="showSweetAlert()"><i class="bi bi-check"></i> Simpan</button>
                <a href="dismantle"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/javascript">
    function toggle(source) { 
        let checkboxes = document 
            .querySelectorAll('input[type="checkbox"]'); 
        for (let i = 0; i < checkboxes.length; i++) { 
            if (checkboxes[i] != source) 
                checkboxes[i].checked = source.checked; 
        } 
    }
    $('input[type="checkbox"]').change(function(){
        var checked = $(this).is(':checked');
        var input = $(this).closest('tr').find('select');
        input.prop('required', checked)
    })

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
            title: 'Konfirmasi Dismantle ?',
            text: 'Apakah Anda yakin ingin dismantle masterial ini?',
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

    function checkValue(input) {
        var maxValue = parseFloat(input.getAttribute('max'));

        if (input.value > maxValue) {
            input.value = maxValue; // Atur nilai input menjadi nilai maksimum jika melebihi
        }
    }
</script>
<?php
}
else{
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <div class="alert alert-danger">
                    Tolong jangan hapus parameter url karena ini penting
                </div>
                <img src="<?php echo $BASE_URL;?>/images/nodata.jpg" class="img-fluid" style="max-width:500px;">
            </div>
            <div class="card-footer">
                <a href="dismantle"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
            </div>
        </div>
    </div>
</div>
<?php
}
?>