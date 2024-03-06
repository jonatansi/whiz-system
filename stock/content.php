<div class="main-content app-content">
    <div class="container-fluid">
        <?php
        if($module=='home'){
            include "content/home/index.php";
        }
        else if($module=='po'){
            include "content/po/index.php";
        }
        else if($module=='terima_po'){
            include "content/terima_po/index.php";
        }
        else if($module=='gudang'){
            include "content/gudang/index.php";
        }
        else if($module=='material'){
            include "content/material/index.php";
        }
        else if($module=='katmaterial'){
            include "content/katmaterial/index.php";
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