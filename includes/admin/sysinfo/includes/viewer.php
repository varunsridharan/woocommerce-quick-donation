<?php
/**
 * Handles Remote Viewing of System Status
 *
 * @package     SSI
 * @subpackage  Classes/Viewer
 * @author      John Regan
 * @since       1.0
 */

class Simple_System_Status_Viewer {

	/**
	 * Renders Remote Viewing portion of Plugin Settings Page
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	static function remote_viewing_section() {
		$value = get_option( 'simple_system_status_remote_url' );
		$url   = home_url() . '/?simple_system_status=' . $value;
		?>
		<p><?php _e( 'Users with this URL can view a plain-text version of your System Status.<br />This link can be handy in support forums, as access to this information can be removed after you receive the help you need.<br />Generating a new URL will safely void access to all who have the existing URL.', WC_QD_TXT ) ?></p>
		<p><input type="text" readonly="readonly" class="sss-url sss-url-text" onclick="this.focus();this.select()" value="<?php echo esc_url( $url ) ?>" title="<?php _e( 'To copy the System Status, click below then press Ctrl + C (PC) or Cmd + C (Mac).', WC_QD_TXT ); ?>" />&nbsp;&nbsp;<a href="<?php echo esc_url( $url ) ?>" target="_blank" class="sss-tiny sss-url-text-link"><?php _e( 'test url', WC_QD_TXT ) ?></a></p>
		<p class="submit">
			<input type="submit" onClick="return false;" class="button-secondary" name="generate-new-url" value="<?php _e( 'Generate New URL', WC_QD_TXT ) ?>" />
		</p>
		<?php
	}

	/**
	 * Renders Remote View using $_GET value
	 *
	 * @since   1.0
	 * @action  template_redirect
	 *
	 * @return  void
	 */
	static function remote_view() {
		if ( ! isset( $_GET['simple_system_status'] ) || empty( $_GET['simple_system_status'] ) ) {
			return;
		}

		$query_value = $_GET['simple_system_status'];
		$value       = get_option( 'simple_system_status_remote_url' );

		echo '<pre>';
		if ( $query_value == $value ) {
			echo esc_html( Simple_System_Status::display() );
			exit();
		} else {
			exit( 'Invalid System Status URL.' );
		}
		echo '</pre>';
	}

}