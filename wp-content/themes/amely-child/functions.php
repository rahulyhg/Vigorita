<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue child scripts
 */
add_action( 'wp_enqueue_scripts', 'amely_child_enqueue_scripts' );
if ( ! function_exists( 'amely_child_enqueue_scripts' ) ) {

	function amely_child_enqueue_scripts() {
		wp_enqueue_style( 'amely-main-style', trailingslashit( get_template_directory_uri() ) . '/style.css' );
		wp_enqueue_style( 'amely-child-style', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css' );
		wp_enqueue_script( 'amely-child-script',
			trailingslashit( get_stylesheet_directory_uri() ) . 'script.js',
			array( 'jquery' ),
			null,
			true );
	}

}


 //Modifica el Tabs Mas Información a Ficha Técnica, en el detalle del producto 
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);

function woo_rename_tabs($tabs)
{
	$tabs['additional_information']['title'] = 'Ficha Técnica';
	return $tabs;
}

//Añade el campo descuento en el form de usuarios del panel de administración
add_action( 'show_user_profile', 'add_field_seccion' );
add_action( 'edit_user_profile', 'add_field_seccion' );
 
function add_field_seccion( $user ) {
?>
    <h3><?php _e('Descuento'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
                <label for="descuento"><?php _e('Descuento (%)'); ?></label>
            </th>
            <td>
                <input type="text" name="descuento" id="descuento" class="regular-text"
                	value="<?php echo esc_attr( get_the_author_meta( 'descuento', $user->ID ) ); ?>" />
                <p class="description"><?php _e('Ingrese el descuento'); ?></p>
            </td>
        </tr>
    </table>
<?php }

//Guardamos los nuevos campos
add_action( 'personal_options_update', 'save_field_seccion' );
add_action( 'edit_user_profile_update', 'save_field_seccion' );

function save_field_seccion( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    if( isset($_POST['descuento']) ) {
        $descuento = sanitize_text_field($_POST['descuento']);
        update_user_meta( $user_id, 'descuento', $descuento );
    }
}