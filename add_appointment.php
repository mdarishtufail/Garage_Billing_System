<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include layout and auth checks
// include('./constant/check.php'); 
include('./constant/connect.php');
include('./constant/layout/head.php');
include('./constant/layout/header.php');
// include('./constant/layout/sidebar.php'); // Uncomment if you use sidebar

?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Add Appointment</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Add Appointment</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-title">
                        <h4>Create Appointment</h4>
                    </div>
                    <div class="card-body">
                        <div class="input-states">
                            <form method="post" action="" class="form-horizontal">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Client Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="client_name" class="form-control" placeholder="Enter client name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Appointment Date</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="appointment_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Service Type</label>
                                        <div class="col-sm-9">
                                            <select name="service_type" class="form-control" required>
                                                <option value="">Select Service</option>
                                                <?php
                                                // Fetch service types from the categories table
                                                $query = "SELECT * FROM categories";
                                                $result = mysqli_query($connect, $query);
                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['categories_name'] . "'>" . $row['categories_name'] . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No service types available</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Vehicle Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="vehicle_number" class="form-control" placeholder="Enter vehicle number" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Mobile Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter mobile number (10 digits)" required pattern="^\d{10}$" title="Mobile number should be 10 digits">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Create Appointment</button>
                            </form>

                            <?php
                            // Appointment creation logic
                            function generateAppointmentID() {
                                return 'APT' . strtoupper(uniqid());
                            }

                            if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
                                isset($_POST['client_name']) &&
                                isset($_POST['appointment_date']) &&
                                isset($_POST['service_type']) &&
                                isset($_POST['vehicle_number']) &&
                                isset($_POST['mobile_number'])) {

                                // Get form data
                                $appointmentID = generateAppointmentID();
                                $clientName = mysqli_real_escape_string($connect, $_POST['client_name']);
                                $appointmentDate = mysqli_real_escape_string($connect, $_POST['appointment_date']);
                                $serviceType = mysqli_real_escape_string($connect, $_POST['service_type']);
                                $vehicleNumber = mysqli_real_escape_string($connect, $_POST['vehicle_number']);
                                $mobileNumber = mysqli_real_escape_string($connect, $_POST['mobile_number']);

                                // Insert data into the database
                                $sql = "INSERT INTO appointments (appointment_id, client_name, appointment_date, service_type, vehicle_number, mobile_number)
                                        VALUES ('$appointmentID', '$clientName', '$appointmentDate', '$serviceType', '$vehicleNumber', '$mobileNumber')";

                                if (mysqli_query($connect, $sql)) {
                                    echo "<script>alert('Appointment created successfully with ID: $appointmentID'); window.location.href='add_appointment.php';</script>";
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Error: Could not create appointment.</div>";
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
