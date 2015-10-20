<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Make sure "text" field is loaded
require_once WCQD_METABOX_FIELDS_DIR . 'text.php';

if ( ! class_exists( 'WCQD_METABOX_Password_Field' ) )
{
	class WCQD_METABOX_Password_Field extends WCQD_METABOX_Text_Field
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
				'<input type="password" class="rwmb-password" name="%s" id="%s" value="%s" size="%s" />',
				$field['field_name'],
				$field['id'],
				$meta,
				$field['size']
			);
		}
	}
}
