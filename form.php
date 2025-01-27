<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$fullname = $email = $gender = $country = $programming_language = "";
$fullname_err = $email_err = $gender_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate full_name
  if(empty(trim($_POST["full_name"]))){
      $fullname_err = "Please enter a full name.";
  } elseif(!preg_match_all('/^[A-Za-z\p{Greek}\s]+/u', trim($_POST["full_name"]))){
      $fullname_err = "Full name can only contain letters";
  } else{
      // Prepare a select statement
      $sql = "SELECT id FROM forms WHERE full_name = ?";

      if($stmt = mysqli_prepare($conn, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_fullname);

          // Set parameters
          $param_fullname = trim($_POST["full_name"]);

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);
              $fullname = trim($_POST["full_name"]);

          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
  }

  // Validate Email
  if(empty(trim($_POST["email"]))){
      $email_err = "Please enter an Email.";
  } else{
      // Prepare a select statement
      $sql = "SELECT id FROM forms WHERE email = ?";

      if($stmt = mysqli_prepare($conn, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_email);

          // Set parameters
          $param_email = trim($_POST["email"]);

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);
              $email = trim($_POST["email"]);

          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
  }

  // Validate gender
  if (!isset($_POST['sex'])){
    $gender_err = "You should pick a gender.";
  } else{
      // Prepare a select statement
      $sql = "SELECT id FROM forms WHERE gender = ?";

      if($stmt = mysqli_prepare($conn, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_gender);

          // Set parameters
          $param_gender = trim($_POST["sex"]);

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);
              $gender = trim($_POST["sex"]);

          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
  }

  //Validate country

  // Prepare a select statement
  $sql = "SELECT id FROM forms WHERE country = ?";

  if($stmt = mysqli_prepare($conn, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_country);

      // Set parameters
      $param_country = trim($_POST["country"]);

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          /* store result */
          mysqli_stmt_store_result($stmt);
          $country = trim($_POST["country"]);

      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  //Validate programming languages
  $sql = "SELECT id FROM forms WHERE programming_language = ?";

  if($stmt = mysqli_prepare($conn, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_programming_language);

      // Set parameters
      $param_programming_language = trim($_POST["programming_language"]);

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          /* store result */
          mysqli_stmt_store_result($stmt);
          $programming_language = trim($_POST["programming_language"]);

      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }


  // Check input errors before inserting in database
  if(empty($fullname_err) && empty($email_err) && empty($gender_err)){

      // Prepare an insert statement
      $sql = "INSERT INTO forms (full_name, email, gender, country, programming_language) VALUES (?, ?, ?, ?, ?)";

      if($stmt = mysqli_prepare($conn, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sssss", $param_fullname, $param_email, $param_gender, $param_country, $param_programming_language);

          // Set parameters
          $param_fullname = $fullname;
          $param_email = $email;
          $param_gender = $gender;
          $param_country = $country;
          $param_programming_language = $programming_language;

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              // Redirect to login page
              echo "<br>Form submited successfully!";
          } else{
              echo "<br>Error submiting the form: " . $conn->error;
          }

          // Close statement
          mysqli_stmt_close($stmt);
        }
  }

  // Close connection
  mysqli_close($conn);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{ width: 360px; padding: 20px; }
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>

    <h2 style="font-weight:bold;margin:20px;">Παρακαλώ συμπληρώστε τα παρακάτω στοιχεία</h2>

    <form style="margin:40px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
          <label style="font-weight:bold;">Ονοματεπώνυμο:</label><br>
          <input style="width:350px;margin: 0 auto;float: none;" type="text" name="full_name" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>">
          <span class="invalid-feedback"><?php echo $fullname_err; ?></span>
      </div>
      <div class="form-group">
          <label style="font-weight:bold;">Email Επικοινωνίας:</label><br>
          <input style="width:350px;margin: 0 auto;float: none;" type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
          <span class="invalid-feedback"><?php echo $email_err; ?></span>
      </div>
      <div class="form-group">
        <label style="font-weight:bold;">Είστε:</label>
        <input type="radio" name="sex" class="<?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>" value="male"> Άνδρας
        <input type="radio" name="sex" class="<?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>" value="female"> Γυναίκα
        <span class="invalid-feedback"><?php echo $gender_err; ?></span>
      </div>
      <div class="form-group">
        <label style="font-weight:bold;">Προέρχεστε από:</label><br>
        <select id="country" name="country" class="<?php echo (!empty($country_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $country; ?>">
           <option value="Afganistan">Afghanistan</option>
           <option value="Albania">Albania</option>
           <option value="Algeria">Algeria</option>
           <option value="American Samoa">American Samoa</option>
           <option value="Andorra">Andorra</option>
           <option value="Angola">Angola</option>
           <option value="Anguilla">Anguilla</option>
           <option value="Antigua & Barbuda">Antigua & Barbuda</option>
           <option value="Argentina">Argentina</option>
           <option value="Armenia">Armenia</option>
           <option value="Aruba">Aruba</option>
           <option value="Australia">Australia</option>
           <option value="Austria">Austria</option>
           <option value="Azerbaijan">Azerbaijan</option>
           <option value="Bahamas">Bahamas</option>
           <option value="Bahrain">Bahrain</option>
           <option value="Bangladesh">Bangladesh</option>
           <option value="Barbados">Barbados</option>
           <option value="Belarus">Belarus</option>
           <option value="Belgium">Belgium</option>
           <option value="Belize">Belize</option>
           <option value="Benin">Benin</option>
           <option value="Bermuda">Bermuda</option>
           <option value="Bhutan">Bhutan</option>
           <option value="Bolivia">Bolivia</option>
           <option value="Bonaire">Bonaire</option>
           <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
           <option value="Botswana">Botswana</option>
           <option value="Brazil">Brazil</option>
           <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
           <option value="Brunei">Brunei</option>
           <option value="Bulgaria">Bulgaria</option>
           <option value="Burkina Faso">Burkina Faso</option>
           <option value="Burundi">Burundi</option>
           <option value="Cambodia">Cambodia</option>
           <option value="Cameroon">Cameroon</option>
           <option value="Canada">Canada</option>
           <option value="Canary Islands">Canary Islands</option>
           <option value="Cape Verde">Cape Verde</option>
           <option value="Cayman Islands">Cayman Islands</option>
           <option value="Central African Republic">Central African Republic</option>
           <option value="Chad">Chad</option>
           <option value="Channel Islands">Channel Islands</option>
           <option value="Chile">Chile</option>
           <option value="China">China</option>
           <option value="Christmas Island">Christmas Island</option>
           <option value="Cocos Island">Cocos Island</option>
           <option value="Colombia">Colombia</option>
           <option value="Comoros">Comoros</option>
           <option value="Congo">Congo</option>
           <option value="Cook Islands">Cook Islands</option>
           <option value="Costa Rica">Costa Rica</option>
           <option value="Cote DIvoire">Cote DIvoire</option>
           <option value="Croatia">Croatia</option>
           <option value="Cuba">Cuba</option>
           <option value="Curaco">Curacao</option>
           <option value="Cyprus">Cyprus</option>
           <option value="Czech Republic">Czech Republic</option>
           <option value="Denmark">Denmark</option>
           <option value="Djibouti">Djibouti</option>
           <option value="Dominica">Dominica</option>
           <option value="Dominican Republic">Dominican Republic</option>
           <option value="East Timor">East Timor</option>
           <option value="Ecuador">Ecuador</option>
           <option value="Egypt">Egypt</option>
           <option value="El Salvador">El Salvador</option>
           <option value="Equatorial Guinea">Equatorial Guinea</option>
           <option value="Eritrea">Eritrea</option>
           <option value="Estonia">Estonia</option>
           <option value="Ethiopia">Ethiopia</option>
           <option value="Falkland Islands">Falkland Islands</option>
           <option value="Faroe Islands">Faroe Islands</option>
           <option value="Fiji">Fiji</option>
           <option value="Finland">Finland</option>
           <option value="France">France</option>
           <option value="French Guiana">French Guiana</option>
           <option value="French Polynesia">French Polynesia</option>
           <option value="French Southern Ter">French Southern Ter</option>
           <option value="Gabon">Gabon</option>
           <option value="Gambia">Gambia</option>
           <option value="Georgia">Georgia</option>
           <option value="Germany">Germany</option>
           <option value="Ghana">Ghana</option>
           <option value="Gibraltar">Gibraltar</option>
           <option value="Great Britain">Great Britain</option>
           <option value="Greece">Greece</option>
           <option value="Greenland">Greenland</option>
           <option value="Grenada">Grenada</option>
           <option value="Guadeloupe">Guadeloupe</option>
           <option value="Guam">Guam</option>
           <option value="Guatemala">Guatemala</option>
           <option value="Guinea">Guinea</option>
           <option value="Guyana">Guyana</option>
           <option value="Haiti">Haiti</option>
           <option value="Hawaii">Hawaii</option>
           <option value="Honduras">Honduras</option>
           <option value="Hong Kong">Hong Kong</option>
           <option value="Hungary">Hungary</option>
           <option value="Iceland">Iceland</option>
           <option value="Indonesia">Indonesia</option>
           <option value="India">India</option>
           <option value="Iran">Iran</option>
           <option value="Iraq">Iraq</option>
           <option value="Ireland">Ireland</option>
           <option value="Isle of Man">Isle of Man</option>
           <option value="Israel">Israel</option>
           <option value="Italy">Italy</option>
           <option value="Jamaica">Jamaica</option>
           <option value="Japan">Japan</option>
           <option value="Jordan">Jordan</option>
           <option value="Kazakhstan">Kazakhstan</option>
           <option value="Kenya">Kenya</option>
           <option value="Kiribati">Kiribati</option>
           <option value="Korea North">Korea North</option>
           <option value="Korea Sout">Korea South</option>
           <option value="Kuwait">Kuwait</option>
           <option value="Kyrgyzstan">Kyrgyzstan</option>
           <option value="Laos">Laos</option>
           <option value="Latvia">Latvia</option>
           <option value="Lebanon">Lebanon</option>
           <option value="Lesotho">Lesotho</option>
           <option value="Liberia">Liberia</option>
           <option value="Libya">Libya</option>
           <option value="Liechtenstein">Liechtenstein</option>
           <option value="Lithuania">Lithuania</option>
           <option value="Luxembourg">Luxembourg</option>
           <option value="Macau">Macau</option>
           <option value="Macedonia">Macedonia</option>
           <option value="Madagascar">Madagascar</option>
           <option value="Malaysia">Malaysia</option>
           <option value="Malawi">Malawi</option>
           <option value="Maldives">Maldives</option>
           <option value="Mali">Mali</option>
           <option value="Malta">Malta</option>
           <option value="Marshall Islands">Marshall Islands</option>
           <option value="Martinique">Martinique</option>
           <option value="Mauritania">Mauritania</option>
           <option value="Mauritius">Mauritius</option>
           <option value="Mayotte">Mayotte</option>
           <option value="Mexico">Mexico</option>
           <option value="Midway Islands">Midway Islands</option>
           <option value="Moldova">Moldova</option>
           <option value="Monaco">Monaco</option>
           <option value="Mongolia">Mongolia</option>
           <option value="Montserrat">Montserrat</option>
           <option value="Morocco">Morocco</option>
           <option value="Mozambique">Mozambique</option>
           <option value="Myanmar">Myanmar</option>
           <option value="Nambia">Nambia</option>
           <option value="Nauru">Nauru</option>
           <option value="Nepal">Nepal</option>
           <option value="Netherland Antilles">Netherland Antilles</option>
           <option value="Netherlands">Netherlands (Holland, Europe)</option>
           <option value="Nevis">Nevis</option>
           <option value="New Caledonia">New Caledonia</option>
           <option value="New Zealand">New Zealand</option>
           <option value="Nicaragua">Nicaragua</option>
           <option value="Niger">Niger</option>
           <option value="Nigeria">Nigeria</option>
           <option value="Niue">Niue</option>
           <option value="Norfolk Island">Norfolk Island</option>
           <option value="Norway">Norway</option>
           <option value="Oman">Oman</option>
           <option value="Pakistan">Pakistan</option>
           <option value="Palau Island">Palau Island</option>
           <option value="Palestine">Palestine</option>
           <option value="Panama">Panama</option>
           <option value="Papua New Guinea">Papua New Guinea</option>
           <option value="Paraguay">Paraguay</option>
           <option value="Peru">Peru</option>
           <option value="Phillipines">Philippines</option>
           <option value="Pitcairn Island">Pitcairn Island</option>
           <option value="Poland">Poland</option>
           <option value="Portugal">Portugal</option>
           <option value="Puerto Rico">Puerto Rico</option>
           <option value="Qatar">Qatar</option>
           <option value="Republic of Montenegro">Republic of Montenegro</option>
           <option value="Republic of Serbia">Republic of Serbia</option>
           <option value="Reunion">Reunion</option>
           <option value="Romania">Romania</option>
           <option value="Russia">Russia</option>
           <option value="Rwanda">Rwanda</option>
           <option value="St Barthelemy">St Barthelemy</option>
           <option value="St Eustatius">St Eustatius</option>
           <option value="St Helena">St Helena</option>
           <option value="St Kitts-Nevis">St Kitts-Nevis</option>
           <option value="St Lucia">St Lucia</option>
           <option value="St Maarten">St Maarten</option>
           <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
           <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
           <option value="Saipan">Saipan</option>
           <option value="Samoa">Samoa</option>
           <option value="Samoa American">Samoa American</option>
           <option value="San Marino">San Marino</option>
           <option value="Sao Tome & Principe">Sao Tome & Principe</option>
           <option value="Saudi Arabia">Saudi Arabia</option>
           <option value="Senegal">Senegal</option>
           <option value="Seychelles">Seychelles</option>
           <option value="Sierra Leone">Sierra Leone</option>
           <option value="Singapore">Singapore</option>
           <option value="Slovakia">Slovakia</option>
           <option value="Slovenia">Slovenia</option>
           <option value="Solomon Islands">Solomon Islands</option>
           <option value="Somalia">Somalia</option>
           <option value="South Africa">South Africa</option>
           <option value="Spain">Spain</option>
           <option value="Sri Lanka">Sri Lanka</option>
           <option value="Sudan">Sudan</option>
           <option value="Suriname">Suriname</option>
           <option value="Swaziland">Swaziland</option>
           <option value="Sweden">Sweden</option>
           <option value="Switzerland">Switzerland</option>
           <option value="Syria">Syria</option>
           <option value="Tahiti">Tahiti</option>
           <option value="Taiwan">Taiwan</option>
           <option value="Tajikistan">Tajikistan</option>
           <option value="Tanzania">Tanzania</option>
           <option value="Thailand">Thailand</option>
           <option value="Togo">Togo</option>
           <option value="Tokelau">Tokelau</option>
           <option value="Tonga">Tonga</option>
           <option value="Trinidad & Tobago">Trinidad & Tobago</option>
           <option value="Tunisia">Tunisia</option>
           <option value="Turkey">Turkey</option>
           <option value="Turkmenistan">Turkmenistan</option>
           <option value="Turks & Caicos Is">Turks & Caicos Is</option>
           <option value="Tuvalu">Tuvalu</option>
           <option value="Uganda">Uganda</option>
           <option value="United Kingdom">United Kingdom</option>
           <option value="Ukraine">Ukraine</option>
           <option value="United Arab Erimates">United Arab Emirates</option>
           <option value="United States of America">United States of America</option>
           <option value="Uraguay">Uruguay</option>
           <option value="Uzbekistan">Uzbekistan</option>
           <option value="Vanuatu">Vanuatu</option>
           <option value="Vatican City State">Vatican City State</option>
           <option value="Venezuela">Venezuela</option>
           <option value="Vietnam">Vietnam</option>
           <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
           <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
           <option value="Wake Island">Wake Island</option>
           <option value="Wallis & Futana Is">Wallis & Futana Is</option>
           <option value="Yemen">Yemen</option>
           <option value="Zaire">Zaire</option>
           <option value="Zambia">Zambia</option>
           <option value="Zimbabwe">Zimbabwe</option>
        </select>
        <span class="invalid-feedback"><?php echo $country_err; ?></span>
      </div>
      <div class="form-group">
        <label style="font-weight:bold;">Ποια είναι η αγαπημένη σας γλώσσα προγραμματισμού: </label><br>
        <select id="programming_language" name="programming_language" value="<?php echo $programming_languages; ?>">
          <option value="Java">Java</option>
          <option value="Python">Python</option>
          <option value="C++">C++</option>
          <option value="Ruby">Ruby</option>
          <option value="Javascript">Javascript</option>
          <option value="PHP">PHP</option>
          <option value="C#">C#</option>
          <option value="R">R</option>
        </select>
      </div>
      <div class="form-group">
          <input class="btn btn-primary" type="submit" value="Submit">
      </div>
    </form>
</body>
</html>
