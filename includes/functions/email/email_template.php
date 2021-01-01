<?php
 define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));
 class sendEmail{
	 
	 function __construct(){
		add_filter( 'wp_mail_from_name',  array($this,"wpb_sender_name"));
	 }
	 
	 public function send_email_woocommerce_style($email, $subject, $heading, $message) {
        // Get woocommerce mailer from instance
        $mailer = WC()->mailer();

        // Wrap message using woocommerce html email template
        $wrapped_message = $mailer->wrap_message($heading, $message);

        // Create new WC_Email instance
        $wc_email = new WC_Email;

        // Style the wrapped message with woocommerce inline styles
        $html_message = $wc_email->style_inline($wrapped_message);

        // Send the email using wordpress mail function
        wp_mail( $email, $subject, $html_message, HTML_EMAIL_HEADERS );
	   
	    WC()->session->__unset('id_session');

    }
	 
	public function wpb_sender_name( $original_email_from ) {
        return get_bloginfo('name');
    }

 }

   

