<?php
$id =wp_unique_id('bpldlDocumentLibrary-');

$form_data = null;

if ( ! empty( $attributes['selectedPostId'] ) ) {
    $post_id = (int) $attributes['selectedPostId'];

    if ( get_post_type( $post_id ) === 'document_library' ) {
        $form_data = get_post_meta( $post_id, 'bplde_settings', true );
        if (empty($form_data)) {
            $form_data = [];
        }
    }
}

?>
<div <?php echo get_block_wrapper_attributes(); ?> id='<?php echo esc_attr( $id ); ?>' data-post-data='<?php echo esc_attr(wp_json_encode($form_data)); ?>'>
    <?php echo render_skeleton_markup( $form_data ); ?>
</div>