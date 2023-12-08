<?php
// Theme Definition
//Use Gmail SMTP in WordPress
function configure_gmail_smtp($phpmailer) {
    // Define Gmail SMTP settings
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.gmail.com';
    $phpmailer->Port       = 587;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username   = 'arundeltait@gmail.com'; // Your Gmail address
    $phpmailer->Password   = 'ehlt muwb tgqw epak'; // Your Gmail password
    $from = 'arundeltait@gmail.com';
    $FromName   = 'Arun';
    $phpmailer->setFrom($from, $FromName);

//Uncomment the following line if you want to use HTML in your emails
    // $phpmailer->isHTML(true);
    // Enable debugging (optional)
    // $phpmailer->SMTPDebug  = 2;
    return $phpmailer;
  
}
add_action('phpmailer_init', 'configure_gmail_smtp');


SMTP Configuration
function custom_wp_mail_smtp_settings( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'mail.deltaweb.in';
    $phpmailer->Port       = 587;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = 'arun@deltaweb.in';
    $phpmailer->Password   = 'Admin@#$%1234';
    $from = 'arun@deltaweb.in';
    $FromName   = 'Toyota';
    $phpmailer->setFrom($from, $FromName);

//Uncomment the following line if you want to use HTML in your emails
    // $phpmailer->isHTML(true);
    // Enable debugging (optional)
    // $phpmailer->SMTPDebug  = 2;
    return $phpmailer;

}
add_action( 'phpmailer_init', 'custom_wp_mail_smtp_settings' );




// Query Panel And all form otp verification code
// // Ajax function otp send
function email_send_otp_fn()
{
    if (isset($_SESSION['user_id_otp'])) {
        $user_id_otp = $_SESSION['user_id_otp'];
    } else {
        $user_id_otp = $_SESSION['user_id_otp'] = uniqid();
    }
    $user_specific_transient_name = 'user_transient_id_' . $user_id_otp;

    $random_number = wp_rand(999, 9999);
    $store_otp = array($random_number);
    $set_otp_specific_transient = set_transient($user_specific_transient_name, $store_otp, 1800); // value in second otp expire
    $get_otp_specific_transient = get_transient($user_specific_transient_name);

    $user_email = esc_attr($_POST['email_id']);
    $subject = 'Please verify your email address';
    $message = 'Please verify your email address by OTP : ' . $get_otp_specific_transient[0];
    $from = "arun@deltaweb.in";
    // Define the headers
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        "From: Your Name <$from>",
    );
    $sendmail = wp_mail($user_email, $subject, $message, $headers);

    if ($sendmail) {
        echo "Send OTP your Email, Please enter OTP and verify Email";
    } else {
        echo "OTP not send your Email.";
    }
    wp_die();
}
add_action('wp_ajax_email_send_otp_fn', 'email_send_otp_fn');
add_action('wp_ajax_nopriv_email_send_otp_fn', 'email_send_otp_fn');
// Ajax function otp verify
function data_verify_otp_fn()
{
    $user_id_otp = !isset($_SESSION['user_id_otp']) ? 0 : $_SESSION['user_id_otp'];
    $user_specific_transient_name = 'user_transient_id_' . $user_id_otp;
    $get_otp_specific_transient = get_transient($user_specific_transient_name);
    $email_otp = $get_otp_specific_transient[0];
    $verify_otp = esc_attr($_POST['verify_otp']);

    if ($verify_otp == $email_otp) {
        echo "OTP Verified"; // If they match, the OTP is verified
    } else {
        echo "OTP Not verified"; // If they don't match, the OTP is not verified
    }
    wp_die();
}
add_action('wp_ajax_data_verify_otp_fn', 'data_verify_otp_fn');
add_action('wp_ajax_nopriv_data_verify_otp_fn', 'data_verify_otp_fn');


function action_send_test_form_data()
{

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'action_send_test_form_data') {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $company = sanitize_text_field($_POST['company']);
        $city = sanitize_text_field($_POST['city']);
        $write_message = sanitize_text_field($_POST['message']);

        // Process file send
        $file_attachment = array();
        if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
            // File details
            $file = $_FILES['upload_file'];
            $file_name_email = $file['name'];
            $file_temp_path = $file['tmp_name'];

            // Add the file as an attachment
            $file_attachment[$file_name_email] = $file_temp_path;
        }

        // Verify reCAPTCHA response
        $recaptcha_secret_key = '6LeuYv4nAAAAACNIKbfNhBtQ8wUuFPm4qTZlDdp0'; // Replace with your actual secret key
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

        $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret_key}&response={$recaptcha_response}";
        $verify_response = file_get_contents($verify_url);
        $recaptcha_data = json_decode($verify_response);

        $to = "arun@deltaweb.in";
        $from = "arun@deltaweb.in";
        $subject_header = "Test Enquiry Form";

        $message = '<html><head>';
        $message .= '<style>
            .main_div p {
                font-weight: 700;
            }
            .main_div p span {
                font-weight: 400;
            }
            .main_div img {
                max-width: 100px;
                height: auto;
            }
            .mytable {
                border-collapse: collapse;
                width: 100%;
                border: 1px solid #ddd;
            }
            .mytable .heading_row {
                background-color: #f2f2f2;
            }
            .mytable th {
                border: 1px solid #ddd;
                padding: 5px;
                text-align: center;
            }
            .mytable td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }
          </style>';

        $message .= '</head><body>';
        $message .= '<div class="main_div">';
        $message .= '<div>';
        $message .= '<p>Dear Sir,</p>';
        $message .= '<p>Name: <span>' . $name . '</span></p>';
        $message .= '<p>Mobile No: <span>' . $phone . '</span></p>';
        $message .= '<p>Email: <span>' . $email . '</span></p>';
        $message .= '<p>Company: <span>' . $company . '</span></p>';
        $message .= '<p>City/Region: <span>' . $city . '</span></p>';
        $message .= '<p>Message: <span>' . $write_message . '</span></p>';
        $message .= '</div>';

        $message .= '<p>Best Regards,</p>';
        $message .= '<p>Arun Kumar.</p>';
        $message .= '</div>';
        $message .= '</body></html>';

        //reCAPTCHA check
        if (!$recaptcha_data->success) {
            // reCAPTCHA verification failed
            echo 'Captcha verification failed.';
            wp_die();
        }

        // Define the headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            "From: Your Name <$from>",
            "Reply-To: Your Name <$email>",
        );

        //Send the email  
        $sendmail = wp_mail($to, $subject_header, $message, implode("\r\n", $headers), $file_attachment);
        if ($sendmail) {
            echo 'The form was sent successfully!';
        } else {
            echo 'Email Not sent and Data not Inserted!';
        }
        wp_die();
    }
}
add_action('wp_ajax_action_send_test_form_data', 'action_send_test_form_data');
add_action('wp_ajax_nopriv_action_send_test_form_data', 'action_send_test_form_data');
