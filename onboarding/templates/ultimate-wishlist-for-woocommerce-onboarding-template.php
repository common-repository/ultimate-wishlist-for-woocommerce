<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin/onboarding
 */

global $uwfw_mwb_uwfw_obj;
$uwfw_onboarding_form_fields =
// desc - filter for trial.
apply_filters( 'mwb_uwfw_on_boarding_form_fields', array() );
?>

<?php if ( ! empty( $uwfw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable <? echo 
	//desc - filter for trial.
	apply_filters('mwb_stand_dialog_classes', 'ultimate-wishlist-for-woocommerce' )?>">
		<div class="mwb-uwfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-uwfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-uwfw-on-boarding-close-btn">
						<a href="#"><span class="uwfw-close-form material-icons mwb-uwfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>
					<h3 class="mwb-uwfw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to MakeWebBetter', 'ultimate-wishlist-for-woocommerce' ); ?> </h3>
					<p class="mwb-uwfw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'ultimate-wishlist-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="mwb-uwfw-on-boarding-form">
						<?php
						$uwfw_onboarding_html = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_generate_html( $uwfw_onboarding_form_fields );
						echo esc_html( $uwfw_onboarding_html );
						?>
						<div class="mwb-uwfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-uwfw-on-boarding-form-submit mwb-uwfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-uwfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-uwfw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-uwfw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'ultimate-wishlist-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
