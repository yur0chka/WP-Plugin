<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
// Initialize success message
$success_message = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST[$this->plugin_name])) {
    // Validate and sanitize the input data
    $input_data = isset($_POST[$this->plugin_name]) ? $_POST[$this->plugin_name] : array();
    $footer_text = isset($input_data['footer_text']) ? sanitize_text_field($input_data['footer_text']) : '';

    // Save the data to the database
    if (!empty($footer_text)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'footer_data'; // assuming the table name is 'wp_footer_data'

        // Prepare data to be inserted
        $data = array(
            'footer_text' => $footer_text,
        );

        // Insert data into the database
        $wpdb->insert($table_name, $data);

        // Check if the data was inserted successfully
        if ($wpdb->insert_id) {
            // Set success message
            $success_message = __('Data updated successfully.', $this->plugin_name);

            // Send email notification
           $subject = 'Data Updated Successfully';
			$message = 'The footer text has been updated to: ' . $footer_text;
			$recipient_email = ''; // Specify the recipient email address here

			$mail_sent = wp_mail($recipient_email, $subject, $message);

if ($mail_sent) {
    echo 'Email sent successfully.';
} else {
    echo 'Failed to send email.';
}
        } else {
            // Set error message if data insertion failed
            $success_message = __('Error: Data could not be updated.', $this->plugin_name);
        }

        // Optionally, you can redirect the user after saving the data
        // wp_redirect(admin_url('options-general.php?page=' . $this->plugin_name));
        // exit; // Always exit after a redirect
    }
}
?>

<form method="post" name="my_options" action="">
    <?php
    // Output nonce, action, and option_page fields for security
    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
    ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php if (!empty($success_message)) : ?>
        <div id="message" class="updated notice is-dismissible">
            <p><?php echo $success_message; ?></p>
        </div>
    <?php endif; ?>

    <fieldset>
        <legend class="screen-reader-text"><span><?php _e('Text in the footer', $this->plugin_name); ?></span></legend>
        <label for="<?php echo $this->plugin_name; ?>-footer_text">
            <span><?php esc_attr_e('Text in the footer', $this->plugin_name); ?></span>
        </label>
        <input type="text"
               class="regular-text" id="<?php echo $this->plugin_name; ?>-footer_text"
               name="<?php echo $this->plugin_name; ?>[footer_text]"
               value="<?php echo esc_attr($footer_text); ?>"
               placeholder="<?php esc_attr_e('Text in the footer', $this->plugin_name); ?>"
        />
    </fieldset>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', TRUE); ?>

</form>
