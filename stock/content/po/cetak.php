<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_item, (SELECT SUM(e.jumlah * e.harga) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_harga 
FROM po a 
LEFT JOIN master_cabang b ON a.request_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON a.master_vendor_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'"));
?>
<html>
	<head>
        <title><?php echo $d['nomor'];?></title>   
		<style type='text/css'>
			@page {
                size: A4;
                padding: 0;
                margin: 5mm 6mm 5mm 14mm; 
                font-family: 'Arial Narrow', sans-serif;
            }			  

            @media print {
                .break{
                    page-break-before: always;
                }
            }
            body{
                font-family: 'Arial Narrow', sans-serif;
                font-size:0.8rem;
                line-height: 1rem;
                -webkit-print-color-adjust: exact !important;
            }

            .family-trebuchet{
                font-family: 'Trebuchet MS', sans-serif;
            }

            .fs-1{
                font-size: 0.99rem;
            }

            .text-left{
                text-align:left;
            }

            .text-center{
                text-align:center;
            }
    
            .text-right{
                text-align:right !important;
            }

            table{
                border-collapse:collapse;
                font-size:0.75rem;
            }

            .width-23{
                width:23%;
                float:left;
                margin-bottom: 5px;
                margin-right:5px;
                padding:2px;
            }

            .width-33{
                width:40%;
                float:left;
                margin-bottom: 5px;
                /* border: 1px solid red; */
            }


            .width-50{
                width:48%;
                float:left;
                margin-bottom: 5px;
                margin-right:5px;
            }

            .width-67{
                width:59%;
                float:left;
                margin-bottom: 5px;
                margin-right:2px;
                /* border: 1px solid red; */
            }

            .width-100{
                width:100%;
                text-align: center;
                font-weight: bold;
                margin-bottom: 5px;
                margin-right:5px;
                padding:2px;
            }

            .clear{
                clear:both;
            }

            .border-div{
                border:1px dashed #000;
            }

            h6{
                margin:5px 0 5px 0;
            }

            .img-fluid{
                max-width: 100%;
                height:auto;
            }

            .content-body{
                margin-top:20px;
            }

            h4{
                font-size:0.8rem;
            }

            h3, h4{
                margin:0;
            }

            table.border{
                border-collapse:collapse;
                font-size:0.9rem;
            }

            thead.border tr th{
                border:1px solid #000;
                font-weight:500;
                padding:3px;
                font-size:0.8rem;
                background:#F2F2F2;
            }

            tbody.border tr td{
                border:1px solid #000;
                padding:3px;
                font-size:0.8rem;
            }

            .footer {
                position: fixed;
                bottom: 0;
                padding:5px;
                font-style:italic;
                background-color:#548DD4;
                text-align:center;
                width:98%;
            }

            .fw-italic{
                font-style:italic;
            }
            
            table{
                width:100%;
                border-collapse:collapse;
            }

            table.mytable tbody tr td{
                vertical-align: middle;
            }

            table.head tbody tr td{
                vertical-align: top;
                padding: 0;
            }

            table.detail thead tr th, table.detail tbody tr td, table.detail tfoot tr td{
                font-size: 0.8rem;
                padding: 10px;
                border: 1px solid #000;
            }

            table.detail thead tr th{
                background-color: #18A84B;
                font-weight: normal;
            }


            .img-foto{
                max-width:240px;
                border:1px solid #F2F2F2;
                background-color: #FFF;
                padding:5px;
            }

            .text-right{
                text-align: right;
            }

            .text-center{
                text-align: center;
            }

            
            .my-2{
                margin-top: 0.5rem;
                margin-bottom: 0.2rem;
            }

            
            .mt-3{
                margin-bottom: 2rem;;
            }

            .mb-3{
                margin-bottom: 2rem;;
            }

            .border-head{
                border-top: 2px solid #468FFC;
            }

            .border-head2{
                border-left: 1px solid #468FFC;
                border-right: 1px solid #468FFC;
                border-bottom: 1px solid #468FFC;
            }
            .clear{
                clear:both;
            } 
            .bg-red{
                background-color: #468FFC;
                color: #fff;
            }

            .text-c-green{
                color: #18A84B;
            }

            .fw-bold-arial{
                font-weight: bold;
            }

            .m-0{
                margin:0;
            }
            .p-0{
                padding:0;
            }

            .border-y{
                border-left:1px solid #000;
                border-right:1px solid #000;
            }

            table.border-y tr td{
                border-top:1px solid #000;
                border-bottom:1px solid #000;
            }
        </style>
	</head>
	<body>
        <div class="width-50">
tes
        </div>
        <div class="width-50">
            test
        </div>
	</body>
</html>