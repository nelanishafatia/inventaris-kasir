<?php
require 'ceklogin.php';


if(isset($_GET['idp'])){
    $idp = $_GET['idp'];

    $ambilnamapelanggan = mysqli_query($c, "select * from pesanan p, pelanggan pl where p.idpelanggan=pl.idpelanggan and p.idorder='$idp'");
    $np = mysqli_fetch_array($ambilnamapelanggan);
    $namapel = $np['namapelanggan'];
}else {
    header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Pesanan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
     <style>
        body, th, td {
            font-family: 'Times New Roman', Times, Times;
        }
        th {
            text-align: center;
            font-size: 16px;
        }
    </style>
    <style>
    /* Menjadikan garis pada sel tabel menjadi lebih tebal */
    #mauexport th,
    #mauexport td {
        border: 1px solid black;
    }
</style>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Aplikasi Kasir</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
           
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                                Order
                            </a>
                            <a class="nav-link" href="stock.php">
                                <div class="sb-nav-link-icon"><i class="far fa-edit"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="far fa-bookmark"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                Kelola Pelanggan
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Pesanan: <?=$idp;?></h1>
                        <h4 class="mt-4">Nama Pelanggan: <?=$namapel;?></h4>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Selamat Datang</li>
                        </ol>

                        <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Barang
                    </button>
                    <a href="export.php" class="btn btn-info mb-4">Export Data</a>
                    </button>
                    </a>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Pesanan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-boordered" id="mauexport" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Sub-total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                            $get = mysqli_query($c, "select * from detailpesanan p, produk pr where p.idproduk=pr.idproduk and idpesanan='$idp'");
                            $i=1;

                            while($p=mysqli_fetch_array($get)){
                            $idpr =$p['idproduk'];
                            $iddp =$p['iddetailpesanan'];
                            $qty =$p['qty'];
                            $harga =$p['harga'];
                            $namaproduk =$p['namaproduk'];
                            $desc =$p['deskripsi'];
                            $subtotal =$qty*$harga;
                           
                            ?>
                                <tr>
                                    
                                    <td><?=$i++;?></td>
                                    <td><?=$namaproduk;?> (<?=$desc;?>)</td>
                                    <td>Rp<?= number_format($harga);?></td>
                                    <td><?= number_format($qty);?></td>
                                    <td>Rp<?= number_format($subtotal);?></td>
                                    <td> 
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idpr;?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idpr;?>">
                                       Hapus
                                    </button>
                                    </td>
                                </tr>


                                 <!-- Modal edit-->
                                 <div class="modal fade" id="edit<?=$idpr;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ubah Detail Pesanan</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <form method="post">

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <input type= "text" name="namaproduk" class="form-control" placeholder="Nama produk" value="<?=$namaproduk;?>: <?=$desc;?>"  disabled>
                                                <input type= "number" name="qty" class="form-control mt-2" placeholder="Harga Produk" value="<?=$qty;?>">
                                                <input type= "hidden" name="iddp" value="<?=$iddp;?>">
                                                <input type= "hidden" name="idp" value="<?=$idp;?>">
                                                <input type= "hidden" name="idpr" value="<?=$idpr;?>">
                                            </div>
                                            
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="editdetailpesanan" >Submit</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>

                                            </form>

                                        </div>
                                        </div>
                                    </div>


                                 <!-- The Modal -->
                                <div class="modal fade" id="delete<?=$idpr;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Menghapus barang</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <form method="post">

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                            Apakah anda yakin ingin menghapus barang ini?
                                            <input type="hidden" name="idp" value="<?=$iddp;?>">
                                            <input type="hidden" name="idpr" value="<?=$idpr;?>">
                                            <input type="hidden" name="idorder" value="<?=$idp;?>">

                                            </div>
                                            
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="hapusprodukpesanan" >Ya</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>
                                            
                                            </form>

                                        </div>
                                    </div>
                                </div>



                            <?php
                            }; //end of while

                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
    <!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            
            <form method="post">

            <!-- Modal body -->
            <div class="modal-body">
            Pilih Barang
            <select name="idproduk"class="form-control">

               <?php
               $getproduk = mysqli_query($c, "SELECT * FROM produk where idproduk not in (SELECT idproduk FROM detailpesanan where idpesanan='$idp')");

               while($pl=mysqli_fetch_array($getproduk)){
                    $namaproduk =$pl['namaproduk'];
                    $stock =$pl['stock'];
                    $deskripsi =$pl['deskripsi'];
                    $idproduk =$pl['idproduk']; 
                ?>

                <option value="<?= $idproduk; ?>"><?= $namaproduk . " - " . $deskripsi;?> (Stock: <?=$stock;?>)</option>

                                
                <?php
                }
                ?>

               </select>

               <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah" min="1" required>
               <input type="hidden" name="idp" value="<?=$idp;?>">

            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="addproduk" >Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
            
            </form>

        </div>
    </div>
</div>
</html>