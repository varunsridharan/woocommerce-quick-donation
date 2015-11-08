<table class="wc-order-totals">

<tr>
    <td class="label"><strong> <?php _e( 'Project Name', WC_QD_TXT ); ?> : </strong></td>
    <td class="total">
        <div class="view"><?php 

            $project_ID = WC_QD()->db()->get_project_id($post->ID);
            $title = get_the_title($project_ID);
            $link = get_permalink($project_ID); 
            printf('<a href="%s"> %s </a> ',$link,$title);
            ?></div>
         
    </td> 
</tr>
    
    <?php if ( wc_tax_enabled() ) : ?>
        <?php foreach ( $order->get_tax_totals() as $code => $tax ) : ?>
            <tr>
                <td class="label"><?php echo $tax->label; ?>:</td>
                <td class="total"><?php
                    if ( ( $refunded = $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ) ) > 0 ) {
                        echo '<del>' . strip_tags( $tax->formatted_amount ) . '</del> <ins>' . wc_price( $tax->amount - $refunded, array( 'currency' => $order->get_order_currency() ) ) . '</ins>';
                    } else {
                        echo $tax->formatted_amount;
                    }
                ?></td>
                <td width="1%"></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>


<tr>
    <td class="label"><strong> <?php _e( 'Donation Amount', WC_QD_TXT ); ?> : </strong></td>
    <td class="total">
        <div class="view"><?php echo $order->get_formatted_order_total(); ?></div>
        <div class="edit" style="display: none;">
            <input type="text" class="wc_input_price" id="_order_total" name="_order_total" placeholder="<?php echo wc_format_localized_price( 0 ); ?>" value="<?php echo ( isset( $data['_order_total'][0] ) ) ? esc_attr( wc_format_localized_price( $data['_order_total'][0] ) ) : ''; ?>" />
            <div class="clear"></div>
        </div>
    </td>
    <td><?php if ( $order->is_editable() ) : ?>
        <div class="wc-order-edit-line-item-actions"><a class="edit-order-item" href="#"></a>    </div><?php endif; ?>
    </td>
</tr>
</table>










<div class="order_data_column" style="display:none; visibility: hidden;">
<?php
echo '<div class="address">';
if ( $order->get_formatted_shipping_address() ) { echo '<p><strong>' . __( 'Address', WC_QD_TXT ) . ':</strong>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>'; } else { echo '<p class="none_set"><strong>' . __( 'Address', WC_QD_TXT ) . ':</strong> ' . __( 'No shipping address set.', WC_QD_TXT ) . '</p>'; }
if ( ! empty( self::$shipping_fields ) ) { foreach ( self::$shipping_fields as $key => $field ) { if ( isset( $field['show'] ) && false === $field['show'] ) { continue; }  $field_name = 'shipping_' . $key;  if ( ! empty( $order->$field_name ) ) { echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . make_clickable( esc_html( $order->$field_name ) ) . '</p>'; } } }
if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' == get_option( 'woocommerce_enable_order_comments', 'yes' ) ) && $post->post_excerpt ) { echo '<p><strong>' . __( 'Customer Provided Note', WC_QD_TXT ) . ':</strong> ' . nl2br( esc_html( $post->post_excerpt ) ) . '</p>'; }
echo '</div>';
echo '<div class="edit_address">';
if ( ! empty( self::$shipping_fields ) ) { foreach ( self::$shipping_fields as $key => $field ) { if ( ! isset( $field['type'] ) ) { $field['type'] = 'hidden'; } if ( ! isset( $field['id'] ) ){ $field['id'] = '_shipping_' . $key; }
switch ( $field['type'] ) {
case 'select' :
woocommerce_wp_select( $field );
break;
default :
woocommerce_wp_text_input( $field );
break;
} } }

if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' == get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) {
?>
<p class="form-field form-field-wide"><label for="excerpt"><?php _e( 'Customer Provided Note', WC_QD_TXT ) ?>:</label>
<textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt" placeholder="<?php esc_attr_e( 'Customer\'s notes about the order', WC_QD_TXT ); ?>"><?php echo wp_kses_post( $post->post_excerpt ); ?></textarea></p>
<?php
}

echo '</div>'; 
?>
</div>