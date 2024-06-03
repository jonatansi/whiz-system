<form action="opname-input-material" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="opname_id" value="<?php echo $_POST['id'];?>" id="opname_id">
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
                                <label>Merk / Type <span class="text-danger">*</span></label>
                                <select name="master_material_id" class="form-control select2" id="material_id" required>
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Kondisi Pembelian Awal <span class="text-danger">*</span></label>
                                <select name="master_kondisi_id" class="form-control select2"required id="master_kondisi_id">
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
                                <label>Jumlah Aktual <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control rupiah" name="jumlah_aktual" required placeholder="Jumlah Aktual">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="master_satuan_kecil_id" class="form-control select2" id="master_satuan_kecil_id">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Remark </label>
                                <input type="text" class="form-control" name="remark">
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
        var opname_id = $("#opname_id").val();
        form.find("button[type='submit']").prop('disabled', true);
        performAjaxRequest(actionUrl, form.serialize(), function(msg) {
            if(msg==''){
                $("#form_modul").modal('hide');
                performAjaxRequest('opname-table-material-view', {'opname_id' : opname_id}, function(msg) {
                    $('#table_view_material').html(msg);
                });
            }
            else{
                $("#error_msg").html(msg);
                form.find("button[type='submit']").prop('disabled', false);
            }
        });
    });

    $(document).ready( function () {
        loadMaterialOptions(0);
    });

    function loadMaterialOptions(kategori_material_id) {
        $.ajax({
            type: 'POST',
            url: "data-material",
            cache: false,
            data: { 'kategori_material_id': kategori_material_id },
            success: function(data) {
                $("#material_id").html(data);
                loadSatuanMaterialOptions($("#material_id").val());
            }
        });
    }

    function loadSatuanMaterialOptions(material_id) {
        $.ajax({
            type: 'POST',
            url: "data-satuan-material",
            cache: false,
            data: { 'material_id': material_id },
            success: function(data) {
                $("#master_satuan_kecil_id").html(data);
            }
        });
    }

    $("#material_id").change(function() {
        var material_id = $("#material_id").val();
        loadSatuanMaterialOptions(material_id);
    });


</script>

<script type="text/javascript" src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>
<?php
echo form_select2();
?>