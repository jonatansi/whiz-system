<?php
$so =  mysqli_fetch_array(mysqli_query($conn,"SELECT status_id FROM opname WHERE id='$_POST[opname_id]' AND deleted_at IS NULL"));      
?>
<table class="table" id="my_datatable">
    <thead class="table-info text-center">
        <tr>
            <th width="50px">No</th>
            <th>Kategori</th>
            <th>Merk/Type</th>
            <th>Jlh Tercatat</th>
            <th>Jlh Aktual</th>
            <th>Total SN</th>
            <th>Satuan</th>
            <th>Kondisi</th>
            <th>Remark</th>
            <?php
            if($so['status_id']!='365' AND $s['status_id']!='355'){
                echo "<th>Aksi</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $tampil=mysqli_query($conn,"SELECT a.id, a.jumlah_tercatat, a.stok_kondisi_id, a.jumlah_aktual, a.remark, b.merk_type, c.nama AS nama_kategori_material, d.nama AS nama_satuan_kecil, e.nama AS nama_kondisi, (SELECT COUNT(f.id) FROM opname_sn f WHERE a.id=f.opname_detail_id AND f.material_sn_status_id='500') AS total_sn 
        FROM opname_detail a
        LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
        LEFT JOIN master_kategori_material c ON b.master_kategori_material_id=c.id AND c.deleted_at IS NULL
        LEFT JOIN master_satuan d ON a.master_satuan_kecil_id=d.id AND d.deleted_at IS NULL
        LEFT JOIN master_kondisi e ON a.master_kondisi_id=e.id
        WHERE a.opname_id='$_POST[opname_id]' AND a.deleted_at IS NULL");
        $no=1;
        while($r=mysqli_fetch_array($tampil)){
            ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $r['nama_kategori_material'];?></td>
                <td><?php echo $r['merk_type'];?></td>
                <td class="text-center"><?php echo formatAngka($r['jumlah_tercatat']);?></td>
                <td class="text-center"><?php echo formatAngka($r['jumlah_aktual']);?></td>
                <td class="text-center"><a href="opname-sn-<?php echo $r['id'];?>" class="text-primary" target="_blank"><?php echo formatAngka($r['total_sn']);?></a></td>
                <td><?php echo $r['nama_satuan_kecil'];?></td>
                <td><?php echo $r['nama_kondisi'];?></td>
                <td><?php echo $r['remark'];?></td>
                <?php
                if($so['status_id']!='365' AND $s['status_id']!='355'){
                ?>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-warning btnEdit" data-toggle="tooltip" data-placement="top" title="Edit" id="<?php echo $r['id'];?>"><i class="bi bi-pen"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>" <?php if($r['stok_kondisi_id']!='0'){echo "disabled";}?>><i class="bi bi-trash"></i></button>
                </td>
                <?php
                }
                ?>
            </tr>
            <?php
            $no++;
        }
        ?>
    </tbody>
</table>
<input type="hidden" name="opname_id" value="<?php echo $_POST['opname_id'];?>" id="opname_id">

<script type="text/javascript"> 
   $('#my_datatable tbody').on('click', '.btnDelete', async function() {
    const id = this.id;
    const opname_id = $("#opname_id").val();

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
            await reloadMaterialTable(opname_id);
        } catch (error) {
            console.error('Error:', error);
        }
    }
});

async function deleteMaterial(id) {
    try {
        await $.ajax({
            type: 'POST',
            url: 'opname-delete-material',
            data: { 'id': id }
        });
    } catch (error) {
        throw new Error('Failed to delete material');
    }
}

async function reloadMaterialTable(opname_id) {
    try {
        $('.preloader').show();
        $('#table_view_material').html("Loading...");

        const msg = await $.ajax({
            type: 'POST',
            url: 'opname-table-material-view',
            data: { 'opname_id': opname_id }
        });

        $('#table_view_material').html(msg);
    } catch (error) {
        console.error('Error:', error);
    } finally {
        $('.preloader').hide();
    }
}

<?php
echo generate_javascript_action("btnEdit", "opname-edit-material");
echo general_default_datatable();
?>
</script>