<table class="table" id="my_datatable">
    <thead class="table-info text-center">
        <tr>
            <th width="50px">No</th>
            <th>Kategori</th>
            <th>Merk/Type</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Kondisi</th>
            <th>Harga Satuan</th>
            <th>Jumlah Harga</th>
            <th width="60px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tampil=mysqli_query($conn,"SELECT a.*, b.merk_type, c.nama AS nama_kondisi, d.nama AS nama_kategori_material, e.nama AS nama_satuan_besar FROM po_detail a
        LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
        LEFT JOIN master_kondisi c ON a.master_kondisi_id=c.id AND c.deleted_at IS NULL
        LEFT JOIN master_kategori_material d ON a.master_kategori_material_id=d.id AND d.deleted_at IS NULL
        LEFT JOIN master_satuan e ON a.master_satuan_besar_id=e.id AND e.deleted_at IS NULL
        WHERE a.deleted_at IS NULL AND a.po_id IS NULL AND a.created_pegawai_id='$_SESSION[login_user]'");
        $no=1;
        while($r=mysqli_fetch_array($tampil)){
            ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $r['nama_kategori_material'];?></td>
                <td><?php echo $r['merk_type'];?></td>
                <td class="text-center"><?php echo formatAngka($r['jumlah']);?></td>
                <td class="text-center"><?php echo $r['nama_satuan_besar'];?></td>
                <td class="text-center"><?php echo $r['nama_kondisi'];?></td>
                <td class="text-end"><?php echo formatAngka($r['harga']);?></td>
                <td class="text-end"><?php echo formatAngka($r['jumlah']*$r['harga']);?></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </tbody>
</table>

<script type="text/javascript"> 
    $('#my_datatable tbody').on('click', '.btnDelete',function() {
        var id = this.id;
        
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success ms-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: 'You will not be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'po-delete-material',
                    data:{
                        'id' : id
                    },
                    success: function() {
                        $.ajax({
                            type: 'POST',
                            url: 'po-table-material-add',
                            beforeSend: function() {
                                $('.preloader').show();
                                $('#table_add_material').html("Loading...");
                            },
                            complete: function() {
                                $('.preloader').hide();
                            },
                            success: function(msg) {
                                $('#table_add_material').html(msg);
                            }
                        });
                    }
                });
            }
        })
    });

<?php

echo general_default_datatable();
?>
</script>