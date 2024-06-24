<?php

include ('connect.php');

if(isset($_POST['signup'])){

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check1 = "SELECT * From users where email = '$email'" ;
    $result1 = $conn -> query($check1);
    $check1 = "SELECT * From users where username = '$username'" ;
    $result2 = $conn -> query($check1);
    if($result1->num_rows>0 || $result2->num_rows>0)
    {
        echo "Either username or email already exists. Try Other !!" ;
    }
else{
    $sql1 = "INSERT INTO `users` ( `username`, `email`, `password`) VALUES ('$username', '$email', '$password')" ;

    if($conn->query($sql1)==TRUE){
        
        header("Location: index.php");
    }
    else{
        echo "Error!!".$conn->error;
    }
}

}

if(isset($_POST['signin'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    // $password=md5($password) ;
    
    $sql="SELECT * FROM users WHERE username ='$username' and password ='$password'";
    $result=$conn->query($sql);
    if($result->num_rows>0){
     session_start();
     $row=$result->fetch_assoc();
     $_SESSION['email']=$row['email'];
     header("Location: homepage1.php");
     exit();
    }
    else{
     echo "Not Found, Incorrect Email or Password";
     
    //  header("Location: index.php");
    //  echo ("Not Found, Incorrect Email or Password");
    }
}
?>
