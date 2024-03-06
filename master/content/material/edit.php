<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_material WHERE id='$_POST[id]'"));
?>
<form action="material-update" method="POST" enctype="multipart/form-data" id="form_crud">
    <input type="hidden" name="id" value="<?php echo $d['id'];?>">
	<div class="modal-dialog modal-lg a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Edit</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>ID Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['kode'];?>" name="kode" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="master_kategori_material_id" class="form-control select2" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_kategori_material ORDER BY nama ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['master_kategori_material_id']){
                                        echo"<option value='$r[id]' selected>$r[nama]</option>";
                                    }
                                    else{
                                        echo"<option value='$r[id]'>$r[nama]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="form-group">
                            <label>Merk / Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['merk_type'];?>" name="merk_type" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Satuan <span class="text-danger">*</span></label>
                            <select name="master_satuan_id" class="form-control select2" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM master_satuan ORDER BY nama ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['master_satuan_id']){
                                        echo"<option value='$r[id]' selected>$r[nama]</option>";
                                    }
                                    else{
                                        echo"<option value='$r[id]'>$r[nama]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" value="<?php echo $d['remark'];?>" name="remark">
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
echo form_modal_js("material?message=edit");
echo form_select2();
?>