<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM opname_detail WHERE id='$_POST[id]' AND deleted_at IS NULL"));
?>
<form action="opname-update-material" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="id" value="<?php echo $d['id'];?>">
    <input type="hidden" name="opname_id" value="<?php echo $d['opname_id'];?>">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Edit Jumlah Aktual</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Jumlah Aktual <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah" name="jumlah" required placeholder="" value="<?php echo formatAngka($d['jumlah_aktual']);?>">
                    </div>
                </div>
                            
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Remark </label>
                        <textarea name="remark" class="form-control"></textarea>
                    </div>
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple" id="btnSubmit"><i class="bi bi-check"></i> Simpan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Batal</button>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
    $('form').submit(function(){
        $(this).find(':submit').attr('disabled','disabled');
    });
</script>