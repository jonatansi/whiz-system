<form action="user-input" method="POST" enctype="multipart/form-data" id="form_crud">
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
                            <label>Branch <span class="text-danger">*</span></label>
                            <select name="master_cabang_id" class="form-control select2" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_cabang WHERE deleted_at IS NULL ORDER BY nama ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo"<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="nama" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="username" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" value="" name="password" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" value="" name="email" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>No. Handphone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mob_no" value="" name="no_handphone" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="" name="jabatan" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Level <span class="text-danger">*</span></label>
                            <select name="level_id" class="form-control" required>
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM pegawai_level");
                                while($r=mysqli_fetch_array($tampil)){
                                    echo "<option value='$r[id]'>$r[nama]</option>";
                                }
                                ?>
                            </select>
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
echo form_modal_js("user?message=add");
echo form_select2();
?>
<script src="<?php echo $BASE_URL_MASTER;?>/addons/js/location_general.js"></script>