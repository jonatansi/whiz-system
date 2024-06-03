<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT status_id, po_id FROM po_terima WHERE id='$_POST[id]'"));
if($d['status_id']=='200'){
    $next_status_id = 210;
    $title = "Completed Penerimaan PO";
}
?>
<form action="terimapo-next-action" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="next_status_id" value="<?php echo $next_status_id;?>">
    <input type="hidden" name="po_terima_id" value="<?php echo $_POST['id'];?>">
    <input type="hidden" name="po_id" value="<?php echo $d['po_id'];?>">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title"><?php echo $title;?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="form-group">
                    <label class="mb-2">Dokumen Pendukung <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="dokumen" required accept="application/pdf, image/*">
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan / Catatan <span class="text-danger">*</span></label>
                    <textarea name="remark" class="form-control" required></textarea>
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Simpan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Tutup</button>
			</div>
		</div>
	</div>
</form>