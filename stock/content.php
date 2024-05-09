<div class="main-content app-content">
    <div class="container-fluid">
        <?php
        if($module=='home'){
            if($_SESSION['master_cabang_id']!='1'){
                include "content/home/index.php";
            }
            else{
                include "content/home/index2.php";
            }
        }
        else if($module=='po'){
            include "content/po/index.php";
        }
        else if($module=='terima_po'){
            include "content/terima_po/index.php";
        }
        else if($module=='persediaan'){
            include "content/persediaan/index.php";
        }
        else if($module=='guna'){
            include "content/guna/index.php";
        }
        else if($module=='mutasi'){
            include "content/mutasi/index.php";
        }
        else if($module=='opname'){
            include "content/opname/index.php";
        }
        else if($module=='profile'){
            include "content/profile/index.php";
        }
        else if($module=='material'){
            include "content/material/index.php";
        }
        else if($module=='cari'){
            include "content/cari/index.php";
        }
        ?>
    </div>
</div>