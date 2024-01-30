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

                <!-- Start::slide -->
                <li class="slide">
                    <a href="<?php echo $BASE_URL_MASTER;?>/home" class="side-menu__item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg>
                        <span class="side-menu__label">Dashboard</span>
                        <!-- <span class="badge bg-success ms-auto menu-badge">1</span> -->
                    </a>
                </li>

                <li class="slide <?php if($module=='branch'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/branch" class="side-menu__item <?php if($module=='branch'){echo "active";}?>">
                        <i class="fa fa-house side-menu__icon"></i>
                        <span class="side-menu__label">Branch</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='vendor'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/vendor" class="side-menu__item <?php if($module=='vendor'){echo "active";}?>">
                        <i class="fa-regular fa-building side-menu__icon"></i>
                        <span class="side-menu__label">Vendor</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='gudang'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/gudang" class="side-menu__item <?php if($module=='gudang'){echo "active";}?>">
                        <i class="fa fa-cubes side-menu__icon"></i>
                        <span class="side-menu__label">Penyimpanan</span>
                    </a>
                </li>


                <li class="slide <?php if($module=='material'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/material" class="side-menu__item <?php if($module=='material'){echo "active";}?>">
                        <i class="fa-brands fa-dropbox side-menu__icon"></i>
                        <span class="side-menu__label">Material</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='katmaterial'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/katmaterial" class="side-menu__item <?php if($module=='katmaterial'){echo "active";}?>">
                        <i class="fa-brands fa-dropbox side-menu__icon"></i>
                        <span class="side-menu__label">Kategori Material</span>
                    </a>
                </li>

                <li class="slide <?php if($module=='satuan'){echo "active";}?>">
                    <a href="<?php echo $BASE_URL_MASTER;?>/satuan" class="side-menu__item <?php if($module=='satuan'){echo "active";}?>">
                        <i class="fa fa-tag side-menu__icon"></i>
                        <span class="side-menu__label">Satuan</span>
                    </a>
                </li>
                
                <li class="slide__category"><span class="category-name">User Management</span></li>

                <li class="slide">
                    <a href="<?php echo $BASE_URL_MASTER;?>/user" class="side-menu__item">
                        <i class="fa-regular fa-user side-menu__icon"></i>
                        <span class="side-menu__label">Pengguna</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="<?php echo $BASE_URL_MASTER;?>/user-logactivity" class="side-menu__item">
                        <i class="fa fa-list side-menu__icon"></i>
                        <span class="side-menu__label">Log Activity</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="<?php echo $BASE_URL_MASTER;?>/user-loglogin" class="side-menu__item">
                        <i class="fa-solid fa-right-to-bracket side-menu__icon"></i>
                        <span class="side-menu__label">Log Login</span>
                    </a>
                </li>
                <!-- End::slide -->
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>