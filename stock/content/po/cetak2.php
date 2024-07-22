<?php
$d=mysqli_fetch_array(mysqli_query($conn,"SELECT a.*, b.nama AS nama_cabang, c.nama AS nama_vendor, d.nama AS nama_status, d.warna AS warna_status, (SELECT SUM(e.jumlah) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_item, (SELECT SUM(e.jumlah * e.harga) FROM po_detail e WHERE a.id=e.po_id AND e.deleted_at IS NULL) AS total_harga, w.nama AS nama_provinsi, x.nama AS nama_kabupaten, y.nama AS nama_kecamatan, z.nama AS nama_kelurahan , e.nama AS nama_buat, e.jabatan
FROM po a 
LEFT JOIN master_cabang b ON a.request_master_cabang_id=b.id AND b.deleted_at IS NULL
LEFT JOIN master_vendor c ON a.master_vendor_id=c.id AND c.deleted_at IS NULL
LEFT JOIN master_status d ON a.status_id=d.id
LEFT JOIN lok_provinsi w ON a.lok_provinsi_id=w.id
LEFT JOIN lok_kabupaten x ON a.lok_kabupaten_id=x.id
LEFT JOIN lok_kecamatan y oN a.lok_kecamatan_id=y.id
LEFT JOIN lok_kelurahan z ON a.lok_kelurahan_id=z.id
LEFT JOIN pegawai e ON a.created_pegawai_id=e.id
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

            .width-40{
                width:40%;
                float:left;
                margin-bottom: 5px;
                /* border: 1px solid red; */
            }

            .width-20{
                width:20%;
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


            .text-c-green{
                color: #18A84B;
            }
        </style>
        <script>
		function myFunction() {
			window.print();
			setTimeout(window.close, 0);
		}
		</script>
	</head>
	<body onload="myFunction()">
        <div class="width-40">
            <img src="<?php echo $BASE_URL;?>/images/logocolor.png" style="max-width:200px;">
        </div>
        <div class="width-20">&nbsp;</div>
        <div class="width-40">
            <p class='text-c-green m-0 p-0 fs-1'>PT. Whiz Digital Berjaya</p>
            <p class='m-0 p-0' style='font-size:0.85rem;'>The City Tower Level 12 Unit 1-N, Jalan MH Thamrin Nomor 81<br>
            Jakarta Pusat, DKI Jakarta, 10310</p>
            <table style='font-size:0.8rem;'>
                <tbody>
                    <tr><td>P</td><td>:</td><td>+62 21 5569 2222</td></tr>
                    <tr><td>M</td><td>:</td><td>+62 813 3300 0606</td></tr>
                    <tr><td>W</td><td>:</td><td>https://whizdigital.id</td></tr>
                    <tr><td>E</td><td>:</td><td>finance@whizdigital.id</td></tr>
                </tbody>
            </table>
        </div>
        <div class="clear" style="margin-bottom: 0.4rem;"></div>
        <div class="width-50">
            <h1>Purchase Order</h1>
        </div>
        <div class="clear" style="margin-bottom: 0.4rem;"></div>

        <div class="width-40">
            <table class="top">
                <tr>
                    <td class="fw-bold">No. Purchase Order</td>
                    <td class="text-right"><?php echo $d['nomor'];?></td>
                </tr>

                <tr>
                    <td class="fw-bold">Tanggal PO</td>
                    <td class="text-right"><?php echo dateFormat($d['tanggal']);?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Requester</td>
                    <td class="text-right"><?php echo $d['nama_cabang'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">PIC Penerima</td>
                    <td class="text-right"><?php echo $d['request_pic_nama'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">No. HP PIC Penerima</td>
                    <td class="text-right"><?php echo $d['request_pic_hp'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Dikirim ke</td>
                    <td class="text-right">
                        <?php 
                            echo $d['alamat_tujuan'].'<br>Kel. '.$d['nama_kelurahan'].', Kec. '.$d['nama_kecamatan'].'<br>'.$d['nama_kabupaten'].', '.$d['nama_provinsi'].'<br>Kode POS : '.$d['tujuan_kode_pos'];
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="width-20">&nbsp;</div>
        <div class="width-40">
            <table>
                <tr>
                    <td class="fw-bold">No. Penawaran</td>
                    <td class="text-right"><?php echo $d['nomor_penawaran'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Vendor</td>
                    <td class="text-right"><?php echo $d['nama_vendor'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">PIC Vendor</td>
                    <td class="text-right"><?php echo $d['vendor_pic_nama'];?></td>
                </tr>
                <tr>
                    <td class="fw-bold">No. HP PIC Vendor</td>
                    <td class="text-right"><?php echo $d['vendor_pic_hp'];?></td>
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
                    <th>JLH SATUAN<br>BESAR</th>
                    <th>JLH SATUAN<br>DASAR</th>
                    <th>KONDISI<br>PEMBELIAN AWAL</th>
                    <th>HARGA SATUAN</th>
                    <th>SUBTOTAL</th>
                </tr>
            </thead>
            <tbody class="border">
                <?php
                $no=1;
                $tampil=mysqli_query($conn,"SELECT a.*, b.merk_type, c.nama AS nama_kondisi, d.nama AS nama_kategori_material, e.nama AS nama_satuan_besar, f.nama AS nama_satuan_kecil FROM po_detail a
                LEFT JOIN master_material b ON a.master_material_id=b.id AND b.deleted_at IS NULL
                LEFT JOIN master_kondisi c ON a.master_kondisi_id=c.id AND c.deleted_at IS NULL
                LEFT JOIN master_kategori_material d ON a.master_kategori_material_id=d.id AND d.deleted_at IS NULL
                LEFT JOIN master_satuan e ON a.master_satuan_besar_id=e.id AND e.deleted_at IS NULL
                LEFT JOIN master_satuan f ON a.master_satuan_kecil_id=f.id AND f.deleted_at IS NULL
                WHERE a.deleted_at IS NULL AND a.po_id='$_GET[id]'");
                $grand_total=0;
                while($r=mysqli_fetch_array($tampil)){
                    ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $r['nama_kategori_material'];?></td>
                        <td><?php echo $r['merk_type'];?></td>
                        <td class="text-center"><?php echo formatAngka($r['jumlah']).' '.$r['nama_satuan_besar'];?></td>
                        <td class="text-center"><?php echo formatAngka($r['jumlah']*$r['jumlah_konversi']).' '.$r['nama_satuan_kecil'];?></td>
                        <td class="text-center"><?php echo $r['nama_kondisi'];?></td>
                        <td class="text-right"><?php echo formatAngka($r['harga']);?></td>
                        <td class="text-right"><?php echo formatAngka($r['jumlah']*$r['harga']);?></td>
                    </tr>
                    <?php
                    $no++;

                    $grand_total+=($r['jumlah']*$r['harga']);
                }
                ?>
            </tbody>
        </table>
        <div style="margin: 2rem 0"></div>
        <div class="width-33">
            <table style="margin-bottom:1rem;">
                <tbody class="special">
                    <tr>
                        <td width="150px">STATUS :</td>
                        <td class="fw-bold"><?php echo $d['nama_status'];?></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody class="special">
                    <tr>
                        <td width="150px">NOTE/REMARK :</td>
                        <td class="fw-bold"><?php echo $d['deskripsi'];?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="width-33">&nbsp;</div>
        <div class="width-33">
            <table>
                <tbody class="special">
                    <tr>
                        <td width="150px">GRAND TOTAL :</td>
                        <td class="fw-bold text-right"><h3>Rp <?php echo formatAngka($grand_total);?></h3></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="clear" style="margin-bottom: 2rem;"></div>
        <footer>
            <div class="width-50">
                Disetujui oleh<br>
                PT Whiz Digital Berjaya<br><br><br><br><br>
               <b><u> <?php echo $d['nama_buat'];?></u></b><br>
               <?php echo $d['jabatan'];?>
            </div>
        </footer>
	</body>
</html>