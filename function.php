<?php

session_start();

//Bikin koneksi
$c = mysqli_connect('localhost','root','','kasir'); 

//Login
if(isset($_POST['login'])){
    //initiate variable 
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($c, "SELECT * FROM user WHERE username='$username' and password='$password'");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        //Jika datanya ditemukan
        //berhasil login

        $_SESSION['login'] = 'True';
        header('location:index.php');
    }else {
        //Data tidak ditemukan
        //gagal login
        echo '
        <script>alert("Username atau Password salah);
        window.location.href="login.php"
        </script>
        ';
    }
}



if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    $insert = mysqli_query($c, "Insert into produk (namaproduk, deskripsi, harga, stock) values ('$namaproduk','$deskripsi','$harga','$stock')");
    if( $insert){
        header('location:stock.php');
    }else {
        echo '
        <script>alert("Gagal menambah barang baru");
        window.location.href="stock.php"
        </script>
        ';
    }
};



if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    $insert = mysqli_query($c, "Insert into pelanggan (namapelanggan,notelp,alamat) values ('$namapelanggan','$notelp','$alamat')");
    if( $insert){
        header('location:pelanggan.php');
    }else {
        echo '
        <script>alert("Gagal menambah pelanggan baru");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}


if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];

    $insert = mysqli_query($c, "Insert into pesanan (idpelanggan) values ('$idpelanggan')");
    if( $insert){
        header('location:index.php');
    }else {
        echo '
        <script>alert("Gagal menambah pesanan baru");
        window.location.href="index.php"
        </script>
        ';
    }
}

//produk dipilih di pesanan
if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp']; //idpesanan
    $qty = $_POST['qty']; //jumlah yang mau dikeluarin

   //hitung stock sekarang ada berapa
   $hitung1 = mysqli_query($c,"select * from produk where idproduk='$idproduk'");
   $hitung2 = mysqli_fetch_array($hitung1);
   $stocksekarang = $hitung2['stock']; //stock barang saat ini

   if($stocksekarang>=$qty){

    //Kurangi stocknya dengan jumlah yang akan dikeluarkan
    $selisih = $stocksekarang-$qty;

    //stocknya cukup
    $insert = mysqli_query($c, "Insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
    $update = mysqli_query($c, "update produk set stock='$selisih' where idproduk='$idproduk'");

    if($insert&&$update){
        header('location:view.php?idp='.$idp);
    }else {
        echo '
        <script>alert("Gagal menambah pesanan baru");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
    }
   }else{
    //stock ga cukup
    echo '
        <script>alert("Stock barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
   }
}


//Menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];

    //cari tahu stock sekarang berapa
    $caristock = mysqli_query($c, "select * from produk where idproduk='$idproduk'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    //hitung
    $newstock = $stocksekarang+$qty;

    $insertb = mysqli_query($c, "Insert into masuk (idproduk,qty) values ('$idproduk','$qty')");
    $updatetb = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idproduk'");

    if($insertb&&$updatetb){
        header('location:masuk.php');
    }else {
        echo '
        <script>alert("Gagal menghapus barang");
        window.location.href="masuk.php?idp='.$idorder.'"
        </script>
        ';
    }
}



//Hapus produk pesanan
if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp']; //iddetailpesanan
    $idpr = $_POST['idpr'];
    $idorder = $_POST['idorder'];

   //Cek qty sekarang
   $cek1 = mysqli_query($c, "Select * from detailpesanan where iddetailpesanan='$idp'");
   $cek2 = mysqli_fetch_array($cek1);
   $qtysekarang = $cek2['qty'];

   //Cek stock sekarang
   $cek3 = mysqli_query($c, "Select * from produk where idproduk='$idpr'");
   $cek4 = mysqli_fetch_array($cek3);
   $stocksekarang = $cek4['stock'];

   $hitung = $stocksekarang+$qtysekarang;

   $update = mysqli_query($c, "update produk set stock='$hitung' where idproduk='$idpr'"); //update stock
   $hapus = mysqli_query($c, "delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'"); 

   if($update&&$hapus){
        header('location:view.php?idp='.$idorder);
   }else {
        echo '
        <script>alert("Gagal menghapus barang");
        window.location.href="view.php?idp='.$idorder.'"
        </script>
        ';
   }
}

//edit barang
if(isset($_POST['editbarang'])){
    $np = $_POST['namaproduk'];
    $desc = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp']; //idproduk

    $query = mysqli_query($c, "update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp' ");

    if($query){
        header('location:stock.php');
    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="stock.php"
        </script>
        ';
    }
}

//hapus barang
if(isset($_POST['hapusbarang'])){
    $idp = $_POST['idp']; 

    $query = mysqli_query($c, "delete from produk where idproduk='$idp'");

    if($query){
        header('location:stock.php');
    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="stock.php"
        </script>
        ';
    }
}

//edit pelanggan
if(isset($_POST['editpelanggan'])){
    $np = $_POST['namapelanggan']; 
    $nt = $_POST['notelp']; 
    $a = $_POST['alamat']; 
    $id = $_POST['idpl']; 

    $query = mysqli_query($c, "update pelanggan set namapelanggan='$np', notelp='$nt', alamat='$a' where idpelanggan='$id' ");

    if($query){
        header('location:pelanggan.php');
    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

//hapus pelanggan
if(isset($_POST['hapuspelanggan'])){
    $idpl = $_POST['idpl']; 

    $query = mysqli_query($c, "delete from pelanggan where idpelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php');
    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}



//mengubah data barang masuk
if(isset($_POST['editdatabarangmasuk'])){
    $qty = $_POST['qty']; 
    $idm = $_POST['idm']; //id masuk
    $idp = $_POST['idp']; //id produk

    //cari tau qty nya sekarang berapa
    $caritahu = mysqli_query($c, "select * from masuk where idmasuk='$idm'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    //cari tahu stock sekarang berapa
    $caristock = mysqli_query($c, "select * from produk where idproduk='$idp'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    if($qty >= $qtysekarang){
        //kalau inputan user lebih besar daripada qty yg tercatat sekarang
        //hitung selisih
        $selisih = $qty-$qtysekarang;
        $newstock = $stocksekarang+$selisih;

        $query1 = mysqli_query($c, "update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idp'");

        if($query1&&$query2){
            header('location:masuk.php');
        }else {
            echo '
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
    }
    }else{
        //kalau lebih kecil 
        //hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstock = $stocksekarang-$selisih;

        $query1 = mysqli_query($c, "update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idp'");

        if($query1&&$query2){
            header('location:masuk.php');
        }else {
            echo '
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
    }
    }
}

//hapus data barang masuk
if(isset($_POST['hapusdatabarangmasuk'])){
    $idm = $_POST['idm']; 
    $idp = $_POST['idp']; 

  //cari tau qty nya sekarang berapa
  $caritahu = mysqli_query($c, "select * from masuk where idmasuk='$idm'");
  $caritahu2 = mysqli_fetch_array($caritahu);
  $qtysekarang = $caritahu2['qty'];

  //cari tahu stock sekarang berapa
  $caristock = mysqli_query($c, "select * from produk where idproduk='$idp'");
  $caristock2 = mysqli_fetch_array($caristock);
  $stocksekarang = $caristock2['stock'];

 //hitung selisih
 $newstock = $stocksekarang-$qtysekarang;

 $query1 = mysqli_query($c, "delete from masuk where idmasuk='$idm'");
 $query2 = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idp'");

 if($query1&&$query2){
        header('location:masuk.php');
    }else {
            echo '
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
    }
    
}


//hapus order 
if(isset($_POST['hapusorder'])){
    $ido = $_POST['ido']; 

    // Pastikan koneksi terbuka (contoh $c adalah koneksi)
    $cekdata = mysqli_query($c, "SELECT * FROM detailpesanan WHERE idpesanan='$ido'");
    if(!$cekdata){
        die("Error: " . mysqli_error($c)); // Tangani kesalahan jika query gagal
    }

    while($ok=mysqli_fetch_array($cekdata)){
        //balikin stock 
        $qty = $ok['qty'];
        $idproduk = $ok['idproduk'];
        $iddp = $ok['iddetailpesanan'];

         //cari tahu stock sekarang berapa
        $caristock = mysqli_query($c, "SELECT * FROM produk WHERE idproduk='$idproduk'");
        if(!$caristock){
            die("Error: " . mysqli_error($c)); // Tangani kesalahan jika query gagal
        }
        $caristock2 = mysqli_fetch_array($caristock);
        $stocksekarang = $caristock2['stock'];

        $newstock = $stocksekarang + $qty;

        $queryupdate = mysqli_query($c, "UPDATE produk SET stock='$newstock' WHERE idproduk='$idproduk'");
        if(!$queryupdate){
            die("Error: " . mysqli_error($c)); // Tangani kesalahan jika query gagal
        }

        //hapus data 
        $querydelete = mysqli_query($c, "DELETE FROM detailpesanan WHERE iddetailpesanan='$iddp'");
        if(!$querydelete){
            die("Error: " . mysqli_error($c)); // Tangani kesalahan jika query gagal
        }
    }

    // Hapus pesanan setelah detail pesanannya dihapus
    $query = mysqli_query($c, "DELETE FROM pesanan WHERE idorder='$ido'");
    if(!$query){
        die("Error: " . mysqli_error($c)); // Tangani kesalahan jika query gagal
    }

    // Jika semua query berhasil dieksekusi, arahkan ke halaman utama
    header('Location: index.php');
    exit(); // Pastikan tidak ada output lagi setelah melakukan redirect
}

//mengubah detail pesanan
if(isset($_POST['editdetailpesanan'])){
    $qty = $_POST['qty']; 
    $iddp = $_POST['iddp']; //id masuk
    $idpr = $_POST['idpr']; //id produk
    $idp = $_POST['idp']; //idpesanan

    //cari tau qty nya sekarang berapa
    $caritahu = mysqli_query($c, "select * from detailpesanan where iddetailpesanan='$iddp'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    //cari tahu stock sekarang berapa
    $caristock = mysqli_query($c, "select * from produk where idproduk='$idpr'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    if($qty >= $qtysekarang){
        //kalau inputan user lebih besar daripada qty yg tercatat sekarang
        //hitung selisih
        $selisih = $qty-$qtysekarang;
        $newstock = $stocksekarang-$selisih;

        $query1 = mysqli_query($c, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        }else {
            echo '
            <script>alert("Gagal");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }else{
        //kalau lebih kecil 
        //hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstock = $stocksekarang+$selisih;

        $query1 = mysqli_query($c, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($c, "update produk set stock='$newstock' where idproduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        }else {
            echo '
            <script>alert("Gagal");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }
}


?>