<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM master_vendor WHERE id='$_POST[id]'"));
?>
<form action="vendor-update" method="POST" enctype="multipart/form-data" id="form_crud">
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
                            <label>ID Vendor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" value="<?php echo $d['kode'];?>" name="kode" required maxlength="7">
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>NPWP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control npwp" value="<?php echo $d['npwp'];?>" name="npwp" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="form-group">
                            <label>Nama Vendor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['nama'];?>" name="nama" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Provinsi <span class="text-danger">*</span></label>
                            <select name="lok_provinsi_id" class="form-control select2" id="id_propinsi" style="width:100%">
                                <option value="">Pilih Propinsi</option>
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM lok_provinsi ORDER BY id ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['lok_provinsi_id']){
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
                            <label>Kabupaten/Kota <span class="text-danger">*</span></label>
                            <select name="lok_kabupaten_id" class="form-control select2" id="id_kabupaten" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM lok_kabupaten WHERE lok_provinsi_id='$d[lok_provinsi_id]' ORDER BY id ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['lok_kabupaten_id']){
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
                            <label>Kecamatan <span class="text-danger">*</span></label>
                            <select name="lok_kecamatan_id" class="form-control select2" id="id_kecamatan" style="width:100%">
                                <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM lok_kecamatan WHERE lok_kabupaten_id='$d[lok_kabupaten_id]' ORDER BY id ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['lok_kecamatan_id']){
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
                            <label>Kelurahan <span class="text-danger">*</span></label>
                            <select name="lok_kelurahan_id" class="form-control select2" id="id_kelurahan" style="width:100%">
                            <?php
                                $tampil=mysqli_query($conn,"SELECT * FROM lok_kelurahan WHERE lok_kecamatan_id='$d[lok_kecamatan_id]' ORDER BY id ASC");
                                while($r=mysqli_fetch_array($tampil)){
                                    if($r['id']==$d['lok_kelurahan_id']){
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
                            <label>Alamat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['alamat'];?>" name="alamat" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['kode_pos'];?>" name="kode_pos" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>PIC Sales <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $d['sales_pic'];?>" name="sales_pic" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>No. HP Sales <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mob_no"  value="<?php echo $d['sales_hp'];?>" name="sales_hp" required>
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
echo form_modal_js("vendor?message=add");
echo form_select2();
?>
<script src="<?php echo $BASE_URL_MASTER;?>/addons/js/location_general.js"></script>
<script src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>