<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Branch</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-dark me-2 btn-b btnAdd"><i class="mdi mdi-plus-circle"></i> Tambah Branch</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Data Branch
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="my_datatable">
                        <thead class="table-info text-center">
                            <tr>
                                <th width="50px">No</th>
                                <th>ID Branch</th>
                                <th>Branch</th>
                                <th>Alamat</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_provinsi, c.nama AS nama_kabupaten, d.nama AS nama_kecamatan, e.nama AS nama_kelurahan FROM master_cabang a
                            LEFT JOIN lok_provinsi b ON a.lok_provinsi_id=b.id
                            LEFT JOIN lok_kabupaten c ON a.lok_kabupaten_id=c.id
                            LEFT JOIN lok_kecamatan d oN a.lok_kecamatan_id=d.id
                            LEFT JOIN lok_kelurahan e ON a.lok_kelurahan_id=e.id
                            WHERE a.deleted_at IS NULL ORDER BY a.updated_at DESC");
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $r['kode'];?></td>
                                    <td><?php echo $r['nama'];?></td>
                                    <td>
                                        <?php 
                                            echo $r['alamat'].'<br>Kel. '.$r['nama_kelurahan'].', Kec. '.$r['nama_kecamatan'].', '.$r['nama_kabupaten'].', '.$r['nama_provinsi'].'<br>Kode POS : '.$r['kode_pos'];
                                        ?>
                                    </td>
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

<script type="text/javascript" src="<?php echo $BASE_URL_MASTER;?>/addons/js/branch.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL_MASTER;?>/addons/js/datatable.js"></script>

<?php
if(isset($_GET['message'])){
    if($_GET['message']=='add'){
        $pesan = "Berhasil menambahkan data branch";
    }
    else if($_GET['message']=='edit'){
        $pesan = "Berhasil memperbaharui data branch";
    }
    else if($_GET['message']=='delete'){
        $pesan = "Berhasil menghapus data branch";
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