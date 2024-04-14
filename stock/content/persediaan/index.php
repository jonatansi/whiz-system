<?php
if(isset($_GET['act'])==''){
$sql_cabang="SELECT * FROM master_cabang WHERE deleted_at IS NULL AND id='$pegawai[master_cabang_id]'";
if($pegawai['master_cabang_id']=='1'){
    $sql_cabang="SELECT * FROM master_cabang WHERE deleted_at IS NULL ORDER BY nama";
}

$d=mysqli_fetch_array(mysqli_query($conn,$sql_cabang));
?>
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Persediaan Material</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Persediaan Material</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <fieldset class="mb-3">
                        <legend>Filter Data</legend>
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <label class="">Kategori</label>
                                <select name="master_kategori_material_id" class="form-control select2" id="master_kategori_material_id">
                                    <option value="0">Semua</option>
                                    <?php
                                    $tampil=mysqli_query($conn,"SELECT * FROM master_kategori_material WHERE deleted_at IS NULL ORDER BY nama");
                                    while($r=mysqli_fetch_array($tampil)){
                                        echo"<option value='$r[id]'>$r[nama]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cabang</label>
                                <select name="master_cabang_id" class="form-control" id="master_cabang_id">
                                    <?php
                                    $tampil=mysqli_query($conn,$sql_cabang);
                                    while($r=mysqli_fetch_array($tampil)){
                                        echo"<option value='$r[id]'>$r[kode] - $r[nama]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Penyimpanan</label>
                                <select name="master_gudang_id" class="form-control" id="master_gudang_id">
                                    <option value="0">Semua</option>
                                    <?php
                                        $tampil=mysqli_query($conn,"SELECT * FROM master_gudang WHERE master_cabang_id='$d[id]' AND deleted_at IS NULL");
                                        while($r=mysqli_fetch_array($tampil)){
                                            echo"<option value='$r[id]'>$r[kode] - $r[nama]</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="btnFilter"><i class="fa fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </fieldset>
                    <div class="table-responsive">
                        <table class="table" id="datatable_ajax">
                            <thead class="table-info text-center">
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Kategori</th>
                                    <th>Merk/Type</th>
                                    <th>Jlh Stok</th>
                                    <th>Satuan</th>
                                    <th>Penyimpanan</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // $order_column_add = datatable_column("6", "text-center", "false");
    // $order_column_add = datatable_column("-1", "text-end", "false");
    $order_column_add="";
    $disabled_column_serch_add = datatable_column_search_disabled(0);
    // $disabled_column_serch_add.= datatable_column_search_disabled(6);
    // $disabled_column_serch_add.= datatable_column_search_disabled(7);
    // $disabled_column_serch_add.= datatable_column_search_disabled(8);

    $filter = datatable_filter("master_kategori_material_id");
    $filter.= datatable_filter("master_gudang_id");
    $filter.= datatable_filter("master_cabang_id");
    
    echo generate_datatable("persediaan-data", "1", "asc", $order_column_add, $disabled_column_serch_add, $filter);
    ?>

    <script type="text/javascript">
        function loadGudangOptions(master_cabang_id) {
        $.ajax({
            type: 'POST',
            url: "data-gudang",
            cache: false,
            data: { 'master_cabang_id': master_cabang_id },
            success: function(data) {
                $("#master_gudang_id").html(data);
            }
        });

        $("#material_cabang_id").change(function() {
            var material_cabang_id = $("#material_cabang_id").val();
            loadGudangOptions(material_id);
        });
    }
    </script>
    <?php
    if(isset($_GET['message'])){
        if($_GET['message']=='add'){
            $pesan = "Berhasil menambahkan data user";
        }
        else if($_GET['message']=='edit'){
            $pesan = "Berhasil memperbaharui data user";
        }
        else if($_GET['message']=='delete'){
            $pesan = "Berhasil menghapus data user";
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
}
else if($_GET['act']=='tambah'){
    include "add/index.php";
}
else if($_GET['act']=='view'){
    include "view.php";
}
?>