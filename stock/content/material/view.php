<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Detail Material</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Material</li>
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
            <?php
                $sql="SELECT a.*, b.nama AS nama_gudang, c.nama AS nama_kategori, d.nama AS nama_kondisi, e.nama AS nama_status, e.warna AS warna_status, f.merk_type, g.nama AS nama_cabang, h.nama AS uom, f.kode AS kode_material, f.remark AS remark_material FROM material_sn a
                LEFT JOIN master_gudang b ON a.master_gudang_id=b.id AND b.deleted_at IS NULL
                LEFT JOIN master_kategori_material c on a.master_kategori_material_id=c.id AND c.deleted_at IS NULL
                LEFT JOIN master_kondisi d ON a.master_kondisi_id=d.id AND d.deleted_at IS NULL
                LEFT JOIN master_material f ON a.master_material_id=f.id AND f.deleted_at IS NULL
                LEFT JOIN master_cabang g ON b.master_cabang_id=g.id AND g.deleted_at IS NULL
                LEFT JOIN master_satuan h ON f.master_satuan_id=h.id AND g.deleted_at IS NULL
                LEFT JOIN master_status e ON a.status_id=e.id WHERE b.deleted_at IS NULL AND a.id='$_GET[id]'";

                // if($_SESSION['master_cabang_id']!='1'){
                //     $sql.=" AND g.id='$_SESSION[master_cabang_id]' ";
                // }
                $d=mysqli_fetch_array(mysqli_query($conn,$sql));
                if(isset($d['id'])!=''){
                    $status = "<span class='badge bg-$d[warna_status]'>$d[nama_status]</span>";
                    ?>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td width="150px">Serial Number</td><td class="fw-bold text-end"><?php echo $d['serial_number'];?></td></tr>
                                    <tr><td>Kode</td><td class="fw-bold text-end"><?php echo $d['kode_material'];?></td></tr>
                                    <tr><td>Kategori</td><td class="fw-bold text-end"><?php echo $d['nama_kategori'];?></td></tr>
                                    <tr><td>Merk/Type</td><td class="fw-bold text-end"><?php echo $d['merk_type'];?></td></tr>
                                    <tr><td>Uom</td><td class="fw-bold text-end"><?php echo $d['uom'];?></td></tr>
                                    <tr><td>Deskripsi</td><td class="fw-bold text-end"><?php echo $d['remark_material'];?></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-5 offset-md-2">
                            <table class="table table-sm">
                                <tbody>

                                    <tr><td>Kondisi Pembelian Awal</td><td class="fw-bold text-end"><?php echo $d['nama_kondisi'];?></td></tr>
                                    <tr><td>Gudang</td><td class="fw-bold text-end"><?php echo $d['nama_gudang'];?></td></tr>
                                    <tr><td>Cabang</td><td class="fw-bold text-end"><?php echo $d['nama_cabang'];?></td></tr>
                                    <tr><td>Harga</td><td class="fw-bold text-end"><?php echo formatAngka($d['harga']);?></td></tr>
                                    <tr><td>Status</td><td class="fw-bold text-end"><?php echo $status;?></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table class="table" id="my_datatable">
                                <thead class="table-info">
                                    <tr>
                                        <th width="15%">Tanggal / Jam</th>
                                        <th>Status</th>
                                        <th>Transaksi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil=mysqli_query($conn,"SELECT a.*, b.nama AS nama_status,b.warna AS warna_status FROM material_sn_log a INNER JOIN master_status b ON a.status_id=b.id WHERE a.material_sn_id='$d[id]'");
                                    while($r=mysqli_fetch_array($tampil)){
                                        $status = "<span class='badge bg-$r[warna_status]'>$r[nama_status]</span>";
                                        $transaksi="";
                                        if($r['act_type_id']=='1'){
                                            $transaksi = "<a href='terimapo-view-$r[act_table_id]' class='text-primary'>$r[transaction_number]</a>";
                                        }
                                        else if($r['act_type_id']=='2'){
                                            $transaksi = "<a href='mutasi-view-$r[act_table_id]' class='text-primary'>$r[transaction_number]</a>";
                                        }
                                        else if($r['act_type_id']=='3'){
                                            $transaksi = "<a href='opname-view-$r[act_table_id]' class='text-primary'>$r[transaction_number]</a>";
                                        }
                                        else if($r['act_type_id']=='4'){
                                            $transaksi = "<a href='dismantle-view-$r[act_table_id]' class='text-primary'>$r[transaction_number]</a>";
                                        }
                                        else if($r['act_type_id']=='5'){
                                            $transaksi = "<a href='guna-view-$r[act_table_id]' class='text-primary'>$r[transaction_number]</a>";
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo WaktuIndo($r['created_at']);?></td>
                                            <td><?php echo $status;?></td>
                                            <td><?php echo $transaksi;?></td>
                                            <td><?php echo $r['remark'];?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <img src="<?php echo $BASE_URL;?>/images/nodata.jpg" class="img-fluid">
                        </div>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php
echo general_default_datatable();
?>
</script>