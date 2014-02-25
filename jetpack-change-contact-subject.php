<?php
/*
Plugin Name: Jetpack - Change Contact Subject
Description: Change the subject line of emails sent by Jetpack contact foms
Version: 1.0
Author: Katherine Semel
Author URI: http://bonsaibudget.com/
*/

if ( ! class_exists( 'JetpackChangeContactSubject' ) ) {

class JetpackChangeContactSubject {

    function JetpackChangeContactSubject() {

        add_filter( 'contact_form_subject', array( $this, 'change_contact_form_subject') );

        add_action( 'grunion_pre_message_sent', array( $this, 'filter_message_pre_send'), 1, 3 );
    }

    function change_contact_form_subject( $contact_form_subject ) {
        return $contact_form_subject;
    }

    function filter_message_pre_send( $post_id, $all_values, $extra_values ) {

        $feedback_author = get_post_meta( $post_id, '_feedback_author', true );
        $feedback_author_email = get_post_meta( $post_id, '_feedback_author_email', true );
        $feedback_subject = get_post_meta( $post_id, '_feedback_subject', true );
        $feedback_email_array = maybe_unserialize( get_post_meta( $post_id, '_feedback_email' ), true );

        $feedback_subject = $feedback_subject . ' from ' . $feedback_author . ' (' . $feedback_author_email . ')';

        update_post_meta( $post_id, '_feedback_subject', addslashes( $feedback_subject ) );

        update_post_meta( $post_id, '_feedback_email', array(
            'to'      => $feedback_email_array['to'],
            'subject' => $feedback_subject,
            'message' => $feedback_email_array['message'],
            'headers' => $feedback_email_array['headers']
        ) );

        return;
    }
}

$JetpackChangeContactSubject = new JetpackChangeContactSubject();

}

?>