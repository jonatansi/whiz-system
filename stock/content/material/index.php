<?php
if(isset($_GET['act'])==''){
?>
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Data Material</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Material</li>
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
                    <div class="table-responsive">
                        <table class="table" id="datatable_ajax">
                            <thead class="table-info text-center">
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Serial Number</th>
                                    <th class="text-center">Kategori</th>
                                    <th>Merk/Type</th>
                                    <th>Pembelian Awal</th>
                                    <th>Gudang</th>
                                    <th>Cabang</th>
                                    <th>Harga</th>
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
    $order_column_add.= datatable_column("-1", "text-center", "true");
    
    $disabled_column_serch_add = datatable_column_search_disabled(0);

    
    echo generate_datatable("material-data", "1", "asc", $order_column_add, $disabled_column_serch_add, '', "datatable_ajax");

}
else if($_GET['act']=='view'){
    include "view.php";
}
?>