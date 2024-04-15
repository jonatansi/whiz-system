<?php
$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));
?>
<form action="terimapo-tambah" method="GET" enctype="multipart/form-data" id="form_crud">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Penerimaan Material</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="form-group">
                    <label class="mb-2">Nomor PO <span class="text-danger">*</span></label>
                    <select name="po_id" class="form-control" required>
                        <?php
                        $tampil=mysqli_query($conn,"SELECT * FROM po WHERE deleted_at IS NULL AND request_master_cabang_id='$pegawai[master_cabang_id]' AND (status_id='15' OR status_id='20')");
                        while($r=mysqli_fetch_array($tampil)){
                            echo "<option value='$r[id]'>$r[nomor]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-2">Tanggal<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal" max="<?php echo $tgl_sekarang;?>">
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Buat Penerimaan</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Tutup</button>
			</div>
		</div>
	</div>
</form>