<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_gudang, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM mutasi_detail e WHERE a.id=e.mutasi_id AND e.deleted_at IS NULL) AS total_item, e.nama AS request_pegawai_nama
FROM mutasi a 
LEFT JOIN master_cabang b ON a.created_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_gudang c ON a.master_gudang_tujuan_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
LEFT JOIN pegawai e ON a.request_pegawai_id=e.id AND e.deleted_at IS NULL
WHERE a.deleted_at IS NULL AND a.id='$_GET[id]'"));
?>
<html>
	<head>
        <title><?php echo $d['nomor'];?></title>   
		<style type='text/css'>
			@page {
                size: A4;
                padding: 0;
                margin: 3mm 4mm 4mm 4mm; 
                /* font-family: 'Arial Narrow', sans-serif; */
            }			  

            @media print {
                .break{
                    page-break-before: always;
                }
            }
            body{
                font-family: 'Trebuchet MS', sans-serif;
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
                font-size: 0.8rem;
                width: 100%;
            }

            table tr td{
                padding: 4px !important;
            }

            .fw-bold{
                font-weight: bold;
            }

            table.top tr td{
                vertical-align:top;
            }

            .width-23{
                width:23%;
                float:left;
                margin-bottom: 5px;
                margin-right:5px;
                padding:2px;
            }

            .width-33{
                width:33.3%;
                float:left;
                margin-bottom: 5px;
                /* border: 1px solid red; */
            }


            .width-50{
                width:49%;
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
                font-size:0.8rem;
            }

            thead.border tr th{
                border-bottom:1px solid #CCC;
                font-weight:bold;
                padding:10px 5px;
                font-size:0.8rem;
                background:#F2F2F2;
            }

            tbody.border tr td{
                border-bottom:1px solid #CCC;
                padding:10px 5px !important;
                font-size:0.8rem;
            }

            tbody.special tr td{
                padding:10px !important;
                font-size:0.8rem;
                background:#F2F2F2;
            }

            .footer {
                position: fixed;
                bottom: 0;
            }

            .fw-italic{
                font-style:italic;
            }
        </style>
        <script>
		function myFunction() {
			window.print();
			setTimeout(window.close, 0);
		}
		</script>
	</head>
	<body>
        <div class="width-50">
            <h1>Mutasi Material</h1>
        </div>
        <div class="width-50 text-right">
            <img src="<?php echo $BASE_URL;?>/images/logo.png">
        </div>
        <div class="clear" style="margin-bottom: 2rem;"></div>

        <div class="width-33">
            <table class="top">
                <tr>
                    <td class="fw-bold">No. Mutasi</td>
                    <td class="text-right"><?php echo $d['nomor'];?></td>
                </tr>

                <tr>
                    <td class="fw-bold">Requester</td>
                    <td class="text-right"><?php echo $d['request_pegawai_nama'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Jabatan</td>
                    <td class="text-right"><?php echo $d['request_pegawai_jabatan'];?></td>
                </tr>
            </table>
        </div>
        <div class="width-33">&nbsp;</div>
        <div class="width-33">
            <table>
                <tr>
                    <td class="fw-bold">Tanggal</td>
                    <td class="text-right"><?php echo dateFormat($d['tanggal']);?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Gudang Tujuan</td>
                    <td class="text-right"><?php echo $d['nama_gudang'];?></td>
                </tr>
            </table>
        </div>
        <div class="clear" style="margin-bottom: 2rem;"></div>
        <table class="border">
            <thead class="border">
                <tr>
                    <th>NO</th>
                    <th class="text-left">KATEGORI</th>
                    <th class="text-left">MERK/TYPE</th>
                    <th>JLH ITEM</th>
                    <th>KONDISI</th>
                    <th>GUDANG ASAL</th>
                    <th>SN</th>
                </tr>
            </thead>
            <tbody class="border">
                <?php
                $no=1;
                $tampil=mysqli_query($conn,"SELECT a.*, b.merk_type, c.nama AS nama_kategori_material, e.nama AS nama_satuan_kecil, f.nama AS nama_gudang, g.nama AS nama_kondisi FROM mutasi_detail a
                LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
                LEFT JOIN master_kategori_material c ON a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
                LEFT JOIN master_satuan e ON a.master_satuan_kecil_id=e.id AND e.deleted_at IS NULL
                LEFT JOIN master_gudang f ON a.master_gudang_asal_id=f.id AND f.deleted_at IS NULL
                LEFT JOIN master_kondisi g ON a.master_kondisi_id=g.id AND g.deleted_at IS NULL
                WHERE a.deleted_at IS NULL AND a.mutasi_id='$_GET[id]'");
                $grand_total=0;
                while($r=mysqli_fetch_array($tampil)){
                    ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $r['nama_kategori_material'];?></td>
                        <td><a href="mutasi-sn-<?php echo $r['id'];?>" target="_blank"><?php echo $r['merk_type'];?></a></td>
                        <td class="text-center"><?php echo formatAngka($r['jumlah']).' '.$r['nama_satuan_kecil'];?></td>
                        <td><?php echo $r['nama_kondisi'];?></td>
                        <td><?php echo $r['nama_gudang'];?></td>
                        <td></td>
                    </tr>
                    <?php
                    $no++;

                    $grand_total+=$r['jumlah'];
                }
                ?>
            </tbody>
        </table>
        <div style="margin: 2rem 0"></div>
        <div class="width-33">
            <table>
                <tbody class="special">
                    <tr>
                        <td width="150px">STATUS :</td>
                        <td class="fw-bold"><?php echo $d['nama_status'];?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="width-33">&nbsp;</div>
        <div class="width-33">
            <table>
                <tbody class="special">
                    <tr>
                        <td width="150px">TOTAL :</td>
                        <td class="fw-bold text-right"><h3><?php echo formatAngka($grand_total);?></h3></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="clear" style="margin-bottom: 2rem;"></div>
        <footer>
            <div class="width-50">
                
            </div>
        </footer>
	</body>
</html>