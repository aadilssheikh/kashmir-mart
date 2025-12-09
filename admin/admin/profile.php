<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include("../../db.php");

if (isset($_POST['re_password'])) {
    $email = $_SESSION['admin_email'];
    
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $re_pass = $_POST['re_pass'];
    
    $old_pass = mysqli_real_escape_string($con, $old_pass);
    $new_pass = mysqli_real_escape_string($con, $new_pass);
    $re_pass = mysqli_real_escape_string($con, $re_pass);
    
    $password_query = mysqli_query($con, "SELECT * FROM admin_info WHERE admin_email='$email'");
    $password_row = mysqli_fetch_assoc($password_query);
    $database_password = $password_row['admin_password'];

    
    if (password_verify($old_pass, $database_password)) {
        if ($new_pass === $re_pass) {
            $hashed_new_pass = password_hash($new_pass, PASSWORD_BCRYPT);
            $update_pwd = mysqli_query($con, "UPDATE admin_info SET admin_password='$hashed_new_pass' WHERE admin_email='$email'");
            
            if ($update_pwd) {
                echo "<script>alert('Password updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating password');</script>";
            }
        } else {
            echo "<script>alert('New password and confirm password do not match');</script>";
        }
    } else {
        echo "<script>alert('Old password is incorrect');</script>";
    }
}

include "sidenav.php";
include "topheader.php";
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Change Password</h4>
                        <p class="card-category">Complete your profile</p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="profile.php">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Enter old password</label>
                                        <input type="password" class="form-control" name="old_pass">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Enter new password</label>
                                        <input type="password" class="form-control" name="new_pass">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Confirm new password</label>
                                        <input type="password" class="form-control" name="re_pass">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary pull-right" type="submit" name="re_password">Update Profile</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
