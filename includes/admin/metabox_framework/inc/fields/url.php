<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Make sure "text" field is loaded
require_once WCQD_METABOX_FIELDS_DIR . 'text.php';

if ( ! class_exists( 'WCQD_METABOX_URL_Field' ) )
{
	class WCQD_METABOX_URL_Field extends WCQD_METABOX_Text_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			return sprintf(
				'<input type="url" class="rwmb-url" name="%s" id="%s" value="%s" size="%s" placeholder="%s"/>',
				$field['field_name'],
				$field['id'],
				$meta,
				$field['size'],
				$field['placeholder']
			);
		}

		/**
		 * Sanitize url
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field )
		{
			return is_array( $new ) ? array_map( 'esc_url', $new ) : esc_url( $new );
		}
	}
}
