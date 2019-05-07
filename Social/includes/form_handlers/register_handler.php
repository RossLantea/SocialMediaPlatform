<?php
//Muuttujien ilmoittaminen virheiden estämiseksi
$fname = ""; //Etunimi
$lname = ""; //Sukunimi 
$em = ""; //Sähköposti
$em2 = ""; //Sähköposti 2
$password = ""; //Salasana
$password2 = ""; //Salasana 2
$date = ""; //Kirjautumisen päivämäärä
$error_array = array(); //Sisältää error viestit

if(isset($_POST['register_button'])){

  //Rekisteröinti lomakkeen arvot

  //Etunimi
  $fname = strip_tags($_POST['reg_fname']); //Poistaa HTML tagit
  $fname = str_replace(' ', '', $fname); //Poistaa välilyönnit
  $fname = ucfirst(strtolower($fname)); //Eka kirjain isolla
  $_SESSION['reg_fname'] = $fname; //Tallentaa etunimen istunnon muuttujaan

  //Sukunimi
  $lname = strip_tags($_POST['reg_lname']); //Poistaa HTML tagit
  $lname = str_replace(' ', '', $lname); //Poistaa välilyönnit
  $lname = ucfirst(strtolower($lname)); //Eka kirjain isolla
  $_SESSION['reg_lname'] = $lname; //Tallentaa sukunimen istunnon muuttujaan


  //Sähköposti
  $em = strip_tags($_POST['reg_email']); //Poistaa HTML tagit
  $em = str_replace(' ', '', $em); //Poistaa välilyönnit
  $em = ucfirst(strtolower($em)); //Eka kirjain isolla
  $_SESSION['reg_email'] = $em; //Tallentaa sähköpostin istunnon muuttujaan

  //Sähköposti 2
  $em2 = strip_tags($_POST['reg_email2']); //Remove html tags
  $em2 = str_replace(' ', '', $em2); //remove spaces
  $em2 = ucfirst(strtolower($em2)); //Uppercase first letter
  $_SESSION['reg_email2'] = $em2; //Stores email2 into session variable

  //Salasana
  $password = strip_tags($_POST['reg_password']); //Poistaa HTML tagit
  $password2 = strip_tags($_POST['reg_password2']); //Poistaa HTML tagit

  $date = date("Y-m-d"); //Tämänhetkinen päivämäärä

  if($em == $em2) {
    //Tarkista, onko sähköpostiosoite kelvollisessa muodossa
    if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

      $em = filter_var($em, FILTER_VALIDATE_EMAIL);

      //Tarkista onko sähköposti jo olemassa
      $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

      //Laske palautettujen rivien lukumäärä
      $num_rows = mysqli_num_rows($e_check);

      if($num_rows > 0) {
        array_push($error_array, "Email already in use<br>");
      }

    }
    else {
      array_push($error_array, "Invalid email format<br>");
    }


  }
  else {
    array_push($error_array, "Emails don't match<br>");
  }


  if(strlen($fname) > 25 || strlen($fname) < 2) {
    array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
  }

  if(strlen($lname) > 25 || strlen($lname) < 2) {
    array_push($error_array,  "Your last name must be between 2 and 25 characters<br>");
  }

  if($password != $password2) {
    array_push($error_array,  "Your passwords do not match<br>");
  }


  else {
    if(preg_match('/[^A-Za-z0-9]/', $password)) {
      array_push($error_array, "Your password can only contain english characters or numbers<br>");
    }
  }

  if(strlen($password > 30 || strlen($password) < 5)) {
    array_push($error_array, "Your password must be betwen 5 and 30 characters<br>");
  }


  if(empty($error_array)) {
    $password = md5($password); //Encryptaa salasana ennen sen lähettämistä tietokantaan

    //Luo käyttäjätunnus yhdistämällä etunimi ja sukunimi
    $username = strtolower($fname . "_" . $lname);
    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");


    $i = 0; 
    // Jos käyttäjänimi on jo olemassa, lisää numero käyttäjänimen perään
    while(mysqli_num_rows($check_username_query) != 0) {
      $i++; //Lisää 1 to i
      $username = $username . "_" . $i;
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
    }

    //Profiilin kuvan määritys
    $rand = rand(1, 2); //Random numero 1 ja 2 välillä

    if($rand == 1)
      $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
    else if($rand == 2)
      $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";


    $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

    array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");

    //Tyhjennä istunnon muuttujat
    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] = "";
    $_SESSION['reg_email'] = "";
    $_SESSION['reg_email2'] = "";
  }

}
?>