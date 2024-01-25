<?php
include "konfig/base_url.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Whiz Digital System</title>
    <meta name="Description" content="PT. Whiz Digital Berjaya adalah perusahaan nasional yang bergerak dalam bidang penyediaan jasa dan infrastruktur telekomunikasi beserta IT Service, yang telah memiliki pengalaman panjang pada institusi Pemerintahan, BUMN maupun Koorporasi Swasta di Indonesia.">
    <meta name="Author" content="Whiz Digital">
	<meta name="keywords" content="">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo $BASE_URL;?>/images/icon.png" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link id="style" href="<?php echo $BASE_URL;?>/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <!-- Style Css -->
    <link href="<?php echo $BASE_URL;?>/assets/css/styles.min.css" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="<?php echo $BASE_URL;?>/assets/css/icons.min.css" rel="stylesheet" >


</head>

<body>

    <div class="container-fluid custom-page">
        <div class="row bg-white">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent-3">
                <div class="row w-100 mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto w-100">
                        <img src="<?php echo $BASE_URL;?>/images/login.jpeg"
                            class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white py-4">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="card-sigin">
                                        <div class="main-signup-header" style="border: 1px solid #CCC; padding:2rem;">

                                            <div class="text-center">
                                                <img src="<?php echo $BASE_URL;?>/images/logocolor.png" class="mb-2" alt="logo" style="max-height:80px;">
                                                <h6 class="fw-medium mb-4 fs-17">Selamat datang kembali! Silakan masuk untuk melanjutkan.</h6>
                                            </div>

                                            <form>
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Username</label> 
                                                    <input class="form-control" placeholder="Enter your username" type="text">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Password</label> 
                                                    <input class="form-control" placeholder="Enter your password" type="password">
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block w-100">Sign In</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="<?php echo $BASE_URL;?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Show Password JS -->
    <script src="<?php echo $BASE_URL;?>/assets/js/show-password.js"></script>

</body>

</html>