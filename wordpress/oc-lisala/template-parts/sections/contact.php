<?php
/**
 * Section « Contact » : coordonnées, réseaux sociaux, formulaire vers WhatsApp.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'contact' );
if ( ! $o['enabled'] ) {
	return;
}
$g       = ocl_options( 'general' );
$wa      = ocl_whatsapp_url( $o['wa_message'] );
$fields  = ocl_form_fields();
$state   = ocl_form_state();
$socials = ocl_socials();
$show_form = $wa && $fields;
?>
<section class="section section--light" id="contact"<?php echo ocl_labelledby( $o['title'], 'contact-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container contact<?php echo $show_form ? '' : ' contact--single'; ?>">
		<div class="contact__info reveal">
			<?php if ( $o['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo esc_html( $o['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( $o['title'] ) : ?>
				<h2 class="section-title" id="contact-title"><?php echo esc_html( $o['title'] ); ?></h2>
			<?php endif; ?>
			<?php if ( $o['text'] ) : ?>
				<p><?php echo esc_html( $o['text'] ); ?></p>
			<?php endif; ?>

			<?php if ( $g['address'] || $g['phone'] || $g['email'] ) : ?>
				<ul class="contact-list">
					<?php if ( $g['address'] ) : ?>
						<li>
							<span class="contact-list__label"><?php esc_html_e( 'Adresse', 'oc-lisala' ); ?></span>
							<span class="contact-list__value"><?php echo esc_html( $g['address'] ); ?></span>
						</li>
					<?php endif; ?>
					<?php if ( $g['phone'] ) : ?>
						<li>
							<span class="contact-list__label"><?php esc_html_e( 'Téléphone', 'oc-lisala' ); ?></span>
							<span class="contact-list__value"><?php echo ocl_phone_link( $g['phone'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</li>
					<?php endif; ?>
					<?php if ( $g['email'] ) : ?>
						<li>
							<span class="contact-list__label"><?php esc_html_e( 'E-mail', 'oc-lisala' ); ?></span>
							<span class="contact-list__value"><a href="mailto:<?php echo esc_attr( antispambot( $g['email'] ) ); ?>"><?php echo esc_html( antispambot( $g['email'] ) ); ?></a></span>
						</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $wa && $o['wa_btn_label'] ) : ?>
				<a class="btn btn--whatsapp" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
					<?php echo ocl_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo esc_html( $o['wa_btn_label'] ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $socials ) : ?>
				<?php if ( $o['social_title'] ) : ?>
					<h3 class="contact__social-title"><?php echo esc_html( $o['social_title'] ); ?></h3>
				<?php endif; ?>
				<?php echo $socials; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- construit et échappé dans ocl_socials(). ?>
			<?php endif; ?>
		</div>

		<?php if ( $show_form ) : ?>
			<div class="contact__form reveal">
				<form class="form form--card" id="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
					<?php if ( $o['form_title'] ) : ?>
						<h3 class="form__title"><?php echo esc_html( $o['form_title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( $o['form_note'] ) : ?>
						<p class="form__demo" role="note"><?php echo esc_html( $o['form_note'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $state['errors']['_form'] ) ) : ?>
						<p class="form__alert" role="alert"><?php echo esc_html( $state['errors']['_form'] ); ?></p>
					<?php endif; ?>

					<input type="hidden" name="action" value="ocl_whatsapp">
					<?php wp_nonce_field( 'ocl_whatsapp_form', 'ocl_form_nonce', false ); ?>
					<div class="form__hp" aria-hidden="true">
						<label for="ocl-website"><?php esc_html_e( 'Ne pas remplir ce champ', 'oc-lisala' ); ?></label>
						<input type="text" id="ocl-website" name="ocl_website" value="" tabindex="-1" autocomplete="off">
					</div>

					<?php foreach ( $fields as $field ) : ?>
						<?php
						$id    = 'c-' . $field['name'];
						$error = isset( $state['errors'][ $field['name'] ] ) ? $state['errors'][ $field['name'] ] : '';
						$value = ocl_form_initial_value( $field );
						$attrs = ( $field['required'] ? ' required' : '' )
							. ( $field['minlength'] ? ' minlength="' . (int) $field['minlength'] . '"' : '' )
							. ' maxlength="' . (int) OCL_FORM_MAX_LENGTH . '"'
							. ( $error ? ' aria-invalid="true"' : '' )
							. ' aria-describedby="' . esc_attr( $id ) . '-error"';
						$auto  = array(
							'email' => 'email',
							'tel'   => 'tel',
						);
						if ( 'text' === $field['type'] && 0 === strpos( sanitize_title( $field['label'] ), 'nom' ) ) {
							$auto['text'] = 'name';
						}
						?>
						<div class="field<?php echo $error ? ' has-error' : ''; ?>">
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?><?php echo $field['required'] ? ' <span aria-hidden="true">*</span>' : ''; ?></label>
							<?php if ( 'textarea' === $field['type'] ) : ?>
								<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" rows="5" data-label="<?php echo esc_attr( $field['label'] ); ?>"<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributs construits ci-dessus. ?>><?php echo esc_textarea( $value ); ?></textarea>
							<?php elseif ( 'select' === $field['type'] ) : ?>
								<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" data-label="<?php echo esc_attr( $field['label'] ); ?>"<?php echo $field['required'] ? ' required' : ''; ?><?php echo $error ? ' aria-invalid="true"' : ''; ?> aria-describedby="<?php echo esc_attr( $id ); ?>-error">
									<option value=""><?php esc_html_e( 'Choisir…', 'oc-lisala' ); ?></option>
									<?php foreach ( $field['options'] as $option ) : ?>
										<option value="<?php echo esc_attr( $option ); ?>"<?php selected( $value, $option ); ?>><?php echo esc_html( $option ); ?></option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" type="<?php echo esc_attr( $field['type'] ); ?>" value="<?php echo esc_attr( $value ); ?>" data-label="<?php echo esc_attr( $field['label'] ); ?>"<?php echo isset( $auto[ $field['type'] ] ) ? ' autocomplete="' . esc_attr( $auto[ $field['type'] ] ) . '"' : ''; ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php endif; ?>
							<p class="field__error" id="<?php echo esc_attr( $id ); ?>-error" aria-live="polite"><?php echo esc_html( $error ); ?></p>
						</div>
					<?php endforeach; ?>

					<button class="btn btn--primary btn--block" type="submit"><?php echo esc_html( $o['submit_label'] ? $o['submit_label'] : __( 'Envoyer sur WhatsApp', 'oc-lisala' ) ); ?></button>
					<?php if ( $o['success_text'] ) : ?>
						<p class="form__success" role="status" hidden><?php echo esc_html( $o['success_text'] ); ?></p>
					<?php endif; ?>
				</form>
			</div>
		<?php endif; ?>
	</div>
</section>
