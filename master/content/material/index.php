<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Material</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-dark me-2 btn-b btnAdd"><i class="mdi mdi-plus-circle"></i> Tambah Material</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Data Material
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="my_datatable">
                        <thead class="table-info text-center">
                            <tr>
                                <th width="50px">No</th>
                                <th>Tgl/Jam</th>
                                <th>ID Material</th>
                                <th>Kategori</th>
                                <th>Merk / Type</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_kategori, c.nama AS nama_satuan FROM master_material a
                            LEFT JOIN master_kategori_material b ON a.master_kategori_material_id=b.id
                            LEFT JOIN master_satuan c ON a.master_satuan_id=c.id
                            WHERE a.deleted_at IS NULL ORDER BY a.updated_at DESC");
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo dateFormat($r['updated_at']);?></td>
                                    <td><?php echo $r['kode'];?></td>
                                    <td><?php echo $r['nama_kategori'];?></td>
                                    <td><?php echo $r['merk_type'];?></td>
                                    <td><?php echo $r['nama_satuan'];?></td>
                                    <td><?php echo $r['remark'];?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary btnEdit" data-toggle="tooltip" data-placement="top" title="Edit" id="<?php echo $r['id'];?>"><i class="bi bi-pen"></i> Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger btnDelete" data-toggle="tooltip" data-placement="top" title="Hapus" id="<?php echo $r['id'];?>"><i class="bi bi-trash"></i> Delete</button>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php
echo generate_javascript_action("btnAdd", "material-tambah");
echo generate_javascript_action("btnEdit", "material-edit");
echo generate_javascript_action("btnDelete", "material-delete");

echo general_default_datatable();
?>
</script>

<?php
if(isset($_GET['message'])){
    if($_GET['message']=='add'){
        $pesan = "Berhasil menambahkan data material";
    }
    else if($_GET['message']=='edit'){
        $pesan = "Berhasil memperbaharui data material";
    }
    else if($_GET['message']=='delete'){
        $pesan = "Berhasil menghapus data material";
    }
    ?>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#successToast').toast('show');
            $("#successToastBody").html("<?php echo $pesan;?>");
        });
    </script>
    <?php
}
?>