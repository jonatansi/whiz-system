<form action="opname-sn-input" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="opname_detail_id" value="<?php echo $_POST['id'];?>" id="opname_detail_id">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Tambah</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Serial Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="serial_number" required autofocus>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Harga <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rupiah" name="harga" required placeholder="Harga">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Remark <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="remark" required placeholder="Misalnya : Retur project">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="error_msg"></div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple" id="btnSubmit"><i class="bi bi-check"></i> Simpan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Batal</button>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
    function performAjaxRequest(url, data, successCallback) {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").hide();
            },
            success: successCallback
        });
    }

    $("#form_crud").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');
        var opname_detail_id = $("#opname_detail_id").val();
        form.find("button[type='submit']").prop('disabled', true);
        performAjaxRequest(actionUrl, form.serialize(), function(msg) {
            if(msg==''){
                $("#form_modul").modal('hide');
                performAjaxRequest('opname-table-material-sn', {'opname_detail_id' : opname_detail_id}, function(msg) {
                    $('#table_sn_material').html(msg);
                });
            }
            else{
                $("#error_msg").html(msg);
                form.find("button[type='submit']").prop('disabled', false);
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>