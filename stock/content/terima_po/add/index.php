<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_item, (SELECT SUM(e.jumlah * e.harga) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_harga, w.nama AS nama_provinsi, x.nama AS nama_kabupaten, y.nama AS nama_kecamatan, z.nama AS nama_kelurahan 
FROM po a 
LEFT JOIN master_cabang b ON a.request_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON a.master_vendor_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
LEFT JOIN lok_provinsi w ON a.lok_provinsi_id=w.id
LEFT JOIN lok_kabupaten x ON a.lok_kabupaten_id=x.id
LEFT JOIN lok_kecamatan y oN a.lok_kecamatan_id=y.id
LEFT JOIN lok_kelurahan z ON a.lok_kelurahan_id=z.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[po_id]'"));
?>

<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Penerimaan Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="terimapo">Penerimaan Material</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>
<form method="POST" action="terimapo-input" enctype="multipart/form-data">
<input type="hidden" name="po_id" value="<?php echo $d['id'];?>">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <fieldset class="mb-4">
                    <legend>Detail PO</legend>
                    <div class="row">
                        <div class="col">
                            <table class="mytable">
                                <tr>
                                    <td class="">No. Purchase Order</td>
                                    <td class="text-end fw-bold"><?php echo $d['nomor'];?></td>
                                </tr>
                                <tr>
                                    <td class="">Tanggal</td>
                                    <td class="text-end fw-bold"><?php echo dateFormat($d['tanggal']);?></td>
                                </tr>
                                <tr>
                                    <td class="">Requester</td>
                                    <td class="text-end fw-bold"><?php echo $d['nama_cabang'];?></td>
                                </tr>
                                <tr>
                                    <td class="">PIC Penerima</td>
                                    <td class="text-end fw-bold"><?php echo $d['request_pic_nama'];?></td>
                                </tr>
                                <tr>
                                    <td class="">No. HP PIC</td>
                                    <td class="text-end fw-bold"><?php echo $d['request_pic_hp'];?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">
                            <table class="mytable top">
                                <tr>
                                    <td class="">No. Penawaran</td>
                                    <td class="text-end fw-bold"><?php echo $d['nomor_penawaran'];?></td>
                                </tr>
                                <tr>
                                    <td class="">Vendor</td>
                                    <td class="text-end fw-bold"><?php echo $d['nama_vendor'];?></td>
                                </tr>
                                <tr>
                                    <td class="">PIC Vendor</td>
                                    <td class="text-end fw-bold"><?php echo $d['vendor_pic_nama'];?></td>
                                </tr>
                                <tr>
                                    <td class="">No. HP PIC Vendor</td>
                                    <td class="text-end fw-bold"><?php echo $d['vendor_pic_hp'];?></td>
                                </tr>
                                <tr>
                                    <td class="">Dikirim ke</td>
                                    <td class="text-end fw-bold">
                                    <?php 
                                        echo $d['alamat_tujuan'].'<br>Kel. '.$d['nama_kelurahan'].', Kec. '.$d['nama_kecamatan'].'<br>'.$d['nama_kabupaten'].', '.$d['nama_provinsi'].'<br>Kode POS : '.$d['tujuan_kode_pos'];
                                    ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </fieldset>
                <div class="row mb-3">
                    <label class="col-md-2 text-end pt-2">Tanggal Penerimaan <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="tanggal" value="<?php echo $_GET['tanggal'];?>" max="<?php echo $tgl_sekarang;?>">
                    </div>
                    <!-- <label class="col-md-2 text-end pt-2">Dokumen Pendukung <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="dokumen" required accept="application/pdf, image/*">
                    </div> -->
                    <label class="col-md-2 text-end pt-2">Note / Remark</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="remark">
                    </div>
                </div>
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th><input type="checkbox" onclick="toggle(this);" checked></th>
                            <th>KATEGORI</th>
                            <th>MERK/TYPE</th>
                            <th>KONDISI PEMBELIAN AWAL</th>
                            <th>JLH ITEM</th>
                            <th width="150px">SDH DITERIMA</th>
                            <th width="150px">JLH DITERIMA <small class="text-danger">* </small></th>
                            <th>GUDANG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil=mysqli_query($conn,"SELECT a.*, b.merk_type, c.nama AS nama_kondisi, d.nama AS nama_kategori_material, e.nama AS nama_satuan_besar, f.nama AS nama_satuan_kecil, (a.jumlah-a.jumlah_diterima) AS sisa FROM po_detail a
                        LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
                        LEFT JOIN master_kondisi c ON a.master_kondisi_id=c.id AND c.deleted_at IS NULL
                        LEFT JOIN master_kategori_material d ON a.master_kategori_material_id=d.id AND d.deleted_at IS NULL
                        LEFT JOIN master_satuan e ON a.master_satuan_besar_id=e.id AND e.deleted_at IS NULL
                        LEFT JOIN master_satuan f ON a.master_satuan_kecil_id=f.id AND f.deleted_at IS NULL
                        WHERE a.deleted_at IS NULL AND a.po_id='$_GET[po_id]' HAVING sisa > 0 ORDER BY a.id ASC");
                        $grand_total=0;
                        $no=1;
                        while($r=mysqli_fetch_array($tampil)){
                            ?>
                            <tr>
                                <td><input type="checkbox" name="po_detail_id[]" value="<?php echo $r['id'];?>" checked></td>
                                <td><?php echo $r['nama_kategori_material'];?></td>
                                <td><?php echo $r['merk_type'];?></td>
                                <td><?php echo $r['nama_kondisi'];?></td>
                                <td>
                                    <?php echo formatAngka($r['jumlah']).' '.$r['nama_satuan_besar'];?><br>
                                    <?php echo formatAngka($r['jumlah_total']).' '.$r['nama_satuan_kecil'];?>
                                </td>
                                <td><?php echo formatAngka($r['jumlah_diterima']).' '.$r['nama_satuan_besar'];?></td>
                                <td>
                                    <input type="number" class="form-control" name="jumlah_<?php echo $r['id'];?>" min="1" max="<?php echo $r['sisa'];?>"  oninput="checkValue(this)" value="1">
                                </td>
                                <td>
                                    <select name="gudang_<?php echo $r['id'];?>" class="form-control">
                                        <?php
                                        $gudang=mysqli_query($conn,"SELECT id, kode, nama FROM master_gudang WHERE master_cabang_id='$pegawai[master_cabang_id]'");
                                        while($g=mysqli_fetch_array($gudang)){
                                            echo"<option value='$g[id]'>$g[kode] - $g[nama]</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
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
                <a href="terimapo"><button type="button" class="btn btn-danger has-ripple"><i class="bi bi-x"></i> Batal</button></a>
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
        var input = $(this).closest('tr').find('input[type="text"]');
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
            title: 'Konfirmasi Penerimaan ?',
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

    function checkValue(input) {
        var maxValue = parseFloat(input.getAttribute('max'));

        if (input.value > maxValue) {
            input.value = maxValue; // Atur nilai input menjadi nilai maksimum jika melebihi
        }
    }
</script>