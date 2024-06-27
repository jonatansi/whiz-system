<?php
$pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));
?>
<form action="dismantle-tambah" method="GET" enctype="multipart/form-data" id="form_crud">
	<div class="modal-dialog modal-md a-lightSpeed">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-standard-title">Dismantle Material</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="form-data">
                <div class="form-group">
                    <label class="mb-2">Kategori Penggunaan <span class="text-danger">*</span></label>
                    <select name="master_guna_id" class="form-control" required id="master_guna_id">
                     <?php
                        $tampil=mysqli_query($conn, "SELECT * FROM master_guna ORDER BY nama");
                        while($r=mysqli_fetch_array($tampil)){
                            echo "<option value='$r[id]'>$r[kode] - $r[nama]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-2">
                        User Identity <small class="text-danger">*</small>
                    </label>
                    <select name="master_guna_kategori_id" class="form-control select2" required id="master_guna_kategori_id"  style="width:100%">
                        
                    </select>
                </div>
			</div>
			<div class="modal-footer p-2">
                <button type="submit" class="btn btn-success has-ripple"><i class="bi bi-check"></i> Buat Dismantle</button>
                <button type="button" class="btn btn-danger has-ripple"  data-bs-dismiss="modal"><i class="bi bi-x"></i> Tutup</button>
			</div>
		</div>
	</div>
</form>
<?php
echo form_select2();
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Function to handle AJAX requests
        function sendAjaxRequest(type, url, data, targetElement) {
            $.ajax({
                type: type,
                url: url,
                data: data,
                beforeSend: function() {
                    $('.preloader').show();
                    $(targetElement).html("Loading...");
                },
                complete: function() {
                    $('.preloader').hide();
                },
                success: function(response) {
                    $(targetElement).html(response);
                }
            });
        }

        // Initial AJAX request for master kategori
        var masterGunaId = $("#master_guna_id").val();
        sendAjaxRequest('POST', 'dismantle-useridentity', { 'master_guna_id': masterGunaId }, '#master_guna_kategori_id');

        // Event handler for change event on master_guna_id
        $("#master_guna_id").change(function() {
            var masterGunaId = $(this).val();
            sendAjaxRequest('POST', 'dismantle-useridentity', { 'master_guna_id': masterGunaId }, '#master_guna_kategori_id');
        });
    });
    $('form').submit(function(){
        $(this).find(':submit').attr('disabled','disabled');
    });
</script>