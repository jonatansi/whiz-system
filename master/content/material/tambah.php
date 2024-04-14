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
                            <label>Satuan Dasar<span class="text-danger">*</span></label>
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
                            <label>Minimum stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" value="" name="minimum_stok" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
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
<?php
echo form_modal_js("material?message=add");
echo form_select2();
?>