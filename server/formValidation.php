<?php
include_once "database_operations.php";
$valid = true; // at the end we will proceed to the next page only if valid is true
$categoryId = ""; // this will be the last entry for categoryId of employer/employee
$selected_type = ""; // this would be the membership type that was selected by userinput

if (isset($_POST['MembershipType'])) { //program will behave differently based on the type of membership selected
    switch ($_POST['MembershipType']) {
        case "employer_prime":
            $categoryId = 1;
            $selected_type = "employer";
            break;
        case "employer_gold":
            $categoryId = 2;
            $selected_type = "employer";
            break;
        case "applicant_basic":
            $categoryId = 1;
            $selected_type = "applicant";
            break;
        case "applicant_prime":
            $categoryId = 2;
            $selected_type = "applicant";
            break;
        case "applicant_gold":
            $categoryId = 3;
            $selected_type = "applicant";
            break;
    }
}

$errorMessage = array(); // We may have 1 or more error messages to warn the user to go back and fix the wrong inputs.

foreach ($_POST as $key => $value) { // if any of the fields are empty the user has to fix it. (With the exception of company for applicants)

    if (empty($_POST[$key])) {
        if ($selected_type == "applicant" && $key == "company") { //because company field is optional only for applicants
            continue;
        }
        $valid = false;
    }
}


if ($valid == true) {
    if ($_POST['password'] != $_POST['confirm_password']) { // check for password matching
        $errorMessage[] = 'Passwords should be the same!';
        $valid = false;
    }

    if (!isset($error_message)) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { //This is a server-side validation for correct email format from PHP 
            $errorMessage[] = "Invalid email address.";
            $valid = false;
        }
    }

    if (!isset($error_message)) { // check to see if the membership type was selected.
        if (!isset($_POST["MembershipType"])) {
            $errorMessage[] = "You should choose your user preference!";
            $valid = false;
        }
    }
    if (!isset($error_message)) { // check the number of characters for phone number (ntba)
        if (strlen($_POST["tel"]) > 13 || strlen($_POST["tel"]) < 10) { //This is a server-side validation for correct email format from PHP 
            $errorMessage[] = "Invalid telephone number!";
            $valid = false;
        }
    }
    if (!isset($error_message)) { // check the number of characters for phone number (ntba)
        if (strlen($_POST["postalCode"]) > 7 || strlen($_POST["postalCode"]) < 6) { //This is a server-side validation for correct email format from PHP 
            $errorMessage[] = "Invalid postal code!";
            $valid = false;
        }
    }

    // If we reach here then every field was good and checked. now we need to see if a username/email was already taken
    switch ($selected_type) {
        case "employer":
            $member_exists = EmployerExists($_POST['userName'], $_POST["email"]);
            break;

        case "applicant":
            $member_exists = ApplicantExists($_POST['userName'], $_POST["email"]);
            break;
    }
    if ($member_exists[0] == true) {
        $errorMessage[] = "This username is not available, please choose another username.";
        $valid = false;
    }
    if ($member_exists[1] == true) {
        $errorMessage[] = "This email is not available, please use another email.";
        $valid = false;
    }
}
// -------------------------------------------------------------------------------------------------------------
else { // this means one or more of the fields are empty. (valid is not true)
    $errorMessage[] = "All fields are required.";
}
// -------------------------------------------------------------------------------------------------------------
// every check was passed!
if ($valid == true) {
    $_SESSION["userName"] = $_POST["userName"]; // we set this session variable and will use refer to it on the next pages. (see dashboard pages after welcome word!)

    switch ($selected_type) {
        case "employer":
            AddEmployer($_POST["userName"], $_POST["password"], $_POST["email"], $_POST["company"], $_POST["tel"], $_POST["postalCode"], $_POST["city"], $_POST["address"], $categoryId);
            echo "<script type='text/javascript'>window.location.href = 'employer_dashboard.php?idh={$idh}&ajax_show=experience';</script>"; //navigate to dashboard    
            break;
        case "applicant":
            AddEmployee($_POST["userName"], $_POST["password"], $_POST["email"], $_POST["tel"], $_POST["postalCode"], $_POST["city"], $_POST["address"], $categoryId);
            echo "<script type='text/javascript'>window.location.href = 'applicant_dashboard.php?idh={$idh}&ajax_show=experience';</script>";    //navigate to dashboard
            break;
    }
}
