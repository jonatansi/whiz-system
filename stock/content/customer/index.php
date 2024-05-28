<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Pelanggan</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-dark me-2 btn-b btnAdd"><i class="mdi mdi-plus-circle"></i> Tambah Pelanggan</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Data Pelanggan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="my_datatable">
                        <thead class="table-info text-center">
                            <tr>
                                <th width="50px">No</th>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            $tampil=mysqli_query($conn,"SELECT a.* FROM master_customer a WHERE a.master_cabang_id='$_SESSION[master_cabang_id]' AND a.deleted_at IS NULL ORDER BY a.kode ASC");
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $r['kode'];?></td>
                                    <td><?php echo $r['nama'];?></td>
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
echo generate_javascript_action("btnAdd", "customer-tambah");
echo generate_javascript_action("btnEdit", "customer-edit");
echo generate_javascript_action("btnDelete", "customer-delete");

echo general_default_datatable();
?>
</script>

<?php
if(isset($_GET['message'])){
    if($_GET['message']=='add'){
        $pesan = "Berhasil menambahkan data pelanggan";
    }
    else if($_GET['message']=='edit'){
        $pesan = "Berhasil memperbaharui data pelanggan";
    }
    else if($_GET['message']=='delete'){
        $pesan = "Berhasil menghapus data pelanggan";
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