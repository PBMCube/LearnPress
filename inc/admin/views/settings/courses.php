<?php
/**
 * Display settings for course
 *
 * @author  ThimPress
 * @package LearnPress/Admin/Views
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = LP()->settings;
global $wp_post_types;
if ( !empty( $wp_post_types[LP()->course_post_type] ) ) {
	$course_type          = $wp_post_types[LP()->course_post_type];
	$default_courses_slug = $course_type->rewrite['slug'];
} else {
	$default_courses_slug = '';
}

//$permalinks = get_option( 'woocommerce_permalinks' );
$course_permalink = $settings->get( 'course_base' );

$courses_page_id = learn_press_get_page_id( 'courses' );
$base_slug       = urldecode( ( $courses_page_id > 0 && get_post( $courses_page_id ) ) ? get_page_uri( $courses_page_id ) : _x( 'courses', 'default-slug', 'learn_press' ) );
$course_base    = _x( 'course', 'default-slug', 'learn_press' );

$structures = array(
	0 => array(
		'value' => '',
		'text'	=> __( 'Default', 'learn_press' ),
		'code'	=> esc_html( home_url() ) . '/?lp_course=sample-course'
	),
	1 => array(
		'value' => '/' . trailingslashit( $course_base ),
		'text'  => __( 'Course', 'learn_press' ),
		'code'  => esc_html( sprintf( '%s/%s/sample-course/', home_url(), $course_base ) )
	),
	2 => array(
		'value' => '/' . trailingslashit( $base_slug ),
		'text'  => __( 'Courses base', 'learn_press' ),
		'code'  => esc_html( sprintf( '%s/%s/sample-course/', home_url(), $base_slug ) )
	),
	3 => array(
		'value' => '/' . trailingslashit( $base_slug ) . trailingslashit( '%course_category%' ),
		'text'  => __( 'Courses base with category', 'learn_press' ),
		'code'  => esc_html( sprintf( '%s/%s/course-category/sample-course/', home_url(), $base_slug ) )
	)
);

$is_custom = true;
?>
<h3 class=""><?php echo $this->section['text']; ?></h3>
<table class="form-table">
	<tbody>
	<?php do_action( 'learn_press_before_' . $this->id . '_' . $this->section['id'] . '_settings_fields', $settings ); ?>

	<tr>
		<th scope="row"><label><?php _e( 'Courses Page', 'learn_press' ); ?></label></th>
		<td>
			<?php
			learn_press_pages_dropdown( $this->get_field_name( "courses_page_id" ), $courses_page_id );
			?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label><?php _e( 'Course category base', 'learn_press' ); ?></label></th>
		<td>
			<input type="text" class="regular-text" name="<?php echo $this->get_field_name( "course_category_base" ); ?>" value="<?php echo $settings->get( 'course_category_base' ); ?>" placeholder="<?php echo 'course-category'; ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row"><label><?php _e( 'Course tag base', 'learn_press' ); ?></label></th>
		<td>
			<input type="text" class="regular-text" name="<?php echo $this->get_field_name( "course_tag_base" ); ?>" value="<?php echo $settings->get( 'course_tag_base' ); ?>" placeholder="<?php echo 'course-tag'; ?>" />
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<?php _e( 'Single course permalink', 'learn_press' );?>
		</th>
	</tr>
	<?php foreach( $structures as $k => $structure ): ?>
	<tr<?php if( $k == 2 || $k == 3 ){ echo ' class="learn-press-courses-page-id'; echo !$courses_page_id ? ' hide-if-js"' : '""'; };?> >
		<th>
			<?php
			$is_checked = checked( ( $course_permalink == '' && $structure['value'] == '' ) || ( $structure['value'] == trailingslashit( $course_permalink ) ), true, false );
			if( $is_custom && $is_checked ) {
				$is_custom = false;
			}
			?>
			<label>
				<input name="<?php echo $this->get_field_name( "course_base" ); ?>" type="radio" value="<?php echo esc_attr( $structure['value'] ); ?>" class="learn-press-course-base" <?php echo $is_checked; ?> />
				<?php echo $structure['text']; ?>
			</label>
		</th>
		<td>
			<code><?php echo $structure['code'];?></code>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<th>
			<label>
				<input name="<?php echo $this->get_field_name( "course_base" ); ?>" id="learn_press_custom_permalink" type="radio" value="custom" <?php checked( $is_custom, true ); ?> />
				<?php _e( 'Custom Base', 'learn_press' ); ?>
			</label>
		</th>
		<td>
			<input name="course_permalink_structure" id="course_permalink_structure" type="text" value="<?php echo esc_attr( $course_permalink ); ?>" class="regular-text code" />
			<p class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'learn_press' ); ?></p>
		</td>
	</tr>
	<?php do_action( 'learn_press_after_' . $this->id . '_' . $this->section['id'] . '_settings_fields', $settings ); ?>
	</tbody>
</table>
<script type="text/javascript">
	jQuery( function($) {
		$('input.learn-press-course-base').change(function() {
			$('#course_permalink_structure').val( $( this ).val() );
		});

		$('#course_permalink_structure').focus( function(){
			$('#learn_press_custom_permalink').click();
		} );

		$('#learn_press_courses_page_id').change(function(){
			$('tr.learn-press-courses-page-id').toggleClass( 'hide-if-js', !parseInt( this.value ) )
		});
	} );
</script>