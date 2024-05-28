<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h4 class="mb-0">Hi, <b><?php echo $pegawai['nama'];?></b>. Welcome back!</h4>
        <p class="mb-0 text-muted">Dashboard</p>
    </div>
   
</div>

<div class="row">
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-primary-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">BRANCH</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                                <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM master_cabang WHERE deleted_at IS NULL"));
                                echo $c['tot'];
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div id="compositeline"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-warning-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">TOTAL TRANSAKSI PO</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                            <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM po WHERE deleted_at IS NULL AND status_id='25'"));
                                echo $c['tot'];
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div id="compositeline4"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-danger-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">TOTAL ITEM</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                                <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(jumlah) AS tot FROM stok WHERE deleted_at IS NULL"));
                                echo formatAngka($c['tot']);
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div id="compositeline2"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-success-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">TOTAL NILAI ASET</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                                <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(harga) AS tot FROM material_sn WHERE status_id IN (500,501)"));
                                echo formatAngka($c['tot']);
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div id="compositeline3"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="purchase_order"></div>
            </div>
        </div>
    </div>
</div>

<?php
$branch = "";
$tampil=mysqli_query($conn,"SELECT id, nama FROM master_cabang WHERE deleted_at IS NULL ORDER BY nama");
while($r=mysqli_fetch_array($tampil)){
    $branch.="'$r[nama]',";
}

$series_data="";
$status = mysqli_query($conn,"SELECT id, nama FROM master_status WHERE remark='PO'");
while($r=mysqli_fetch_array($status)){

    $branch_data = "[";
    $data = mysqli_query($conn,"SELECT a.nama, COUNT(b.id) AS total FROM master_cabang a LEFT JOIN po b ON a.id=b.request_master_cabang_id AND b.status_id='$r[id]' AND b.deleted_at IS NULL WHERE a.deleted_at IS NULL GROUP BY a.nama ORDER BY a.nama");
    while($d=mysqli_fetch_array($data)){
        $branch_data.="$d[total],"; 
    }
    $branch_data.="]";

    $series_data.= "{
        name: '$r[nama]',
        data: $branch_data
    },";
}

// echo $series_data;
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
    Highcharts.chart('purchase_order', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Purchase Order Semua Branch',
        align: 'left'
    },
    subtitle: {
        text:
            'UV - Tel',
        align: 'left'
    },
    xAxis: {
        categories: [<?php echo $branch;?>],
        crosshair: true,
        accessibility: {
            description: 'Branch'
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total PO'
        }
    },
    tooltip: {
        valueSuffix: ''
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
       <?php echo $series_data;?>
    ]
});

</script>
