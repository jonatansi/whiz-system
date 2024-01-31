<form action="material-input" method="POST" enctype="multipart/form-data" id="form_crud">
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
                            <label>ID Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="kode" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="master_kategori_material_id" class="form-control select2" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_kategori_material ORDER BY nama ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo"<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="form-group">
                            <label>Merk / Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="merk_type" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Satuan <span class="text-danger">*</span></label>
                            <select name="master_satuan_id" class="form-control select2" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_satuan ORDER BY nama ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo"<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" value="" name="remark">
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
                window.location.href="material?message=add";
                $("#form_modul").modal('hide');
            }
        });

    });
</script>
<script src="<?php echo $BASE_URL_MASTER;?>/addons/js/select2.js"></script>