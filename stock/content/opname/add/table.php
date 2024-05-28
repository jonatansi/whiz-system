<table class="table" id="my_datatable">
    <thead class="table-info text-center">
        <tr>
            <th width="50px">No</th>
            <th>Kategori</th>
            <th>Merk/Type</th>
            <th>Jlh Tercatat</th>
            <th>Satuan</th>
            <th>Kondisi Pembelian Awal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tampil=mysqli_query($conn,"SELECT e.nama AS nama_kategori_material, d.merk_type, b.jumlah AS jumlah_tercatat, f.nama AS nama_satuan_kecil, c.nama AS nama_kondisi FROM stok a 
        LEFT JOIN stok_kondisi b ON a.id=b.stok_id AND b.deleted_at IS NULL
        LEFT JOIN master_kondisi c ON b.master_kondisi_id=c.id
        LEFT JOIN master_material d ON a.master_material_id=d.id AND d.deleted_at IS NULL
        LEFT JOIN master_kategori_material e ON d.master_kategori_material_id=e.id AND e.deleted_at IS NULL
        LEFT JOIN master_satuan f ON d.master_satuan_id=f.id AND f.deleted_at IS NULL
        WHERE a.deleted_at  IS NULL AND a.master_gudang_id='$_POST[master_gudang_id]'");
        $no=1;
        while($r=mysqli_fetch_array($tampil)){
            ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $r['nama_kategori_material'];?></td>
                <td><?php echo $r['merk_type'];?></td>
                <td class="text-center"><?php echo formatAngka($r['jumlah_tercatat']);?></td>
                <td><?php echo $r['nama_satuan_kecil'];?></td>
                <td><?php echo $r['nama_kondisi'];?></td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </tbody>
</table>

<script type="text/javascript"> 
<?php

echo general_default_datatable();
?>
</script>