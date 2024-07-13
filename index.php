<?php
include "konfig/base_url.php";
?>
<html>
<head>
    <title>Stay Connected Whiz Digital</title>
    <link rel="icon" href="<?php echo $BASE_URL;?>/images/loginpage/stayconnected.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <link id="style" href="<?php echo $BASE_URL;?>/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <link href="<?php echo $BASE_URL;?>/assets/css/icons.min.css" rel="stylesheet" >

    <link href="<?php echo $BASE_URL;?>/assets/css/style-login.css?v1" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=6LdQhl0pAAAAAORLRytMwFzYm6tlEFc5-oYfA6Zc"></script>
</head>
<body>
    <div class="container-fluid body">
        <div class="row ">
            <div class="col-md-6">
                <img src="<?php echo $BASE_URL;?>/images/loginpage/whizdigital.svg" class="img-header2">
            </div>
            <div class="col-md-6 text-end">
                <img src="<?php echo $BASE_URL;?>/images/loginpage/whizlink.svg" class="img-header me-3">
                <img src="<?php echo $BASE_URL;?>/images/loginpage/whizlink_home.svg" class="img-header2 me-3">
                <img src="<?php echo $BASE_URL;?>/images/loginpage/octans.svg" class="img-header">
            </div>
        </div>
        <div class="vh-80 d-flex justify-content-center align-items-center">
            <div class="row w-100 justify-content-center align-items-center">
                <div class="col-md-8 d-flex justify-content-center align-items-center">
                    <div class="position-relative" id="circle-container">
                        <div class="circle-center text-center">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/werp.svg" alt="WERP Logo" class="circle-center-logo" id="werp">
                        </div>
                        <div class="circle-item whiz-matrix">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_matrix.svg" alt="Whiz Matrix" id="matrix" class="menu-circle active">
                            <p>Whiz Matrix</p>
                        </div>
                        <div class="circle-item whiz-spy">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_spy.svg" alt="Whiz Spy" id="spy" class="menu-circle">
                            <p>Whiz Spy</p>
                        </div>
                        <div class="circle-item whiz-temp">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_temp.svg" alt="Whiz Temp" id="temp" class="menu-circle">
                            <p>Whiz Temp</p>
                        </div>
                        <div class="circle-item whiz-mail">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_mail.svg" alt="Whiz Mail" id="mail" class="menu-circle">
                            <p>Whiz Mail</p>
                        </div>
                        <div class="circle-item whiz-cloud">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_cloud.svg" alt="Whiz Cloud" id="cloud" class="menu-circle">
                            <p>Whiz Cloud</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="login-form w-100 shadow-sm rounded-4 py-4 px-4 bg-white">
                        <div id="login_opsi">
                            <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_matrix_logo.svg" alt="Whiz Matrix" style="max-width:170px;" id="img_login">
                            <p class="mb-4" id="title_login">Management Asset and Transaction Inventory Order</p>
                            <div class="row justify-content-center" style="min-height:100px;">
                                <div class="col">
                                    <div class="circle-submenu" id="whiz_master" onclick="toggleActive(this)">
                                        <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_matrix_master.svg" alt="Master Data">
                                        <p>Master Data</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="circle-submenu" id="whiz_stock" onclick="toggleActive(this)">
                                        <img src="<?php echo $BASE_URL;?>/images/loginpage/whiz_matrix_asset.svg" alt="Asset Management">
                                        <p>Asset Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form id="loginForm" class="w-100" action="ceklogin">
                            <input type="hidden" name="id_portal" id="id_portal">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                                    </div>
                                    <input type="email" class="form-control form-email" id="loginUsername" placeholder="John@corporate.id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-password" id="loginPassword" placeholder="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" id="togglePassword" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div id="error" class="mt-3 mb-1"></div>
                            <button type="submit" class="btn btn-primary btn-block rounded-3" id="btnLogin">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-3">
                <img src="<?php echo $BASE_URL;?>/images/loginpage/stayconnected.svg">
            </div>
            <div class="col-md-6 text-center">
                Copyright © 2024 <b>PT. Whiz Digital Berjaya</b>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="<?php echo $BASE_URL;?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    

    <script src="<?php echo $BASE_URL;?>/addons/js/login.js"></script>
</body>
</html>