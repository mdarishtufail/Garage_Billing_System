<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required layout and connection files
include('./constant/connect.php');
include('./constant/layout/head.php');
include('./constant/layout/header.php');

// Fetch all appointments from the database
$query = "SELECT * FROM appointments";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connect));
}
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-title">
                        <h4>All Appointments</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Appointment ID</th>
                                    <th>Client Name</th>
                                    <th>Appointment Date</th>
                                    <th>Service Type</th>
                                    <th>Vehicle Number</th>
                                    <th>Mobile Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['client_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['service_type']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['vehicle_number']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['mobile_number']); ?></td>
                                        <td>
                                            <a href="edit_appointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <a href="add-order.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary">Invoice</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./constant/layout/footer.php'); ?>
