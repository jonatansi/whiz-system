<?php
if($_GET['kategori']=='user'){
    include "user/index.php";
}
else if($_GET['kategori']=='logactivity'){
    include "logactivity/index.php";
}
else if($_GET['kategori']=='loglogin'){
    include "loglogin/index.php";
}
?>