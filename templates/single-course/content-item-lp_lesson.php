<?php
/**
 * Template for display content of lesson
 *
 * @author  ThimPress
 * @version 1.0
 */
global $lp_query, $wp_query;
$user          = learn_press_get_current_user();
$course        = LP()->global['course'];
$item          = LP()->global['course-item'];
$security      = wp_create_nonce( sprintf( 'complete-item-%d-%d-%d', $user->id, $course->id, $item->ID ) );
$can_view_item = $user->can( 'view-item', $item->id, $course->id );
?>
<h2 class="learn-press-content-item-title"><?php echo $item->get_title(); ?></h2>
<div class="learn-press-content-item-summary">
	<?php echo apply_filters( 'learn_press_course_lesson_content', $item->get_content() ); ?>
	<?php if ( $user->has_completed_lesson( $item->ID, $course->id ) ) { ?>
		<?php learn_press_display_message( __( 'Congrats! You have completed this lesson', 'learnpress' ) ); ?>
		<button class="" disabled="disabled"> <?php _e( 'Completed', 'learnpress' ); ?></button>
	<?php } else if ( !$user->has( 'finished-course', $course->id ) && $can_view_item != 'preview' ) { ?>
		<!--
		<button class="button-complete-item button-complete-lesson" data-security="<?php echo esc_attr( $security ); ?>"><?php _e( 'Complete', 'learnpress' ); ?></button>
		-->
		<form method="post">
			<input type="hidden" name="id" value="<?php echo $item->id;?>" />
			<input type="hidden" name="course_id" value="<?php echo $course->id;?>" />
			<input type="hidden" name="security" value="<?php echo esc_attr( $security );?>" />
			<input type="hidden" name="type" value="lp_lesson" />
			<input type="hidden" name="lp-ajax" value="complete-item" />

			<button class="button-complete-item button-complete-lesson"><?php echo __( 'Complete', 'learnpress' );?></button>
			<?php
			/*
			echo apply_filters(
				'learn_press_user_completed_lesson_button',
				sprintf(
					'<button class="complete-lesson-button" data-id="%d" data-course_id="%d" data-nonce="%s">%s</button>',
					$item->id,
					$course->id,
					esc_attr( $security ),
					__( 'Complete', 'learnpress' )
				)
			);*/
			?>
		</form>
	<?php } ?>
</div>
<?php LP_Assets::enqueue_script( 'learn-press-course-lesson' ); ?>
<script type="text/javascript">
	if (typeof window.Lesson_Params != 'undefined') {
		window.Lesson_Params = undefined;
	}
	window.Lesson_Params = <?php echo json_encode( $item->get_settings( $user->id, $course->id ), learn_press_debug_enable() ? JSON_PRETTY_PRINT : 0 );?>;
</script>
