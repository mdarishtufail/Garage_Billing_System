<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required layout and connection files
include('./constant/connect.php');
include('./constant/layout/head.php');
include('./constant/layout/header.php');

// Check if appointment_id is provided in the URL
if (!isset($_GET['appointment_id'])) {
    echo "<div class='alert alert-danger'>No appointment selected.</div>";
    exit();
}

// Get appointment_id from the URL and sanitize it
$appointmentID = mysqli_real_escape_string($connect, $_GET['appointment_id']);

// Fetch appointment details from the database
$query = "SELECT * FROM appointments WHERE appointment_id = '$appointmentID'";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connect));
}

if (mysqli_num_rows($result) > 0) {
    // If appointment is found, fetch the details
    $appointment = mysqli_fetch_assoc($result);
} else {
    echo "<div class='alert alert-warning'>Appointment not found.</div>";
    exit();
}

?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Edit Appointment</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-title">
                        <h4>Edit Appointment Details</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['appointment_id']); ?>">
                            
                            <div class="form-group">
                                <label>Client Name</label>
                                <input type="text" name="client_name" class="form-control" value="<?php echo htmlspecialchars($appointment['client_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Appointment Date</label>
                                <input type="datetime-local" name="appointment_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Service Type</label>
                                <select name="service_type" class="form-control" required>
                                    <?php
                                    $query = "SELECT * FROM categories";
                                    $result = mysqli_query($connect, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selected = ($row['categories_name'] == $appointment['service_type']) ? 'selected' : '';
                                        echo "<option value='" . $row['categories_name'] . "' $selected>" . $row['categories_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Vehicle Number</label>
                                <input type="text" name="vehicle_number" class="form-control" value="<?php echo htmlspecialchars($appointment['vehicle_number']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" value="<?php echo htmlspecialchars($appointment['mobile_number']); ?>" required pattern="^\d{10}$">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $clientName = mysqli_real_escape_string($connect, $_POST['client_name']);
                            $appointmentDate = mysqli_real_escape_string($connect, $_POST['appointment_date']);
                            $serviceType = mysqli_real_escape_string($connect, $_POST['service_type']);
                            $vehicleNumber = mysqli_real_escape_string($connect, $_POST['vehicle_number']);
                            $mobileNumber = mysqli_real_escape_string($connect, $_POST['mobile_number']);

                            // Update the database
                            $sql = "UPDATE appointments SET 
                                    client_name = '$clientName',
                                    appointment_date = '$appointmentDate',
                                    service_type = '$serviceType',
                                    vehicle_number = '$vehicleNumber',
                                    mobile_number = '$mobileNumber'
                                    WHERE appointment_id = '$appointmentID'";

                            if (mysqli_query($connect, $sql)) {
                                echo "<script>alert('Appointment updated successfully!'); window.location.href='dashboard.php';</script>";
                            } else {
                                echo "<div class='alert alert-danger'>Error updating appointment.</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./constant/layout/footer.php'); ?>
