<?php /* Template Name: Testing */ ?>
<?php get_header(); ?>

<!-- Start Body Part -->

<!-- content    -->
<div class="content">
    <!-- section  end-->
    <div class="breadcrumbs fl-wrap">
        <div class="container">
            <div class="breadcrumbs-list">
                <a href="<?php echo get_site_url(); ?>">Home</a><span><?php the_title(); ?></span>
            </div>
        </div>
    </div>
    <!--section -->
    <section class="gray-bg small-top_padding stp-bot">
        <div class="hex-bg"><span class="hex hex1"></span><span class="hex hex2"></span><span class="hex hex3"></span></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-contact">
                        <h4>Get In Touch:</h4>
                        <!--form-->
                        <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="custom-form contact_form" id="test_contact_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Full Name*" required>
                                </div>
                                
                               <div class="col-lg-4">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Your Email*" required>
                                     <div id="send_otp_message"></div>
                                    <input type="button" name="send_email_otp" class="send_otp" onclick="sendEmailOTP()" value="Send Email OTP">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" name="email_otp" id="email_otp" onkeyup="verify_otp_fn()" placeholder="Enter Your Email OTP*" value="" required>
                                    <div id="verify_otp_message"></div>
                                </div>

                                <div class="col-lg-4">
                                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Mobile Number*" required>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="company" name="company" class="form-control" placeholder="Company Name*" required>
                                </div>
                        
                                <div class="col-lg-4">
                                    <input type="text" id="city" name="city" class="form-control" placeholder="City/Region*" required>
                                </div>
                                
                                <div class="col-lg-12">
                                    <input type="file" class="form-control" id="upload_file" name="upload_file" accept=".pdf, .docx" required>
                                </div>

                        
                                <div class="col-lg-12">
                                    <textarea id="message" name="message" class="form-control" placeholder="Write Your Message"></textarea>
                                </div>
                                
                                <div class="col-lg-12 pt-10">
                                   <small class="mt-10 d-block">
                                       <input type="checkbox" class="mr-5" name="trems_contdition" required>I hereby agree to receive emails, calls and SMS related to promotional activities and services, by or on behalf of TMHIN. I further agree to the terms & condition of <a target="_blank" href="<?php the_permalink(667); ?>">Privacy Policy</a>.
                                    </small>
                                </div>

                               <div class="col-lg-12">
                                    <div class="g-recaptcha" data-sitekey="6LeuYv4nAAAAAH3OU7AhZVOsEAK6VDugslFj8aG6"></div>
                                </div>
                                                           
                                <div class="col-lg-12">
                                    <input type="hidden" name="action" value="action_send_test_form_data">
                                    <button type="submit" class="btn float-none" id="submit-form-btn">SEND MESSAGE</button>
                                    <div class="response_message"><span id="request_response"></span></div>
                                </div>
                            </div>
                           
                        </form>
                        <!--//form-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--services end-->
</div>
<!-- content  end-->

<!-- End Body Part -->
<?php get_footer(); ?>
<!--form js start-->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
// Send OTP 
function sendEmailOTP() {
    if ($('#email').val() != '') {
        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'post',
            data: { action: 'email_send_otp_fn', email_id: jQuery('#email').val() },
            success: function (response) {
                // Response
                jQuery('#send_otp_message').html(response);
            }
        });
    }
}
    
// Verify OTP
function verify_otp_fn() {
    if ($('#email_otp').val() != '') {
        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'post',
            data: { action: 'data_verify_otp_fn', verify_otp: jQuery('#email_otp').val() },
            success: function (response) {
                // Response
                jQuery('#verify_otp_message').html(response);
                if (response === "OTP Verified") {
                    enableSubmitButton(); // Enable the submit button if OTP is verified
                }else{
                    disableSubmitButton(); // Disable the submit button if OTP is not verified
                } 
            }
        });
    }
}
    
function enableSubmitButton() {
    $('#submit-form-btn').prop('disabled', false);
    $('#submit-form-btn').css('cursor', 'pointer'); // Change the cursor to 'pointer' when enabled
}

function disableSubmitButton() {
    $('#submit-form-btn').prop('disabled', true);
    $('#submit-form-btn').css('cursor', 'not-allowed'); // Change the cursor to 'not-allowed' when disabled
}

//form
jQuery(document).ready(function($) {
    disableSubmitButton(); // Disable default submit
    $('#test_contact_form').on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#request_response').html(response);
                setTimeout(function() {
                    $('#send_otp_message').empty();
                    $('#verify_otp_message').empty();
                    $('#request_response').html('');
                    $('#test_contact_form')[0].reset();
                    disableSubmitButton(); // Disable the button again after resetting
                }, 8000); // 3000 milliseconds = 3 seconds
            }
        });
    });
});

</script>
<!--//form js start-->