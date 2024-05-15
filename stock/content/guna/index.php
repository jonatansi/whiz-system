<?php
if(isset($_GET['act'])==''){
if(isset($_GET['tanggal_awal'])){
    $tanggal_awal=$_GET['tanggal_awal'];
    $tanggal_akhir=$_GET['tanggal_akhir'];
}
else{
    $tanggal_awal = date('Y-01-01');
	$tanggal_akhir = date('Y-m-d');
}
?>
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Penggunaan Material</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Penggunaan Material</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <?php
                if($pegawai['master_cabang_id']!='1'){
                ?>
                    <a href="guna-tambah"><button type="button" class="btn btn-dark me-2 btn-b btnAdd"><i class="mdi mdi-plus-circle"></i> Buat Penggunaan Baru</button></a>
                <?php
                }
                ?>
            </div>
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
                                <label class="">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" value="<?php echo $tanggal_awal;?>" class="form-control" id="tanggal_awal" max="<?php echo $tgl_sekarang;?>"/>
                            </div>
                            <div class="col-md-3">
                                <label class="">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" value="<?php echo $tanggal_akhir;?>" class="form-control" id="tanggal_akhir" max="<?php echo $tgl_sekarang;?>"/>
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status_id" class="form-control" id="status_id">
                                    <option value="0">Semua</option>
                                    <?php
                                    $tampil=mysqli_query($conn,"SELECT * FROM master_status WHERE remark='GunaMaterial'");
                                    while($r=mysqli_fetch_array($tampil)){
                                        echo "<option value='$r[id]'>$r[nama]</option>";
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
                                    <th class="text-center">Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Branch</th>
                                    <th>Penggunaan</th>
                                    <th>Total Item</th>
                                    <th>Total SN Diinput</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 

    $order_column_add = datatable_column("4", "text-center", "true");
    $order_column_add.= datatable_column("-3", "text-end", "true");
    $order_column_add.= datatable_column("-2", "text-end", "true");
    $order_column_add.= datatable_column("-1", "text-center", "false");
    
    $disabled_column_serch_add = datatable_column_search_disabled(0);
    $disabled_column_serch_add.= datatable_column_search_disabled(7);

    $filter = datatable_filter("tanggal_awal");
    $filter.= datatable_filter("tanggal_akhir");
    $filter.= datatable_filter("status_id");
    
    echo generate_datatable("guna-data", "1", "desc", $order_column_add, $disabled_column_serch_add, $filter, "datatable_ajax");
    ?>

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
else if($_GET['act']=='sn'){
    include "serial_number.php";
}
?>