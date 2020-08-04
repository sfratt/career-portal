<?php
$host = "database"; // service name from docker-compose.yml
$user = "devuser";
$password = "devpass";
$db = "test_db";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
};
// echo "Successfully connected to MySQL";

// After this line, if you get no errors, that means you are connected to the database. You refer to the database instance by $conn

function userExists($userName_input, $email_input) // To check if a username/email input is already taken in Emloyer table
// This method will return an array[boolean,boolean], the first boolean is true when username was taken and
// the second one is true for when the email taken.
{
    $username_taken = false;
    $email_taken = false;
    global $conn; // we need to globalize $conn inside our function, otherwise function will not have access to it and will give errors.

    $sql_u_on_employer = "SELECT UserName FROM Employer WHERE UserName='$userName_input'"; // we check if the username input by user is found on the table
    $sql_u_on_employee = "SELECT UserName FROM Employee WHERE UserName='$userName_input'";
    $res_u_on_employer = mysqli_query($conn, $sql_u_on_employer);
    $res_u_on_employee = mysqli_query($conn, $sql_u_on_employee);
    if (mysqli_num_rows($res_u_on_employer) > 0 or mysqli_num_rows($res_u_on_employee) > 0) {  //if the username is found, the number of rows for the result would not be zero
        $username_taken = true;
    }

    $sql_e_on_employer = "SELECT UserName FROM Employer WHERE Email='$email_input'"; // we check if the email input by user is found on the table
    $sql_e_on_employee = "SELECT UserName FROM Employee WHERE Email='$email_input'";
    $res_e_on_employer = mysqli_query($conn, $sql_e_on_employer);
    $res_e_on_employee = mysqli_query($conn, $sql_e_on_employee);
    if (mysqli_num_rows($res_u_on_employer) > 0 or mysqli_num_rows($res_u_on_employee) > 0) {  //if the email is found, the number of rows for the result would not be zero
        $username_taken = true;
    }

    if (mysqli_num_rows($res_e_on_employer) > 0 or mysqli_num_rows($res_e_on_employee) > 0) {  //if the email is found, the number of rows for the result would not be zero
        $email_taken = true;
    }
    $result = array($username_taken,  $email_taken);
    return $result;
}


function AddEmployer($userName, $userPassword, $Email, $Company, $Telephone, $PostalCode, $City, $Address, $EmployerCategoryId) // adding new employer to the table
{
    global $conn;
    $sql = "INSERT INTO Employer 
     (UserName, UserPassword, Email, Company, Telephone, PostalCode, City, Address, EmployerCategoryId)
     VALUES ('$userName','$userPassword','$Email','$Company', '$Telephone','$PostalCode','$City','$Address',$EmployerCategoryId);";
    $results = mysqli_query($conn, $sql);
}
function AddEmployee($userName, $userPassword, $Email, $Telephone, $PostalCode, $City, $Address, $EmployeeCategoryId) // adding new employee to the table
{
    global $conn;
    $sql = "INSERT INTO Employee
        (UserName, UserPassword, Email, Telephone, PostalCode, City, Address, EmployeeCategoryId)
    VALUES ('$userName','$userPassword','$Email','$Telephone','$PostalCode','$City','$Address',$EmployeeCategoryId);";
    $results = mysqli_query($conn, $sql);
}

function Authentication($userNameInput, $passwordInput)
{
    $isMatched = false;
    $sql_employer = "SELECT * FROM Employer WHERE UserName='$userNameInput'";
    $sql_employee = "SELECT * FROM Employee WHERE UserName='$userNameInput'";
    global $conn;
    $userType = "";
    if ($result = $conn->query($sql_employer)) {
        if (mysqli_num_rows($result) > 0) { // if at least one username was matched
            $row = $result->fetch_row(); // this will get one full row of database for Employer where username matched
            if (strcasecmp($row[1],"$userNameInput") == 0 && $row[2] == "$passwordInput") { // strcasecmp will compare two strings case-insensitively, 
                // example: strcamsecmp(ABC,abc) will return 0
                $isMatched = True;
                $userType= "employer";
                $result->free_result(); // This will free the memory that was dedicated to preserve the result of the query
                return [$isMatched, $userType, $row[1]];
            }
        }
    }
    if ($result = $conn->query($sql_employee)) {
        if (mysqli_num_rows($result) > 0) { // if at least one username was matched
            $row = $result->fetch_row(); // this will get one full row of database for Employer where username matched
            if (strcasecmp($row[1],"$userNameInput") == 0 && $row[2] == "$passwordInput") { // strcasecmp will compare two strings case-insensitively, 
                // example: strcamsecmp(ABC,abc) will return 0
                $isMatched = True;
                $userType= "employee";
                $result->free_result(); // This will free the memory that was dedicated to preserve the result of the query
                return [$isMatched, $userType, $row[1]]; // we will need the usertype and $row[1] which is the exaxt username in our database for saving in the session
            }
        }
    }
    return [false, "", ""]; // This is where the username or password was not a match to the database
}

function connection_close($conn) // This can be used to close the connection, not the best approach! so we will have to figure out about the best way of doing it.
{
    $conn->close();
}
