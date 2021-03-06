<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       1.0
 */

defined( 'ABSPATH' ) || exit();
if ( !$messages ) {
	return;
}
?>
<?php foreach ( $messages as $type => $message ) { ?>
	<?php if ( $message ): foreach ( $message as $content ) { ?>
		<div class="learn-press-message <?php echo $type; ?>">
			<?php
			if ( !preg_match( '!<p>!', $content ) && !preg_match( '!<div>!', $content ) ) {
				$content = sprintf( '<p>%s</p>', $content );
			}
			?>
			<?php echo $content; ?>
		</div>
	<?php } ?>
	<?php endif; ?>
<?php } ?>
