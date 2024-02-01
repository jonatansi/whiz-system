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
                    <table class="table" id="logactivity">
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


<script type="text/javascript">
$(document).ready( function () {
    var tanggal_awal = $("#tanggal_awal").val();
    var tanggal_akhir = $("#tanggal_akhir").val();

    var table = $('#logactivity').DataTable( {
        "processing": true,
        "serverSide": true,
        "order": [[ 0, 'desc' ]],
        "lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
        "ajax": {
            "url": "user-logactivity-data",
            "data": function(d) {
                d.tanggal_awal = $("#tanggal_awal").val();;
                d.tanggal_akhir = $("#tanggal_akhir").val();
            },
            "type": "POST"
        },
        'columnDefs': [
            {
                "targets": 0, // your case first column
                "className": "text-center",
                "width": "50px"
            },
            {
                "targets": 5, // your case first column
                "className": "text-center",
            }
        ],
        "bDestroy": true,
        dom :"<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'excel', 'colvis'
        ]
    });

    $("#btnFilter").click(function() {
        table.ajax.reload();
    });
});
</script>