<?php
require 'ceklogin.php';
?>
<html>
<head>
  <title>Total Pemesanan Produk</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>
<style>
        body, th, td {
            font-family: 'Times New Roman', Times, Times;
        }
        th {
            text-align:leaf;
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
<body>
<div class="container">
    <h2>Total Pesanan Produk</h4>
    <div class="data-tables datatable-dark">
    <table class="table table-bordered" id="mauexport" width="100%" cellspacing="0">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Sub-total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getproduk = mysqli_query($c, "SELECT p.namaproduk, p.harga, dp.qty 
                               FROM detailpesanan dp 
                               INNER JOIN produk p ON dp.idproduk = p.idproduk");

                // Inisialisasi nomor urut
                $i = 1;
                $totalQty = 0;
                $totalSubtotal = 0;
                while ($pl = mysqli_fetch_array($getproduk)) {
                    $namaproduk = $pl['namaproduk'];
                    $harga = $pl['harga']; 
                    $qty = $pl['qty']; 
                    $subtotal = $harga * $qty;
                    $totalQty += $qty;
                    $totalSubtotal += $subtotal;
                    // Menampilkan data dalam baris tabel
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $namaproduk;?></td>
                        <td>Rp<?php echo number_format($harga);?></td>
                        <td><?php echo number_format($qty);?></td>
                        <td>Rp<?php echo number_format($subtotal);?></td>
                    </tr>
                    <?php
                };
                
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?php echo number_format($totalQty);?></th>
                    <th>Rp<?php echo number_format($totalSubtotal);?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ],
                    customize: function (doc) {
                        // Recalculate total quantity and subtotal
                        var totalQty = 0;
                        var totalSubtotal = 0;
                        $('#mauexport tbody tr').each(function() {
                            var qty = $(this).find('td:eq(3)').text().replace(/\D/g,'');
                            var subtotal = $(this).find('td:eq(4)').text().replace(/\D/g,'');
                            totalQty += parseInt(qty);
                            totalSubtotal += parseInt(subtotal);
                        });
                        // Tambahkan total jumlah dan total subtotal ke PDF
                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'right',
                            text: [
                                'Total Jumlah: ' + totalQty,
                                'Total Subtotal: Rp' + totalSubtotal
                            ]
                        });
                    }
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ],
                    stripHtml: false // Menampilkan HTML dalam print
                }
            }
        ]
    } );
});

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

</body>
</html>
