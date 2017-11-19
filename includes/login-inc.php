<?php

session_start();

if (isset($_POST['submit'])){
    include 'db-inc.php';

    $username=mysqli_real_escape_string($conn, $_POST['username']);
    $password=mysqli_real_escape_string($conn, $_POST['password']);

    /*Check if inputs are empty*/
    if (!isset($username) || !isset($password)){
      echo "Empty Input";
      header("Location: ../index.php?login==emptyinput");
      exit();
    }
    else{
      $sql = "SELECT * FROM User WHERE username='$username'";
      $result = mysqli_query($conn, $sql);
      $resultCheck = mysqli_num_rows($result);
      if($resultCheck < 1){
        echo "Wrong Username!";
        header("Location: ../index.php?login==erroruser");
        exit();
      }
      else{
        if($row = mysqli_fetch_assoc($result)){
          //username exists build second salt query
          //$salt = $row['salt'];
          $saltsql = "SELECT username FROM User WHERE username='$username' AND password='$password'";
          $finalresult = mysqli_query($conn, $saltsql);
          if($finalrow = mysqli_fetch_assoc($finalresult)){
            /*Login successful - login user*/
            $_SESSION['username'] = $finalrow['Username'];
            $_SESSION['firstname'] = $finalrow['FirstName'];
            $_SESSION['lastname'] = $finalrow['LastName'];
            header("Location: ../index.php?login==success");
            exit();
          }
          else{
            echo "Wrong Password!";
            header("Location: ../index.php?login==errorpwd");
            exit();
          }
        }
      }
    }
  }
  else{
    header("Location: ../index.php?login==error");
    exit();
  }
