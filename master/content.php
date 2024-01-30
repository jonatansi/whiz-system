<div class="main-content app-content">
    <div class="container-fluid">
        <?php
        $module = $_GET['module'];
        if($module=='home'){
            include "content/home/index.php";
        }
        else if($module=='branch'){
            include "content/branch/index.php";
        }
        else if($module=='vendor'){
            include "content/vendor/index.php";
        }
        else if($module=='gudang'){
            include "content/gudang/index.php";
        }
        else if($module=='material'){
            include "content/material/index.php";
        }
        else if($module=='satuan'){
            include "content/satuan/index.php";
        }
        else if($module=='user'){
            include "content/user/index.php";
        }
        ?>
    </div>
</div>