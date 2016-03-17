<?php 
add_action('admin_init', 'hhs_add_meta_boxes', 1);
function hhs_add_meta_boxes() {
    add_meta_box( 'repeatable-fields', 'Multiple Titles', 'hhs_repeatable_meta_box_display', 'post', 'normal', 'default');
}
function hhs_repeatable_meta_box_display() {
    global $post;
    $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
    wp_nonce_field( 'hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce' );
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '#add-row' ).on('click', function() {
            var row = $( '.empty-row.screen-reader-text' ).clone(true);
            row.removeClass( 'empty-row screen-reader-text' );
            row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
            return false;
        });
    
        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
    </script>
  
    <table id="repeatable-fieldset-one" width="100%">
    <thead>
        <tr>
            <th width="100%">Title</th>
            <th width="8%"></th>
        </tr>
    </thead>
    <tbody>
    <?php
    
    if ( $repeatable_fields ) :
    
    foreach ( $repeatable_fields as $field ) {
    ?>
    <tr>
        <td><input type="text" class="widefat" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>
        <td><a class="button remove-row" href="#">Remove</a></td>
    </tr>
    <?php
    }
    else :
    ?>
    <tr>
        <td><input type="text" class="widefat" name="name[]" /></td>
    
        <td><a class="button remove-row" href="#">Remove</a></td>
    </tr>
    <?php endif; ?>
    <tr class="empty-row screen-reader-text">
        <td><input type="text" class="widefat" name="name[]" /></td>
          
        <td><a class="button remove-row" href="#">Remove</a></td>
    </tr>
    </tbody>
    </table>
    
    <p><a id="add-row" class="button" href="#">Add new title</a></p>
    <?php
}
add_action('save_post', 'hhs_repeatable_meta_box_save');
function hhs_repeatable_meta_box_save($post_id) {
    if ( ! isset( $_POST['hhs_repeatable_meta_box_nonce'] ) ||
    ! wp_verify_nonce( $_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce' ) )
        return;
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    
    if (!current_user_can('edit_post', $post_id))
        return;
    
    $old = get_post_meta($post_id, 'repeatable_fields', true);
    $new = array();
    
    $names = $_POST['name'];
    $count = count( $names );
    
    for ( $i = 0; $i < $count; $i++ ) {
        if ( $names[$i] != '' ) :
            $new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
        endif;
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'repeatable_fields', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'repeatable_fields', $old );
}


?>