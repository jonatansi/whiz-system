<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h4 class="mb-0">Hi, <b><?php echo $pegawai['nama'];?></b> welcome back!</h4>
        <p class="mb-0 text-muted">Dashboard dat amaster.</p>
    </div>
   
</div>
<!-- End Page Header -->

<!-- row -->
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
        <div class="card overflow-hidden sales-card bg-danger-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">VENDOR</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                                <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM master_vendor WHERE deleted_at IS NULL"));
                                echo $c['tot'];
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
                    <h6 class="mb-3 fs-12 text-fixed-white">MATERIAL</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                                <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM master_material WHERE deleted_at IS NULL"));
                                echo $c['tot'];
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div id="compositeline3"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-warning-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div >
                    <h6 class="mb-3 fs-12 text-fixed-white">PENYIMPANAN</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div >
                            <h4 class="fs-20 fw-bold mb-1 text-fixed-white">
                            <?php
                                $c=mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(id) AS tot FROM master_gudang WHERE deleted_at IS NULL"));
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
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Log Aktivitas Terakhir
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-info text-center">
                            <tr>
                                <th>Waktu</th>
                                <th>Modul</th>
                                <th>Aksi</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tampil=mysqli_query($conn,"SELECT a.*, c.nama AS nama_pegawai FROM pegawai_log_activity a
                            LEFT JOIN pegawai c ON a.pegawai_id=c.id ORDER BY a.created_at DESC LIMIT 10");
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo dateFormat($r['created_at']);?></td>
                                    <td><?php echo $r['modul'];?></td>
                                    <td><?php echo $r['aksi'];?></td>
                                    <td><?php echo $r['nama_pegawai'];?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Log Login Terakhir
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-info text-center">
                            <tr>
                                <th>Waktu Login</th>
                                <th>Nama</th>
                                <th>IP Address</th>
                                <th>Browser</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tampil=mysqli_query($conn,"SELECT a.*, b.email, b.nama FROM pegawai_log_login a LEFT JOIN pegawai b ON a.pegawai_id=b.id ORDER BY a.login_at DESC LIMIT 10");
                            while($r=mysqli_fetch_array($tampil)){
                                ?>
                                <tr>
                                    <td><?php echo dateFormat($r['login_at']);?></td>
                                    <td><?php echo $r['nama'];?></td>
                                    <td><?php echo $r['ip_address'];?></td>
                                    <td><?php echo $r['browser'];?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>