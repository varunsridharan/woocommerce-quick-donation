<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WCQD_METABOX_Custom_Html_Field' ) )
{
	class WCQD_METABOX_Custom_Html_Field extends WCQD_METABOX_Field
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
			$html = ! empty( $field['std'] ) ? $field['std'] : '';
			if ( ! empty( $field['callback'] ) && is_callable( $field['callback'] ) )
			{
				$html = call_user_func_array( $field['callback'], array( $meta, $field ) );
			}
			return $html;
		}
	}
}
