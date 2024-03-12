<form action="po-input-material" method="POST" enctype="multipart/form-data" id="form_crud">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Tambah</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="master_kategori_material_id" class="form-control select2" id="kategori_material_id" required>
                            <option value="">Pilih</option>
                            <?php
                            $tampil=mysqli_query($conn,"SELECT * FROM master_kategori_material WHERE deleted_at IS NULL ORDER BY nama");
                            while($r=mysqli_fetch_array($tampil)){
                                echo "<option value='$r[id]'>$r[nama]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Merk / Type <span class="text-danger">*</span></label>
                        <select name="master_material_id" class="form-control select2" id="material_id" required>
                            <option value="">Pilih</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Kondisi <span class="text-danger">*</span></label>
                        <select name="master_kondisi_id" class="form-control select2"required>
                        <?php
                            $tampil=mysqli_query($conn,"SELECT * FROM master_kondisi WHERE deleted_at IS NULL ORDER BY nama");
                            while($r=mysqli_fetch_array($tampil)){
                                echo "<option value='$r[id]'>$r[nama]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Satuan Material PO <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control rupiah" name="jumlah" required placeholder="Jumlah Barang PO">
                            </div>
                            <div class="col-md-6">
                                <select name="master_satuan_besar_id" class="form-control select2">
                                    <?php
                                    $tampil=mysqli_query($conn,"SELECT * FROM master_satuan WHERE deleted_at IS NULL ORDER BY nama");
                                    while($r=mysqli_fetch_array($tampil)){
                                        echo "<option value='$r[id]'>$r[nama]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Konversi ke Satuan Dasar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah" name="jumlah_konversi" required placeholder="Jumlah Barang per Satuan PO">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Harga Satuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah" name="harga" required>
                    </div>
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Simpan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Batal</button>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
    $("#form_crud").submit(function(e) {
        $(this).find("button[type='submit']").prop('disabled',true);
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },

            success: function(msg) {
                // console.log(msg);
                $("#form_modul").modal('hide');
                $.ajax({
                    type: 'POST',
                    url: 'po-table-material-add',
                    beforeSend: function() {
                        $('.preloader').show();
                    },
                    complete: function() {
                        $('.preloader').hide();
                    },
                    success: function(msg) {
                        $('#table_add_material').html(msg);
                    }
                });
            }
        });

    });

    $("#kategori_material_id").change(function() {
        var kategori_material_id=$("#kategori_material_id").val();
        $.ajax({
            type: 'POST',
            url: "data-material",
            cache: false,
            data:{
                'kategori_material_id': kategori_material_id
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(data) {
                $("#material_id").html(data);
            }
        });
    });
</script>

<script type="text/javascript" src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>
<?php
echo form_select2();
?>