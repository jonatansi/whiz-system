<?php
if(isset($_GET['tanggal_awal'])){
    $tanggal_awal=$_GET['tanggal_awal'];
    $tanggal_akhir=$_GET['tanggal_akhir'];
}
else{
    $tanggal_awal = date('Y-m-01');
	$tanggal_akhir = date('Y-m-d');
}
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Log Aktivitas</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
            </ol>
        </nav>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" name="tanggal_awal" value="<?php echo $tanggal_awal;?>" class="form-control" id="tanggal_awal" max="<?php echo $tgl_sekarang;?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" name="tanggal_akhir" value="<?php echo $tanggal_akhir;?>" class="form-control" id="tanggal_akhir" max="<?php echo $tgl_sekarang;?>"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-dark" id="btnFilter"><i class="fa fa-search mr-2 "></i> Filter</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Data Log Aktivitas
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="datatable_ajax">
                        <thead class="table-info text-center">
                            <tr>
                                <th width="50px">No</th>
                                <th>Waktu</th>
                                <th>Modul</th>
                                <th>Tabel</th>
                                <th>ID Data</th>
                                <th>Aksi</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// $order_column_add = datatable_column("-2", "text-center", "false");
$order_column_add = "";
$disabled_column_serch_add = datatable_column_search_disabled(0);

$filter = datatable_filter("tanggal_awal");
$filter.= datatable_filter("tanggal_akhir");

echo generate_datatable("user-logactivity-data", "1", "desc", $order_column_add, $disabled_column_serch_add, $filter);
?>