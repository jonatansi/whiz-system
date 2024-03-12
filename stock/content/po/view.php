<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_item, (SELECT SUM(e.jumlah * e.harga) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_harga 
FROM po a 
LEFT JOIN master_cabang b ON a.request_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON a.master_vendor_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'"));
?>
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Purchase Order</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase Order</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col fw-bold">
                        <?php
                        echo $d['nomor'];
                        ?>
                    </div>
                    <div class="col text-end">
                        <button class='btn btn-danger btn-sm ml-2 btnCetak' id='<?php echo $d['id'];?>'><i class='fas fa-print'></i> Cetak</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <iframe src="<?php echo $BASE_URL;?>/stock/po-cetak-<?php echo $d['id'];?>" style="width:100%; height:100%; border:none" id="Iframe"></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".btnCetak").click(function() {
        var id = this.id;
        window.open("po-cetak-"+id, "popupWindow", "width=600,height=600,scrollbars=yes");
    });
        // Selecting the iframe element
    var frame = document.getElementById("Iframe");
        
    // Adjusting the iframe height onload event
    frame.onload = function()
    // function execute while load the iframe
    {
        // set the height of the iframe as 
        // the height of the iframe content
        frame.style.height = 
        frame.contentWindow.document.body.scrollHeight + 'px';
        

        // set the width of the iframe as the 
        // width of the iframe content
        frame.style.width  = 
        frame.contentWindow.document.body.scrollWidth+'px';
            
    }
</script>