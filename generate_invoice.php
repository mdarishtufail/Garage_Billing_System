<?php include('./constant/layout/head.php'); ?>

<?php 
include('./constant/connect.php');
?>

<div class="container-fluid" style="background-color: #ffffff;">
    <?php
    // Get appointment details using appointment ID
    if (isset($_GET['appointment_id'])) {
        $appointmentID = mysqli_real_escape_string($connect, $_GET['appointment_id']);
        $appointmentQuery = "SELECT * FROM appointments WHERE appointment_id = '$appointmentID'";
        $appointmentResult = mysqli_query($connect, $appointmentQuery);
        $appointment = mysqli_fetch_assoc($appointmentResult);

        // Fetch client details
        $clientQuery = "SELECT * FROM tbl_client WHERE id = '" . $appointment['client_name'] . "'";
        $clientResult = mysqli_query($connect, $clientQuery);
        $client = mysqli_fetch_assoc($clientResult);
    }
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-title">
                    <div class="float-left">
                        <h2 class="mb-0" style="color: black;">Invoice #<?php echo $appointment['appointment_id']; ?></h2>
                    </div> 
                    <div class="float-right"> 
                        Date: <?php echo $appointment['appointment_date']; ?>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-4 mt-4">
                            <?php
                            $webQuery = "SELECT * FROM manage_website";
                            $webResult = mysqli_query($connect, $webQuery);
                            $web = mysqli_fetch_array($webResult);
                            ?>
                            <br>
                            <img class="profile-img" src="./assets/uploadImage/Logo/<?=$web['invoice_logo']?>" style="height:100px;width:auto;">
                        </div>
                        <div class="col-sm-4">
                            <br>
                            <h5 class="mb-3" style="color: black;">From:</h5>                                            
                            <h3 class="text-dark mb-1">ORANGE STATION</h3>
                            <div><?php echo $web['currency_code']; ?></div>
                            <div>Email: <?php echo $web['email']; ?></div>
                            <div>Contact: <?php echo $web['short_title']; ?></div>
                        </div>
                        <div class="col-sm-4">
                            <br>
                            <h5 class="mb-3" style="color: black;">To:</h5>
                            <h3 class="text-dark mb-1"><?= $client['name']; ?></h3>                                            
                            <div><?= $client['address']; ?></div>
                            <div>Phone: <?= $client['mob_no']; ?></div>
                        </div>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Service</th>
                                    <th class="right">Unit Cost</th>
                                    <th class="center">Qty</th>
                                    <th class="right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch services from order_item table (or similar)
                                $servicesQuery = "SELECT * FROM order_item WHERE order_id = '$appointmentID'";
                                $servicesResult = mysqli_query($connect, $servicesQuery);

                                $no = 0;
                                while ($service = mysqli_fetch_array($servicesResult)) {
                                    $productQuery = "SELECT * FROM product WHERE product_id = '".$service['product_id']."'";
                                    $productResult = mysqli_query($connect, $productQuery);
                                    $product = mysqli_fetch_array($productResult);
                                    $no++;
                                ?>
                                <tr>
                                    <td class="center"><?= $no ?></td>
                                    <td class="left strong"><?= $product['product_name'] ?></td>
                                    <td class="right"><?= $service['rate'] ?></td>
                                    <td class="center"><?= $service['quantity'] ?></td>
                                    <td class="right"><?= $service['total'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">
                            <img style="height: 250px; padding-left: 316px;" src="https://thumbs.dreamstime.com/b/grunge-blue-signature-word-round-rubber-seal-stamp-white-background-171945013.jpg">
                        </div>
                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Subtotal</strong>
                                        </td>
                                        <td class="right"><?= $appointment['sub_total'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Discount (<?= $appointment['discount'] ?>%)</strong>
                                        </td>
                                        <td class="right">
                                            <?= $discount = $appointment['sub_total'] * ($appointment['discount'] / 100); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">GST (<?= $appointment['gst_rate'] ?>%)</strong>
                                        </td>
                                        <td class="right">
                                            <?php
                                            $gst_rate = ($appointment['sub_total'] - $discount) * ($appointment['gstn'] / 100);
                                            echo number_format($gst_rate, 2);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Total</strong>
                                        </td>
                                        <td class="right">
                                            <strong class="text-dark"><?= $appointment['grand_total'] ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <img style="height: 150px;" src="https://upload.wikimedia.org/wikipedia/commons/2/25/Ivory_Signature.png">
                    <p class="mb-0">Thank you for your business!</p>
                </div>
                <br><br><br><br>
                <p style="text-align:right;"></p>
            </div>
            <input id="printbtn" type="button" class="btn btn-success btn-flat m-b-30 m-t-30" value="Print Invoice" onclick="window.print();">
            <input id="printbtn" type="button" value="Go Back" class="btn btn-danger btn-flat m-b-30 m-t-30" onclick="goBack()">
        </div>
    </div>
</div>


<?php include('./constant/layout/footer.php'); ?>

<script>
function goBack() {
    window.history.back();
}
</script>
