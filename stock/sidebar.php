<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="<?php echo $BASE_URL;?>/images/logocolor.png" alt="logo" class="desktop-logo">
            <img src="<?php echo $BASE_URL;?>/images/icon.png" alt="logo" class="toggle-logo">
            <img src="<?php echo $BASE_URL;?>/images/logowhite.png" alt="logo" class="desktop-white">
            <img src="<?php echo $BASE_URL;?>/images/icon.png" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">General</span></li>
                <!-- End::slide__category -->

                <li class="slide <?php if($module=='home'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/home" class="side-menu__item <?php if($module=='home'){echo "active";}?>">
                        <i class="fe fe-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">Purchase Order</span></li>

                <li class="slide <?php if($module=='po'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/po" class="side-menu__item <?php if($module=='po'){echo "active";}?>">
                        <i class="fe fe-file side-menu__icon"></i>
                        <span class="side-menu__label">Purchase Order</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='terima_po'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/terima-po" class="side-menu__item <?php if($module=='terima_po'){echo "active";}?>">
                        <i class="fe fe-check-square side-menu__icon"></i>
                        <span class="side-menu__label">Penerima Material</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">Material</span></li>

                <li class="slide <?php if($module=='persediaan'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/persediaan" class="side-menu__item <?php if($module=='persediaan'){echo "active";}?>">
                        <i class="fe fe-database side-menu__icon"></i>
                        <span class="side-menu__label">Persediaan Material</span>
                    </a>
                </li>


                <li class="slide <?php if($module=='guna'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/guna" class="side-menu__item <?php if($module=='guna'){echo "active";}?>">
                        <i class="fe fe-file-minus side-menu__icon"></i>
                        <span class="side-menu__label">Penggunaan Material</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='mutasi'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/mutasi" class="side-menu__item <?php if($module=='mutasi'){echo "active";}?>">
                        <i class="fe fe-git-branch side-menu__icon"></i>
                        <span class="side-menu__label">Mutasi Material</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='opname'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_STOCK;?>/opname" class="side-menu__item <?php if($module=='opname'){echo "active";}?>">
                        <i class="fe fe-git-pull-request side-menu__icon"></i>
                        <span class="side-menu__label">Stok Opname</span>
                    </a>
                </li>
                
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>