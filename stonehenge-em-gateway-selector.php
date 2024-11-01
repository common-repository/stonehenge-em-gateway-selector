<?php
/**
 * Events Manager Pro - Payment Gateway Selector
 *
 * @package           Events Manager Pro - Payment Gateway Selector
 * @author            Stonehenge Creations <support@stonehengecreation.nl>
 * @copyright         2022 Stonehenge Creations
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Events Manager Pro - Payment Gateway Selector
 * Plugin URI:        https://wordpress.org/plugins/stonehenge-em-gateway-selector/
 * Description:       Easily set or unset your activated payment gateway(s) per individual single event with a simple checkbox.
 * Text Domain:       stonehenge-em-gateway-selector
 * Version:           2.0.4
 * Requires at least: 5.5
 * Tested up to:      6.0
 * Requires PHP:      7.3
 * Tested up to PHP:  8.0.14
 * Requires Plugins:  events-manager, events-manager-pro
 * Author:            Stonehenge Creations
 * Author URI:        https://www.stonehengecreations.nl/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Donate Link:       https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/
 */

// Exit if accessed directly.
if( !defined( 'ABSPATH' ) ) exit;

// Prevent errors if loaded too soon.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Stonehenge_EM_Pro_Gateway_Selector {

	/**
	 * @var sting $file The plugin file
	 */
	var $file;

	/**
	 * @var string $slug The plugin slug
	 */
	var $slug;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->file = plugin_basename( __FILE__ );
		$this->slug = dirname( $this->file );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10 , 2 );

		if( is_plugin_active( 'events-manager/events-manager.php' ) && is_plugin_active( 'events-manager-pro/events-manager-pro.php' ) ) {
			add_action( 'em_events_admin_bookings_footer', array( $this, 'render_gateway_selector' ), 50, 1 );
			add_filter( 'em_event_validate_meta', array( $this, 'validate_gateway_selection' ), 10, 2 );
			add_action( 'save_post', array( $this, 'save_gateway_selection' ), 10, 2 );
			add_action( 'em_booking_form_footer', array( $this, 'remove_deselected_gateways' ), 9, 1 );
		}
	}

	/**
	 * Load the plugin translations.
	 */
	public function load_plugin_textdomain() {
		$locale = determine_locale();
		load_default_textdomain( $locale );

		if( !load_textdomain( $this->slug, sprintf( '%1$s/%2$s/languages/%2$s-%3$s.mo', WP_PLUGIN_DIR, $this->slug, $locale ) ) )
			load_plugin_textdomain( $this->slug, false, '/languages' );
	}

	/**
	 * Adds addtional links in the WordPress Plugins Page.
	 *
	 * @param  array  $links Plugin links
	 * @param  string $file  Plugin file
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if( $this->file === $file ) {
			$links['reviews'] = sprintf( '<a href="https://wordpress.org/support/plugin/%s/reviews/#new-post" target="_blank">%s</a>', $this->slug, esc_html( __wp( 'Reviews' ) ) );
			$links['support'] = sprintf( '<a href="https://wordpress.org/support/plugins/%s" target="_blank">Plugin %s</a>', $this->slug, esc_html( __wp( 'Support' ) ) );
			$links['donate']  = sprintf( '<a href="https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/" target="_blank">%s</a>', esc_html( __wp( 'Donate to this plugin &#187;' ) ) );
		}

		return $links;
	}

	/**
	 * Adds a section for Payment Gateway selection in the Edit Event Page.
	 *
	 * @param object $EM_Event
	 */
	public function render_gateway_selector( $EM_Event ) {
		$gateways 	= EM_Gateways::active_gateways();
		$selected	= get_post_meta( $EM_Event->post_id, '_em_active_gateways', true );

		if( empty( $selected ) )
			$selected = array();

		?>
		<br style="clear:both">
		<h4><?php esc_html_e( 'Payment Options', $this->slug ); ?></h4>
		<div id="em-gateway-selector">
			<p><?php
				if( !empty( $gateways ) ) {
					echo sprintf( '<em>%s</em><br>', esc_html__( 'Please select which payment method(s) you would like to be available for this event.', $this->slug ) );

					wp_nonce_field( 'em_gateway_selector', 'em_gateway_selector_nonce' );

					asort( $gateways );
					foreach( $gateways as $id => $name ) {
						$checked = in_array( $id, $selected ) || empty( $EM_Event->event_id ) ? 'checked="checked"' : null;
						echo sprintf( '<label for="%1$s"><input type="checkbox" id="%1$s" name="_em_active_gateways[]" value="%1$s" %3$s>%2$s<label><br>', esc_attr( $id ), esc_html( $name ), esc_attr( $checked ) );
					}
				}
				else {
					echo sprintf( '<span style="color:#e14d43;"></span>', esc_html__( 'No Payment Gateways activated.', $this->slug ) );
				}
			?></p>
		</div>
		<?php
	}

	/**
	 * Validates the form input to prevent disabling all Payment Gateways.
	 *
	 * @param  bool   $result
	 * @parem  object $EM_Event
	 * @return bool
	 */
	public function validate_gateway_selection( $result, $EM_Event ) {
		// Check if bookings are enabled for this event.
		if( $EM_Event->event_rsvp && is_object( $EM_Event->bookings ) ) {
			if( !isset( $_POST['_em_active_gateways'] ) || empty( $_POST['_em_active_gateways'] ) ) {

				// Check if the tickets are free.
				foreach( $EM_Event->bookings->tickets as $EM_Ticket ) {
					if( (float) $EM_Ticket->ticket_price > 0 ) {
						$EM_Event->add_error( esc_html__( 'You need to select at least one Payment Gateway.', $this->slug ) );
						return false;
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Saves the selected gateways to the WordPress database.
	 *
	 * @param  int   $post_id
	 * @param  mixed WP_POST
	 * @return void
	 */
	public function save_gateway_selection( $post_id, $post ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || !in_array( $post->post_type, array( 'event', 'event-recurring' ) ) )
			return;

		if( isset( $_POST['em_gateway_selector_nonce'] ) && wp_verify_nonce( $_POST['em_gateway_selector_nonce'], 'em_gateway_selector' ) ) {
			if( isset( $_POST['_em_active_gateways'] ) && !empty( $_POST['_em_active_gateways'] ) ) {
				$selected = array_map( 'sanitize_text_field', wp_unslash( $_POST['_em_active_gateways'] ) );
				update_post_meta( $post->ID, '_em_active_gateways', $selected );
			}
			else {
				// Bookings are disabled, so delete left-overs.
				delete_post_meta( $post->ID, '_em_active_gateways' );
			}
		}

		return;
	}

	/**
	 * Removes the deselected active EM Gateways from the front-end EM Booking Form.
	 *
	 * @returns void
	 */
	public function remove_deselected_gateways( $EM_Event )  {
		global $EM_Gateways;

		$selected = get_post_meta( $EM_Event->post_id, '_em_active_gateways', true );

		if( !empty( $selected ) ) {
			foreach( $EM_Gateways as $id => $gateway ) {
				if( !in_array( $id, $selected ) ) {
					unset( $EM_Gateways[$id] );
				}
			}
		}
		return;
	}

}

/**
 * @ignore
 */
if( !function_exists( '__wp' ) ):
function __wp( $string = null ) {
	return translate( $string, 'default' );
}
endif;

add_action( 'plugins_loaded', function() {
	new Stonehenge_EM_Pro_Gateway_Selector();
}, 10, 1 );
