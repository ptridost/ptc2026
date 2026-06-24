<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("dbcon.php");
require("dbcon_world.php");
require_once 'csrf.php';

// --- This top block contains all the final, secure PHP form processing logic ---
$showAlert = false;
$alertType = '';
$alertMessage = '';
$redirectUrl = '';

$allowed = ['PTC', 'GTC'];
$congress_type = isset($_GET['congress_type']) && in_array($_GET['congress_type'], $allowed)
        ? $_GET['congress_type']
        : 'PTC'; // Default to PTC if no valid type is provided

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $showAlert = true;
        $alertType = 'success';
        $alertMessage = 'Registration Successful! Redirecting to login...';
        $redirectUrl = 'loginn.php';
    } else {
        $errorType = $_GET['error'] ?? 'unknown';
        $showAlert = true;
        $alertType = 'error';
        switch ($errorType) {
            case 'username_taken': $alertMessage = 'Sorry, but that email/username is already taken.'; break;
            case 'recaptcha': $alertMessage = 'reCAPTCHA verification failed. Please try again.'; break;
            case 'csrf': $alertMessage = 'CSRF token validation failed. Please reload the page.'; break;
            default: $alertMessage = 'An unknown error occurred.'; break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        header('Location: registration.php?error=csrf&congress_type=' . $congress_type);
        exit;
    }

    $recaptchaSecret = '6LcNTkwrAAAAABZ89cmz5eKNQCI6mHLR5nTAvBQN';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        header('Location: registration.php?error=recaptcha&congress_type=' . $congress_type);
        exit;
    }

    $profession = (isset($_POST['profession']) && is_array($_POST['profession'])) ? implode(',', $_POST['profession']) : 'None';
    
    $firstname    = $_POST['firstname'];
    $middlename   = strtoupper($_POST['middlename']);
    $lastname     = $_POST['lastname'];
    $gender       = $_POST['gender'];
    $citizenship  = $_POST['citizenship'];
    $country      = $_POST['country'];
    $region       = $_POST['region'] ?? '';
    $cityprov     = $_POST['cityprovince'] ?? '';
    $age          = $_POST['age'];
    $username     = $_POST['username'];
    $birthdate    = $_POST['birthdate'];
    $password     = $_POST['password'];
    $congress = $_POST['congress_type'];
    $confirm_password = $_POST['confirm_password'];
    if ($password !== $confirm_password) {
        header('Location: registration.php?error=password_mismatch&congress_type=' . $congress_type);
        exit;
    }
   
    $birthdate = $_POST['birthdate'];
    $age_range_from_form = $_POST['age']; // e.g., "21-30"

    // Calculate the user's actual age from their birthdate
    $dob = new DateTime($birthdate);
    $now = new DateTime();
    $calculated_age = $now->diff($dob)->y;

    // A simple function to check if the calculated age falls within the selected range
    function isAgeInRange($age, $rangeStr) {
        if ($rangeStr == 'Less than 21') return $age < 21;
        if ($rangeStr == '61 and above') return $age >= 61;
        
        list($min, $max) = explode('-', $rangeStr);
        return $age >= (int)$min && $age <= (int)$max;
    }

    if (!isAgeInRange($calculated_age, $age_range_from_form)) {
        header('Location: registration.php?error=age_mismatch&congress_type=' . $congress_type);
        exit;
    }
    $organization = $_POST['organization'];
    $position     = $_POST['position'];
    
    $chem_prc     = $_POST['chem_prc'] ?? '';
    $chemen_prc   = $_POST['chemen_prc'] ?? '';
    $chemtech_prc = $_POST['chemtech_prc'] ?? '';
    $mech_prc     = $_POST['mech_prc'] ?? '';
    $agri_prc     = $_POST['agri_prc'] ?? '';
    $other_prof   = $_POST['other_profession'] ?? '';

    $stmt_check = $dbcon->prepare("SELECT username FROM participant WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        header('Location: registration.php?error=username_taken&congress_type=' . $congress_type);
        exit;
    }
    $stmt_check->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt_insert = $dbcon->prepare('INSERT INTO participant (
        firstName, middleName, lastName, Gender, birthdate, Citizenship, Country, Region, CityProvince,
        organization, position, Age, username, password, profession,
        Chem_prc, ChemEn_prc, ChemTech_prc, MechEn_prc, AgriEn_prc, other_profession, congress_type
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    
    $stmt_insert->bind_param("ssssssssssssssssssssss",
        $firstname, $middlename, $lastname, $gender, $birthdate, $citizenship, $country, $region, $cityprov,
        $organization, $position, $age, $username, $hashed_password, $profession,
        $chem_prc, $chemen_prc, $chemtech_prc, $mech_prc, $agri_prc, $other_prof, $congress
    );

    if ($stmt_insert->execute()) {
        $stmt_insert->close();
        header("Location: registration.php?status=success&congress_type=" . $congress_type);
        exit();
    } else {
        header('Location: registration.php?error=' . urlencode($stmt_insert->error) . '&congress_type=' . $congress_type);
        exit;
    }
  
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />
    <title><?= $congress_type === 'GTC' ? 'Global' : 'Philippine' ?> Textile Congress - Registration</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="./registration.css" />
    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
</head>

<body <?= $congress_type === 'GTC' ? 'class="gtc-2026"' : '' ?>>
    <?php include("header&footer/navbar.php"); ?>
    <div class="container registration-card">
        <!-- NEW: Left-side branding panel -->



        <div class="registration-info-panel"> 
            <div class="info-panel__logo">
                <?php if ($congress_type === 'GTC'): ?>
                <img src="./GDC/assets/img/GTC_LOGO_2026_RESIZED.png" alt="GTC Logo" style = "border-radius: 50%;">
                <?php else: ?>
                <img src="./img_outside/logo.png" alt="PTC Logo" style = "border-radius: 50%; max-height: 90%;">
                <?php endif; ?>
            </div>
            <div class="info-panel__text">
                <h1>Join the Congress</h1>
                <p>Become a part of the premier gathering of textile experts, researchers, and innovators in the
                    Philippines. Register now to secure your spot.</p>
            </div>
            <!-- ADD THIS NEW DIV FOR THE BOTTOM IMAGE 
            <div class="info-panel__image">
                <img src="./img_outside/test.png" alt="Event Image">
            </div>
            -->
        </div>

        <!-- NEW: Right-side form panel -->
        <div class="registration-form-panel">
            <form action="registration.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="congress_type" value="<?= htmlspecialchars($congress_type) ?>">
                <div class="container-fluid">
                    <div class="row gy-4">
                        <h1 class="header mb-4">Registration Form</h1>


                        <!-- Personal Information Section -->
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <input type="text" class="input-field" name="firstname" id="txtfield" placeholder=" "
                                    required>
                                <label for="txtfield" class="input-label">First Name</label>
                                <div class="validation-alert"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <input type="text" class="input-field" name="middlename" maxlength="1" id="txtfieldmi"
                                    placeholder=" ">
                                <label for="txtfieldmi" class="input-label">Middle Initial</label>
                                <div class="validation-alert"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <input type="text" class="input-field" name="lastname" id="txtfieldln" required
                                    placeholder=" ">
                                <label for="txtfieldln" class="input-label">Last Name</label>
                                <div class="validation-alert"></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="gender" id="gender" required>
                                    <option value="" disabled selected>Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Not Specify">Prefer not to mention</option>
                                </select>
                                <label for="gender" class="input-label">Sex</label>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="input-container datepicker">
                                <input type="text" class="input-field" name="birthdate" id="datepicker"
                                    autocomplete="off" required placeholder=" ">
                                <label for="datepicker" class="input-label">Birth Date</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="age" id="age" required>
                                    <option value="" disabled selected>Age Range</option>
                                    <option value="61 and above">61 and above</option>
                                    <option value="51-60">51-60 years old</option>
                                    <option value="41-50">41-50 years old</option>
                                    <option value="31-40">31-40 years old</option>
                                    <option value="21-30">21-30 years old</option>
                                    <option value="Less than 21">Less than 21</option>
                                </select>
                                <label for="age" class="input-label">Age Range</label>
                            </div>
                            <div id="age_validation_message" class="validation-message mt-1"></div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="citizenship" id="citizenship" required>
                                    <option value="" disabled selected>Nationality</option>
                                    <?php
                                            $nationalities = mysqli_query($dbcon_world, "SELECT nationality FROM countries ORDER BY CASE WHEN nationality = 'Filipino' THEN 1 ELSE 2 END, nationality;");
                                            if ($nationalities) {
                                                while ($row = mysqli_fetch_assoc($nationalities)) {
                                                    $nationality = htmlspecialchars($row['nationality']);
                                                    echo "<option value=\"$nationality\">$nationality</option>";
                                                }
                                            }
                                        ?>
                                </select>
                                <label for="citizenship" class="input-label">Nationality</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="country" id="country" required>
                                    <option value="" disabled selected>Country</option>
                                    <?php
                                            $result = mysqli_query($dbcon_world, "SELECT * FROM countries ORDER BY CASE WHEN iso2 = 'PH' THEN 1 ELSE 2 END, name;");
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<option value=\"{$row['iso2']}\">{$row['name']}</option>";
                                            }
                                        ?>
                                </select>
                                <label for="country" class="input-label">Country</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="region" id="region" required>
                                    <option value="" disabled selected>Region / State</option>
                                </select>
                                <label for="region" class="input-label">Region / State</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="input-container">
                                <select class="input-field" name="cityprovince" id="city" required>
                                    <option value="" disabled selected>City / Province</option>
                                </select>
                                <label for="city" class="input-label">City / Province</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="input-container">
                                <input type="text" class="input-field" name="organization" id="organization"
                                    placeholder=" " required>
                                <label for="organization" class="input-label">Institution</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="input-container">
                                <input type="text" class="input-field" name="position" id="position" placeholder=" "
                                    required>
                                <label for="position" class="input-label">Position</label>
                            </div>
                        </div>

                        <hr class="mt-4">
                        <h3 class="profession-header">Profession</h3>
                        <i class="profession-subheader">Please select all that apply. Required for CPD units.</i>

                        <div class="profession-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]" value="Chemist"
                                    id="prof_chemists">
                                <label class="form-check-label" for="prof_chemists">
                                    Chemist
                                    <span class = "small text-muted">
                                        (0510)
                                    </span>
                                </label>
                            </div>
                            <div class="prc-field">
                                <div class="input-container">
                                    <input type="text" class="input-field" name="chem_prc" id="chem_prc"
                                        placeholder=" ">
                                    <label for="chem_prc" class="input-label">Chemist PRC License #</label>
                                </div>
                            </div>
                        </div>
                        <div class="profession-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]"
                                    value="Chemical Engineer" id="prof_chem_eng">
                                <label class="form-check-label" for="prof_chem_eng">
                                    Chemical Engineer
                                    <span class = "small text-muted">
                                        (0410)
                                    </span>
                                </label>
                            </div>
                            <div class="prc-field">
                                <div class="input-container">
                                    <input type="text" class="input-field" name="chemen_prc" id="chemen_prc"
                                        placeholder=" ">
                                    <label for="chemen_prc" class="input-label">Chemical Eng. PRC License #</label>
                                </div>
                            </div>
                        </div>
                        <div class="profession-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]"
                                    value="Chemical Technician" id="prof_chem_tech">
                                <label class="form-check-label" for="prof_chem_tech">
                                    Chemical Technician
                                    <span class = "small text-muted">
                                        (0520)
                                     </span>
                                </label>
                            </div>
                            <div class="prc-field">
                                <div class="input-container">
                                    <input type="text" class="input-field" name="chemtech_prc" id="chemtech_prc"
                                        placeholder=" ">
                                    <label for="chemtech_prc" class="input-label">Chemical Tech. PRC License
                                        #</label>
                                </div>
                            </div>
                        </div>
                        <div class="profession-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]"
                                    value="Mechanical Engineer" id="prof_mech_eng">
                                <label class="form-check-label" for="prof_mech_eng">Mechanical Engineer</label>
                            </div>
                            <div class="prc-field">
                                <div class="input-container">
                                    <input type="text" class="input-field" name="mech_prc" id="mech_prc"
                                        placeholder=" ">
                                    <label for="mech_prc" class="input-label">Mechanical Eng. PRC License #</label>
                                </div>
                            </div>
                        </div>
                        <div class="profession-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]"
                                    value="Agricultural and Biosystems Engineer" id="prof_agri_eng">
                                <label class="form-check-label" for="prof_agri_eng">Agricultural & Biosystems
                                    Engineer</label>
                            </div>
                            <div class="prc-field">
                                <div class="input-container">
                                    <input type="text" class="input-field" name="agri_prc" id="agri_prc"
                                        placeholder=" ">
                                    <label for="agri_prc" class="input-label">Agri. & Biosystems Eng. PRC License
                                        #</label>
                                </div>
                            </div>
                        </div>
                        <div class="profession-group-other">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="profession[]" value="Others"
                                    id="prof_others">
                                <label class="form-check-label" for="prof_others">Others</label>
                            </div>
                            <div class="other-fields">
                                <div class="input-container">
                                    <input type="text" name="other_profession" class="input-field" placeholder=" ">
                                    <label class="input-label">Please specify profession</label>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4">
                        <h3 class="profession-header">Account Details</h3>

                        <div class="col-lg-6 col-md-12">
                            <div class="input-container">
                                <input type="email" class="input-field" name="username" id="username" placeholder=" "
                                    required>
                                <label for="username" class="input-label">Username / Email</label>
                                <p class="field-instruction">Note: This will be your Log in Email</p>
                                <div id="uname_response" class="validation-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12"></div>
                        <div class="col-lg-6 col-md-12">
                            <div class="input-container">
                                <input type="password" class="input-field password" name="password" id="password"
                                    placeholder=" " minlength="8" required>
                                <label for="password" class="input-label">Password</label>
                                <p class="field-instruction">At least 8 characters</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="input-container">
                                <input type="password" class="input-field password" name="confirm_password"
                                    id="confirm_password" placeholder=" " required>
                                <label for="confirm_password" class="input-label">Confirm Password</label>
                                <div id="message" class="validation-message"></div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="g-recaptcha" data-sitekey="6LcNTkwrAAAAABX9bm94Z0wq7noMopeZuQnQsEsR"></div>
                        </div>

                        <div class="col-12 my-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label terms-label" for="terms">I hereby agree with the <a
                                    target="_blank" href="https://ptri.dost.gov.ph/transparency/privacy-notice">Data
                                    Privacy Policy</a> and <a target="_blank"
                                    href="https://www.ptri.dost.gov.ph/transparency/about-us">Terms of
                                    Use</a></label>
                        </div>

                        <div class="col-12 my-4">
                            <input class="btn-submit" type="submit" name="submit" value="Register">
                            <p class="mt-3">Already have an account? <a href="./Loginn.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </form>


        </div>

    </div>
    <?php include("header&footer/footer.php"); ob_end_flush(); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="/PTC/app.js"></script>
    <script>
    $(document).ready(function() {

        // --- Logic for Floating Labels on Select Dropdowns ---
        $('.input-container select').on('change', function() {
            if ($(this).val()) {
                $(this).closest('.input-container').addClass('has-value');
            } else {
                $(this).closest('.input-container').removeClass('has-value');
            }
        }).trigger('change'); // Trigger on page load to handle browser autofill

        // --- Real-time Age vs. Birthdate Validation ---
        function validateAgeRange() {
            const birthdateStr = $('#datepicker').val();
            const ageRangeStr = $('#age').val();
            const messageContainer = $('#age_validation_message');

            // Only run the check if both fields have a value
            if (birthdateStr && ageRangeStr) {
                const birthDate = new Date(birthdateStr);
                const today = new Date();
                let calculatedAge = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    calculatedAge--;
                }

                // Helper function to check if the age is in the selected range
                const isAgeInRange = (age, range) => {
                    if (range === 'Less than 21') return age < 21;
                    if (range === '61 and above') return age >= 61;
                    const [min, max] = range.split('-').map(Number);
                    return age >= min && age <= max;
                };

                if (!isAgeInRange(calculatedAge, ageRangeStr)) {
                    // If the age does NOT match the range, show a warning
                    messageContainer.html(
                        "<span style='color: red;'>Warning: The selected age range does not match the calculated age of " +
                        calculatedAge + ".</span>");
                } else {
                    // If it matches, clear the message
                    messageContainer.html("");
                }
            } else {
                // If one of the fields is empty, clear the message
                messageContainer.html("");
            }
        }

        // Run the validation check whenever the birthdate or age range changes
        $('#datepicker, #age').on('change', validateAgeRange);


        // SweetAlert for status messages
        <?php
            if ($showAlert) {
                $jsRedirect = $redirectUrl ? "setTimeout(() => { window.location = '{$redirectUrl}'; }, 2000);" : "";
                echo "Swal.fire({
                    title: '" . ucfirst($alertType) . "!',
                    text: '{$alertMessage}',
                    icon: '{$alertType}',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => { {$jsRedirect} });";
            }
            ?>

        // Datepicker initialization
        $("#datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
            dateFormat: 'yy-mm-dd'
        });

        // --- Logic for Floating Labels on Select Dropdowns ---
        $('.input-container select').on('change', function() {
            if ($(this).val()) {
                $(this).closest('.input-container').addClass('has-value');
            } else {
                $(this).closest('.input-container').removeClass('has-value');
            }
        }).trigger('change'); // Trigger on page load for autofill

        // Consolidated name validation
        function validateNameField(inputElement) {
            const value = $(inputElement).val();
            const alertElement = $(inputElement).siblings('.validation-alert');
            const regex = /^[a-zA-Z\s.-]*$/;
            if (!regex.test(value) && value.length > 0) {
                alertElement.text("Alphabets only").show();
            } else {
                alertElement.hide();
            }
        }
        $('#txtfield, #txtfieldmi, #txtfieldln').on('keyup', function() {
            validateNameField(this);
        })

        // --- Logic to show/hide PRC fields ---
        $('.prc-field, .other-fields').hide(); // Hide all fields initially

        $('.profession-group .form-check-input, .profession-group-other .form-check-input').change(function() {
            // Find the container for the specific profession that was clicked
            let targetGroup = $(this).closest('.profession-group, .profession-group-other');
            // Find the license field ONLY within that specific container
            let targetField = targetGroup.find('.prc-field, .other-fields');

            // If the checkbox is checked, show the field, otherwise hide it
            if (this.checked) {
                targetField.slideDown();
            } else {
                targetField.slideUp();
                targetField.find('input').val(''); // Clear the input's value when hiding
            }
        });

        // Password match validation
        $('#password, #confirm_password').on('keyup', function() {
            const password = $('#password').val();
            const confirmPassword = $('#confirm_password').val();
            if (confirmPassword.length > 0) {
                if (password == confirmPassword) {
                    $('#message').html('✓ Passwords Match').css('color', 'green');
                } else {
                    $('#message').html('✗ Passwords Do Not Match').css('color', 'red');
                }
            } else {
                $('#message').html('');
            }
        });

        // AJAX for dependent dropdowns
        $('#country').change(function() {
            var country_iso = this.value;
            var target_url = (country_iso === "PH") ? "ajax/validateProvince.php" :
                "ajax/statevalidation.php";
            $('#region').html('<option value="" disabled selected>Loading...</option>').prop('disabled',
                true);
            $('#city').html('<option value="" disabled selected>City / Province</option>').prop(
                'disabled', true);
            $.ajax({
                url: target_url,
                type: "POST",
                data: {
                    isoID: country_iso
                },
                success: function(data) {
                    $("#region").html(data).prop('disabled', false);
                }
            });
        });
        $('#region').change(function() {
            var country_iso = $('#country').val();
            var region_code = this.value;
            var target_url = (country_iso === "PH") ? "ajax/validateCity.php" :
                "ajax/citiesvalidation.php";
            $('#city').html('<option value="" disabled selected>Loading...</option>').prop('disabled',
                true);
            $.ajax({
                url: target_url,
                type: "POST",
                data: {
                    ctid: region_code,
                    citycode: region_code
                },
                success: function(data) {
                    $("#city").html(data).prop('disabled', false);
                }
            });
        });

        // Logic to show/hide PRC fields
        $('.prc-field, .other-fields').hide();
        $('.profession-group .form-check-input, .profession-group-other .form-check-input').change(function() {
            let targetGroup = $(this).closest('.profession-group, .profession-group-other');
            let targetField = targetGroup.find('.prc-field, .other-fields');
            if (this.checked) {
                targetField.slideDown();
            } else {
                targetField.slideUp();
                targetField.find('input').val('');
            }
        });

        // Real-time username availability check
        $('#username').on('keyup', function() {
            var username = $(this).val();
            if (username.length > 4) {
                $.ajax({
                    url: 'check_username.php',
                    type: 'post',
                    data: {
                        username: username
                    },
                    success: function(response) {
                        $('#uname_response').html(response);
                    }
                });
            } else {
                $('#uname_response').html('');
            }
        });
    });
    </script>
</body>

</html>