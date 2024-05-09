<?php


header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
// error_reporting(0);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
include "../timeout.php";
date_default_timezone_set("Asia/Jakarta");
if($_SESSION['login_system']==1){
	if(!cek_login()){
		$_SESSION['login_system'] = 0;
	}
}
if($_SESSION['login_system']==0){
	header('location: ../keluar');
}
else{
    include "../konfig/base_url.php";
    include "../konfig/koneksi.php";
    include "../konfig/library.php";
    include "../konfig/fungsi_tanggal.php";
    include "../konfig/fungsi_angka.php";
    include "../konfig/fungsi_generate_js.php";

    include "../services/send_discord.php";
    include "../services/get_error.php";

    $module = $_GET['module'];
    $kategori='';
    if(isset($_GET['kategori'])){
        $kategori=$_GET['kategori'];
    }

    $pegawai = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM pegawai WHERE id='$_SESSION[login_user]'"));

?>
    <!DOCTYPE html>
    <html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

    <head>

        <!-- Meta Data -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> Whiz Digital System</title>
        <meta name="Description" content="PT. Whiz Digital Berjaya adalah perusahaan nasional yang bergerak dalam bidang penyediaan jasa dan infrastruktur telekomunikasi beserta IT Service, yang telah memiliki pengalaman panjang pada institusi Pemerintahan, BUMN maupun Koorporasi Swasta di Indonesia.">
        <meta name="Author" content="Whiz Digital">
        
        <link rel="icon" href="<?php echo $BASE_URL;?>/images/logo.png" type="image/x-icon">
        
        <!-- Choices JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

        <!-- Main Theme Js -->
        <script src="<?php echo $BASE_URL;?>/assets/js/main.js"></script>
        
        <!-- Bootstrap Css -->
        <link id="style" href="<?php echo $BASE_URL;?>/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

        <!-- Style Css -->
        <link href="<?php echo $BASE_URL;?>/assets/css/styles.min.css" rel="stylesheet" >

        <!-- Icons Css -->
        <link href="<?php echo $BASE_URL;?>/assets/css/icons.css" rel="stylesheet" >

        <!-- Node Waves Css -->
        <link href="<?php echo $BASE_URL;?>/assets/libs/node-waves/waves.min.css" rel="stylesheet" > 

        <!-- Simplebar Css -->
        <link href="<?php echo $BASE_URL;?>/assets/libs/simplebar/simplebar.min.css" rel="stylesheet" >
        
        <!-- Color Picker Css -->
        <link rel="stylesheet" href="<?php echo $BASE_URL;?>/assets/libs/flatpickr/flatpickr.min.css">
        <link rel="stylesheet" href="<?php echo $BASE_URL;?>/assets/libs/@simonwep/pickr/themes/nano.min.css">

        <!-- Choices Css -->
        <link rel="stylesheet" href="<?php echo $BASE_URL;?>/assets/libs/choices.js/public/assets/styles/choices.min.css">
        
        <!-- Prism CSS -->
        <link rel="stylesheet" href="<?php echo $BASE_URL;?>/assets/libs/prismjs/themes/prism-coy.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

        <!-- Sweetalerts CSS -->
        <link rel="stylesheet" href="<?php echo $BASE_URL;?>/assets/libs/sweetalert2/sweetalert2.min.css">

        <link rel="stylesheet" href="<?php echo $BASE_URL_STOCK;?>/addons/css/custom.css">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    </head>
    
    <body>
        <!-- Start Switcher -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <nav class="border-bottom border-block-end-dashed">
                    <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                        <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home"
                            type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                        <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile"
                            type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab"
                        tabindex="0">
                        <div class="">
                            <p class="switcher-style-head">Theme Color Mode:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-light-theme">
                                            Light
                                        </label>
                                        <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme"
                                            checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-dark-theme">
                                            Dark
                                        </label>
                                        <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="">
                            <p class="switcher-style-head">Directions:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-ltr">
                                            LTR
                                        </label>
                                        <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-rtl">
                                            RTL
                                        </label>
                                        <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="">
                            <p class="switcher-style-head">Navigation Styles:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-vertical">
                                            Vertical
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-style" id="switcher-vertical"
                                            checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-horizontal">
                                            Horizontal
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-style"
                                            id="switcher-horizontal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="navigation-menu-styles">
                            <p class="switcher-style-head">Vertical & Horizontal Menu Styles:</p>
                            <div class="row switcher-style gx-0 pb-2 gy-2">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-menu-click">
                                            Menu Click
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                            id="switcher-menu-click">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-menu-hover">
                                            Menu Hover
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                            id="switcher-menu-hover">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-icon-click">
                                            Icon Click
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                            id="switcher-icon-click">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-icon-hover">
                                            Icon Hover
                                        </label>
                                        <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                            id="switcher-icon-hover">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidemenu-layout-styles">
                            <p class="switcher-style-head">Sidemenu Layout Styles:</p>
                            <div class="row switcher-style gx-0 pb-2 gy-2">
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-default-menu">
                                            Default Menu
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-default-menu" checked>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-closed-menu">
                                            Closed Menu
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-closed-menu">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-icontext-menu">
                                            Icon Text
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-icontext-menu">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-icon-overlay">
                                            Icon Overlay
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-icon-overlay">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-detached">
                                            Detached
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-detached">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-double-menu">
                                            Double Menu
                                        </label>
                                        <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                            id="switcher-double-menu">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <p class="switcher-style-head">Page Styles:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-regular">
                                            Regular
                                        </label>
                                        <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular"
                                            checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-classic">
                                            Classic
                                        </label>
                                        <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-modern">
                                            Modern
                                        </label>
                                        <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <p class="switcher-style-head">Layout Width Styles:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-full-width">
                                            Full Width
                                        </label>
                                        <input class="form-check-input" type="radio" name="layout-width" id="switcher-full-width"
                                            checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-boxed">
                                            Boxed
                                        </label>
                                        <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <p class="switcher-style-head">Menu Positions:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-menu-fixed">
                                            Fixed
                                        </label>
                                        <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-fixed"
                                            checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-menu-scroll">
                                            Scrollable
                                        </label>
                                        <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-scroll">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <p class="switcher-style-head">Header Positions:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-header-fixed">
                                            Fixed
                                        </label>
                                        <input class="form-check-input" type="radio" name="header-positions"
                                            id="switcher-header-fixed" checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-header-scroll">
                                            Scrollable
                                        </label>
                                        <input class="form-check-input" type="radio" name="header-positions"
                                            id="switcher-header-scroll">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <p class="switcher-style-head">Loader:</p>
                            <div class="row switcher-style gx-0">
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-loader-enable">
                                            Enable
                                        </label>
                                        <input class="form-check-input" type="radio" name="page-loader"
                                            id="switcher-loader-enable" checked>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check switch-select">
                                        <label class="form-check-label" for="switcher-loader-disable">
                                            Disable
                                        </label>
                                        <input class="form-check-input" type="radio" name="page-loader"
                                            id="switcher-loader-disable" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                        <div>
                            <div class="theme-colors">
                                <p class="switcher-style-head">Menu Colors:</p>
                                <div class="d-flex switcher-style pb-2">
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors"
                                            id="switcher-menu-light" checked>
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors"
                                            id="switcher-menu-dark">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors"
                                            id="switcher-menu-primary">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors"
                                            id="switcher-menu-gradient">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-transparent"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Menu"
                                            type="radio" name="menu-colors" id="switcher-menu-transparent">
                                    </div>
                                </div>
                                <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Menu dynamically change from below Theme Primary color picker</div>
                            </div>
                            <div class="theme-colors">
                                <p class="switcher-style-head">Header Colors:</p>
                                <div class="d-flex switcher-style pb-2">
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Light Header" type="radio" name="header-colors"
                                            id="switcher-header-light" checked>
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Dark Header" type="radio" name="header-colors"
                                            id="switcher-header-dark">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Color Header" type="radio" name="header-colors"
                                            id="switcher-header-primary">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors"
                                            id="switcher-header-gradient">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors"
                                            id="switcher-header-transparent">
                                    </div>
                                </div>
                                <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Header dynamically change from below Theme Primary color picker</div>
                            </div>
                            <div class="theme-colors">
                                <p class="switcher-style-head">Theme Primary:</p>
                                <div class="d-flex flex-wrap align-items-center switcher-style">
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary-1" type="radio"
                                            name="theme-primary" id="switcher-primary">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary-2" type="radio"
                                            name="theme-primary" id="switcher-primary1">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary"
                                            id="switcher-primary2">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary"
                                            id="switcher-primary3">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary"
                                            id="switcher-primary4">
                                    </div>
                                    <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                        <div class="theme-container-primary"></div>
                                        <div class="pickr-container-primary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="theme-colors">
                                <p class="switcher-style-head">Theme Background:</p>
                                <div class="d-flex flex-wrap align-items-center switcher-style">
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-bg-1" type="radio"
                                            name="theme-background" id="switcher-background">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-bg-2" type="radio"
                                            name="theme-background" id="switcher-background1">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background"
                                            id="switcher-background2">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-bg-4" type="radio"
                                            name="theme-background" id="switcher-background3">
                                    </div>
                                    <div class="form-check switch-select me-3">
                                        <input class="form-check-input color-input color-bg-5" type="radio"
                                            name="theme-background" id="switcher-background4">
                                    </div>
                                    <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                        <div class="theme-container-background"></div>
                                        <div class="pickr-container-background"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-image mb-3">
                                <p class="switcher-style-head">Menu With Background Image:</p>
                                <div class="d-flex flex-wrap align-items-center switcher-style">
                                    <div class="form-check switch-select m-2">
                                        <input class="form-check-input bgimage-input bg-img1" type="radio"
                                            name="theme-background" id="switcher-bg-img">
                                    </div>
                                    <div class="form-check switch-select m-2">
                                        <input class="form-check-input bgimage-input bg-img2" type="radio"
                                            name="theme-background" id="switcher-bg-img1">
                                    </div>
                                    <div class="form-check switch-select m-2">
                                        <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-background"
                                            id="switcher-bg-img2">
                                    </div>
                                    <div class="form-check switch-select m-2">
                                        <input class="form-check-input bgimage-input bg-img4" type="radio"
                                            name="theme-background" id="switcher-bg-img3">
                                    </div>
                                    <div class="form-check switch-select m-2">
                                        <input class="form-check-input bgimage-input bg-img5" type="radio"
                                            name="theme-background" id="switcher-bg-img4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between canvas-footer flex-wrap"> 
                        <a href="javascript:void(0);" id="reset-all" class="btn btn-danger w-100 m-1">Reset</a> 
                    </div>
                </div>
            </div>
        </div>
        <!-- End Switcher -->


        <!-- Loader -->
        <div id="loader" >
            <img src="<?php echo $BASE_URL;?>/assets/images/media/loader.svg" alt="">
        </div>
        <!-- Loader -->

        <div class="page">
            <!-- app-header -->
            <?php
            include "header.php";
            include "sidebar.php";
            include "content.php";
            include "footer.php";
            ?>
        </div>

        
        <!-- Scroll To Top -->
        <div class="scrollToTop">
            <span class="arrow"><i class="las la-angle-double-up"></i></span>
        </div>
        <div id="responsive-overlay"></div>
        <!-- Scroll To Top -->


        <div id="form_modul" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-small-title" aria-hidden="true"></div>

        <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast colored-toast bg-success-transparent" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="toast-header bg-success text-fixed-white">
                    <img class="bd-placeholder-img rounded me-2" src="../assets/images/brand-logos/toggle-white.png" alt="...">
                    <strong class="me-auto">Berhasil</strong></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body" id="successToastBody">
                    
                </div>
            </div>
        </div>


        <!-- Popper JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/@popperjs/core/umd/popper.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Defaultmenu JS -->
        <script src="<?php echo $BASE_URL;?>/assets/js/defaultmenu.min.js"></script>

        <!-- Node Waves JS-->
        <script src="<?php echo $BASE_URL;?>/assets/libs/node-waves/waves.min.js"></script>

        <!-- Sticky JS -->
        <script src="<?php echo $BASE_URL;?>/assets/js/sticky.js"></script>

        <!-- Simplebar JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="<?php echo $BASE_URL;?>/assets/js/simplebar.js"></script>

        <!-- Color Picker JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

        
        <!-- Apex Charts JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/apexcharts/apexcharts.min.js"></script>
        
        <!-- JSVector Maps JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
        
        <!-- JSVector Maps MapsJS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/jsvectormap/maps/world-merc.js"></script>
        <script src="<?php echo $BASE_URL;?>/assets/js/us-merc-en.js"></script>
        
        <!-- Datatables Cdn -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
        <!-- Custom-Switcher JS -->
        <script src="<?php echo $BASE_URL;?>/assets/js/custom-switcher.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
        <!-- Prism JS -->
        <script src="<?php echo $BASE_URL;?>/assets/libs/prismjs/prism.js"></script>
        <script src="<?php echo $BASE_URL;?>/assets/js/prism-custom.js"></script>
        
        <!-- Sweet alert-->
        <script src="<?php echo $BASE_URL;?>/assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <!-- Input mask Js -->
    	<script src="<?php echo $BASE_URL;?>/assets/libs/jquery.mask.min.js"></script>


        <!-- Custom JS -->
        <script src="<?php echo $BASE_URL;?>/assets/js/custom.js"></script>
        <script src="<?php echo $BASE_URL;?>/addons/js/form-masking-custom.js"></script>

        <script type="text/javascript">
            $('form').submit(function(){
                $(this).find(':submit').attr('disabled','disabled');
            });
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
    </body>
</html>
<?php
}
?>