<?php
require 'ceklogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <style>
        body, th, td {
            font-family: 'Times New Roman', Times, serif;
        }
        th {
            text-align: leaf;
            font-size: 16px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">Aplikasi Kasir</a>
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
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Barang Masuk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat Datang</li>
                    </ol>
                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Barang Masuk
                    </button>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Barang Masuk
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>


                            <?php
                            $get = mysqli_query($c, "select * from masuk m, produk p where m.idproduk=p.idproduk");
                            $i=1;

                            while($p=mysqli_fetch_array($get)){
                            $namaproduk =$p['namaproduk'];
                            $deskripsi =$p['deskripsi'];
                            $qty =$p['qty'];
                            $idmasuk =$p['idmasuk'];
                            $idproduk =$p['idproduk'];
                            $tanggal =$p['tanggalmasuk'];
                           
                            ?>
                                <tr>
                                    <td><?=$i++;?></td>
                                    <td><?=$namaproduk;?>: <?=$deskripsi;?></td>
                                    <td><?=$qty;?></td>
                                    <td><?=$tanggal;?></td>
                                    <td> 
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idmasuk;?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idmasuk;?>">
                                        Delete
                                    </button>
                                    </div>
                                                            
                                    </td>
                                </tr>

                                <!-- Modal edit-->
                                <div class="modal fade" id="edit<?=$idmasuk;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ubah Data Barang Masuk</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <form method="post">

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <input type= "text" name="namaproduk" class="form-control" placeholder="Nama produk" value="<?=$namaproduk;?>: <?=$deskripsi;?>"  disabled>
                                                <input type= "number" name="qty" class="form-control mt-2" placeholder="Harga Produk" value="<?=$qty;?>">
                                                <input type= "hidden" name="idm" value="<?=$idmasuk;?>">
                                                <input type= "hidden" name="idp" value="<?=$idproduk;?>">
                                            </div>
                                            
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="editdatabarangmasuk" >Submit</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>

                                            </form>

                                        </div>
                                        </div>
                                    </div>
                                            
                                              <!-- Modal delete-->
                                <div class="modal fade" id="delete<?= $idmasuk;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus data barang masuk</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <form method="post">

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                               Apakah anda yakin ingin menghapus data ini?
                                                <input type= "hidden" name="idp" value="<?=$idproduk;?>">
                                                <input type= "hidden" name="idm" value="<?=$idmasuk;?>">
                                            </div>
                                            
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="hapusdatabarangmasuk" >Submit</button>
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
            </main>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <script>
        // Menginisialisasi tabel menggunakan SimpleDataTables
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple");
    </script>
  
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
               $getproduk = mysqli_query($c, "SELECT * FROM produk");

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

            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="barangmasuk" >Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
            
            </form>

        </div>
    </div>
</div>
  
</div>
</body>
</html>
