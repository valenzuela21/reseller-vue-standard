<?php
class upload_image_product {

  public function __construct() {
    //
  }

 /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
 */
 public function init() {
   add_action( 'product_cat_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 );
   add_action( 'created_product_cat', array ( $this, 'save_category_image' ), 10, 2 );
   add_action( 'product_cat_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2 );
   add_action( 'edited_product_cat', array ( $this, 'updated_category_image' ), 10, 2 );
   add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
   add_action( 'admin_footer', array ( $this, 'add_script' ) );

 }

public function load_media() {
 wp_enqueue_media();
}

 /*
  * Add a form field in the new category page
  * @since 1.0.0
 */
 public function add_category_image ( $taxonomy ) { ?>
<div class="form-field term-group">
    <label for="product_cat-image-id"><?php _e('Image Banner', 'master-tienda'); ?></label>
    <input type="hidden" id="product_cat-image-id" name="product_cat-image-id" class="custom_media_url" value="">
    <div id="product_cat-image-wrapper"></div>
    <p>
        <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button"
            name="ct_tax_media_button" value="<?php _e( 'Add Image', 'master-tienda' ); ?>" />
        <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove"
            name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'master-tienda' ); ?>" />
    </p>
</div>
<?php
 }

 /*
  * Save the form field
  * @since 1.0.0
 */
 public function save_category_image ( $term_id, $att_id ) {
   if( isset( $_POST['product_cat-image-id'] ) && '' !== $_POST['product_cat-image-id'] ){
     $image = $_POST['product_cat-image-id'];
     add_term_meta( $term_id, 'product_cat-image-id', $image, true );
   }
 }

 /*
  * Edit the form field
  * @since 1.0.0
 */
 public function update_category_image ( $term, $taxonomy ) { ?>
<tr class="form-field term-group-wrap">
    <th scope="row">
        <label for="category-image-id"><?php _e( 'Imagen Baner', 'master-tienda' ); ?></label>
    </th>
    <td>
        <?php $image_id = get_term_meta ( $term -> term_id, 'product_cat-image-id', true ); ?>
        <input type="hidden" id="product_cat-image-id" name="product_cat-image-id" value="<?php echo $image_id; ?>">
        <div id="product_cat-image-wrapper">
            <?php if ( $image_id ) { ?>
            <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
            <?php } ?>
        </div>
        <p>
            <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button"
                name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
            <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove"
                name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
        </p>
    </td>
</tr>
<?php
 }

/*
 * Update the form field value
 * @since 1.0.0
 */
 public function updated_category_image ( $term_id, $tt_id ) {
   if( isset( $_POST['product_cat-image-id'] ) && '' !== $_POST['product_cat-image-id'] ){
     $image = $_POST['product_cat-image-id'];
     update_term_meta ( $term_id, 'product_cat-image-id', $image );
   } else {
     update_term_meta ( $term_id, 'product_cat-image-id', '' );
   }
 }

/*
 * Add script
 * @since 1.0.0
 */
 public function add_script() { ?>
<script>
jQuery(document).ready(function($) {
    function ct_media_upload(button_class) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click', button_class, function(e) {
            var button_id = '#' + $(this).attr('id');
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment) {
                if (_custom_media) {
                    $('#product_cat-image-id').val(attachment.id);
                    $('#product_cat-image-wrapper').html(
                        '<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />'
                    );
                    $('#product_cat-image-wrapper .custom_media_image').attr('src', attachment.url)
                        .css('display', 'block');
                } else {
                    return _orig_send_attachment.apply(button_id, [props, attachment]);
                }
            }
            wp.media.editor.open(button);
            return false;
        });
    }
    ct_media_upload('.ct_tax_media_button.button');
    $('body').on('click', '.ct_tax_media_remove', function() {
        $('#product_cat-image-id').val('');
        $('#product_cat-image-wrapper').html(
            '<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />'
        );
    });
    // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
    $(document).ajaxComplete(function(event, xhr, settings) {
        var queryStringArr = settings.data.split('&');
        if ($.inArray('action=add-tag', queryStringArr) !== -1) {
            var xml = xhr.responseXML;
            $response = $(xml).find('term_id').text();
            if ($response != "") {
                // Clear the thumb image
                $('#product_cat-image-wrapper').html('');
            }
        }
    });
});
</script>
<?php }

  }

$CT_TAX_META = new upload_image_product();
$CT_TAX_META -> init();