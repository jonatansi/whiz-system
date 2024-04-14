<form action="katmaterial-input" method="POST" enctype="multipart/form-data" id="form_crud">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Tambah</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
				<div class="col-md-12 mb-2">
					<div class="form-group">
						<label>ID Kategori <span class="text-danger">*</span></label>
						<input type="text" class="form-control text-uppercase" value="" name="kode" required maxlength="3" pattern="[A-Za-z]+" placeholder="Hanya huruf" oninput="this.value = this.value.replace(/[^A-Za-z]/g, '');">
					</div>
				</div>
				
                <div class="form-group">
                    <label>Nama Kategori Material <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="nama" required>
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Simpan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Batal</button>
			</div>
		</div>
	</div>
</form>
<?php
echo form_modal_js("katmaterial?message=add");
?>