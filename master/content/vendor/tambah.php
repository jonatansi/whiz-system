<form action="vendor-input" method="POST" enctype="multipart/form-data" id="form_crud">
	<div class="modal-dialog modal-lg a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Tambah</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>ID Vendor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="kode" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>NPWP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control npwp" value="" name="npwp" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="form-group">
                            <label>Nama Vendor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="nama" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Provinsi <span class="text-danger">*</span></label>
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

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kabupaten/Kota <span class="text-danger">*</span></label>
                            <select name="lok_kabupaten_id" class="form-control select2" id="id_kabupaten" style="width:100%"> </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kecamatan <span class="text-danger">*</span></label>
                            <select name="lok_kecamatan_id" class="form-control select2" id="id_kecamatan" style="width:100%"></select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kelurahan <span class="text-danger">*</span></label>
                            <select name="lok_kelurahan_id" class="form-control select2" id="id_kelurahan" style="width:100%"></select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="alamat" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="kode_pos" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>PIC Sales <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="sales_pic" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>No. HP Sales <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mob_no" value="" name="sales_hp" required>
                        </div>
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
        $(this).find(':submit').attr('disabled','disabled');
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
                window.location.href="vendor?message=add";
                $("#form_modul").modal('hide');
            }
        });

    });
</script>
<script src="<?php echo $BASE_URL_MASTER;?>/addons/js/select2.js"></script>
<script src="<?php echo $BASE_URL_MASTER;?>/addons/js/location_general.js"></script>
<script src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>