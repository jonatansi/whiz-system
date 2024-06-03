<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT nama FROM master_cabang WHERE id='$_SESSION[master_cabang_id]'"));
$nama_cabang=$d['nama'];
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Dashboard</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="purchase_order"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="terima_po"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Data Material Stock Dibawah Minimum
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="my_datatable">
                        <thead class="table-info text-center">
                            <tr>
                                <th width="50px">No</th>
                                <th>ID Material</th>
                                <th>Kategori</th>
                                <th>Merk / Type</th>
                                <th>Stock Minimum</th>
                                <th>Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            $tampil=mysqli_query($conn,"SELECT a.kode, a.merk_type, a.minimum_stok, b.nama AS nama_kategori, COALESCE(c.jumlah, 0) AS stok_tersedia FROM  master_material a
                            LEFT JOIN master_kategori_material b ON a.master_kategori_material_id = b.id
                            LEFT JOIN stok c ON a.id = c.master_material_id AND c.master_cabang_id = '$_SESSION[master_cabang_id]' AND c.deleted_at IS NULL
                            WHERE a.deleted_at IS NULL AND (a.minimum_stok > COALESCE(c.jumlah, 0) OR c.jumlah IS NULL)");
                            
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo $no;?></td>
                                    <td><?php echo $r['kode'];?></td>
                                    <td><?php echo $r['nama_kategori'];?></td>
                                    <td><?php echo $r['merk_type'];?></td>
                                    <td class="text-end"><?php echo formatAngka($r['minimum_stok']);?></td>
                                    <td class="text-end"><?php echo formatAngka($r['stok_tersedia']);?></td>
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

<?php
$grafik_po="";
$tampil=mysqli_query($conn,"SELECT a.nama, COUNT(b.id) AS total
FROM master_status a 
LEFT JOIN po b ON a.id = b.status_id AND b.deleted_at IS NULL AND b.request_master_cabang_id = '$_SESSION[master_cabang_id]'
WHERE a.remark = 'PO' GROUP BY a.nama");

while($r=mysqli_fetch_array($tampil)){
    $grafik_po.="{
        name: '$r[nama]',
        y: $r[total]
    },";
}

$grafik_terima_po="";
$tampil=mysqli_query($conn,"SELECT a.nama, COUNT(b.id) AS total  FROM master_status a 
LEFT JOIN po_terima b ON a.id=b.status_id AND b.deleted_at IS NULL
LEFT JOIN po c ON b.po_id=c.id AND c.deleted_at IS NULL AND c.request_master_cabang_id = '$_SESSION[master_cabang_id]'
WHERE a.remark = 'Penerimaan PO' GROUP BY a.nama");
while($r=mysqli_fetch_array($tampil)){
    $grafik_terima_po.="{
        name: '$r[nama]',
        y: $r[total]
    },";
}
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript">
    Highcharts.chart('purchase_order', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Purchase Order'
        },
        subtitle: {
            text:
            '<?php echo $nama_cabang;?>'
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20
                }, {
                    enabled: true,
                    distance: -40,
                    format: '{point.percentage:.1f}%',
                    style: {
                        fontSize: '1.2em',
                        textOutline: 'none',
                        opacity: 0.7
                    },
                    filter: {
                        operator: '>',
                        property: 'percentage',
                        value: 10
                    }
                }]
            }
        },
        series: [
            {
                name: 'Total Data',
                colorByPoint: true,
                data: [<?php echo $grafik_po;?>]
            }
        ]
    });


    Highcharts.chart('terima_po', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Penerimaan Purchase Order'
        },
        subtitle: {
            text:
            '<?php echo $nama_cabang;?>'
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20
                }, {
                    enabled: true,
                    distance: -40,
                    format: '{point.percentage:.1f}%',
                    style: {
                        fontSize: '1.2em',
                        textOutline: 'none',
                        opacity: 0.7
                    },
                    filter: {
                        operator: '>',
                        property: 'percentage',
                        value: 10
                    }
                }]
            }
        },
        series: [
            {
                name: 'Total Data',
                colorByPoint: true,
                data: [<?php echo $grafik_terima_po;?>]
            }
        ]
    });

</script>