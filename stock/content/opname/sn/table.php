<div class="table table-responsive">
    <table class="table table-sm" id="my_datatable">
        <thead class="table-info text-center">
            <tr>
                <th width="100">No</th>
                <th>Serial Number</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Remark</th>
                <th width="100px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=1;
            $tampil=mysqli_query($conn,"SELECT a.*, c.nama AS nama_status, c.warna AS warna_status FROM opname_sn a 
            LEFT JOIN material_sn b ON a.material_sn_id=b.id 
            LEFT JOIN master_status c ON a.material_sn_status_id=c.id
            WHERE a.opname_detail_id='$_POST[opname_detail_id]'");
            while($r=mysqli_fetch_array($tampil)){
                $status="<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $r['serial_number'];?></td>
                    <td><?php echo formatAngka($r['harga']);?></td>
                    <td><?php echo $status;?></td>
                    <td><?php echo $r['remark'];?></td>
                    <td>
                        <?php
                        if($r['material_sn_id']!='0' AND $r['status']=='1'){ 
                        ?>
                            <button type="button" class="btn btn-sm btn-warning btnEdit" data-toggle="tooltip" data-placement="top" title="Edit" id="<?php echo $r['id'];?>"><i class="bi bi-pen"></i></button>
                        <?php
                        }
                        if($r['material_sn_id']=='0' AND $r['status']=='1'){
                        ?>
                        <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>"><i class="bi bi-trash"></i></button>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<input type="hidden" name="opname_detail_id" value="<?php echo $_POST['opname_detail_id'];?>" id="opname_detail_id">

<script type="text/javascript"> 
   $('#my_datatable tbody').on('click', '.btnDelete', async function() {
    const id = this.id;
    const opname_detail_id = $("#opname_detail_id").val();

    const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    });

    if (result.isConfirmed) {
        try {
            await deleteMaterial(id);
            await reloadMaterialTable(opname_detail_id);
        } catch (error) {
            console.error('Error:', error);
        }
    }
});

async function deleteMaterial(id) {
    try {
        await $.ajax({
            type: 'POST',
            url: 'opname-sn-delete',
            data: { 'id': id }
        });
    } catch (error) {
        throw new Error('Failed to delete serial number '+error);
    }
}

async function reloadMaterialTable(opname_detail_id) {
    try {
        $('.preloader').show();
        $('#table_sn_material').html("Loading...");

        const msg = await $.ajax({
            type: 'POST',
            url: 'opname-table-material-sn',
            data: { 'opname_detail_id': opname_detail_id }
        });

        $('#table_sn_material').html(msg);
    } catch (error) {
        console.error('Error:', error);
    } finally {
        $('.preloader').hide();
    }
}

<?php
echo generate_javascript_action("btnEdit", "opname-sn-edit");
echo general_default_datatable();
?>
</script>