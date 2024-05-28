<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_guna_kategori WHERE id='$_POST[id]'"));
?>
<form action="katguna-update" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="id" value="<?php echo $d['id'];?>">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Edit</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
				<div class="form-group">
						<label>Kategori Penggunaan<span class="text-danger">*</span></label>
						<select name="master_guna_id" class="form-control" required>
							<?php
							$tampil=mysqli_query($conn,"SELECT * FROM master_guna WHERE type='2'");
							while($r=mysqli_fetch_array($tampil)){
								if($r['id']==$d['master_guna_id']){
									echo"<option value='$r[id]' selected>$r[nama]</option>";
								}
								else{
									echo"<option value='$r[id]'>$r[nama]</option>";
								}
							}
							?>
						</select>
					</div>
                <div class="form-group">
                    <label>Nama Kategori Material <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="<?php echo $d['nama'];?>" name="nama" required>
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
echo form_modal_js("katguna?message=edit");
?>