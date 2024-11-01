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

global $pagenow, $uwfw_mwb_uwfw_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}
$mwb_plugin_name                 = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
$mwb_plugin_deactivation_id      = $mwb_plugin_name . '-no_thanks_deactive';
$mwb_plugin_onboarding_popup_id  = $mwb_plugin_name . '-onboarding_popup';
$uwfw_onboarding_form_deactivate =
// desc - filter for trial.
apply_filters( 'mwb_uwfw_deactivation_form_fields', array() );

?>
<?php if ( ! empty( $uwfw_onboarding_form_deactivate ) ) : ?>
	<div id="<?php echo esc_attr( $mwb_plugin_onboarding_popup_id ); ?>" class="mdc-dialog mdc-dialog--scrollable <? echo 
	//desc - filter for trial.
	apply_filters('mwb_stand_dialog_classes', 'ultimate-wishlist-for-woocommerce' )?>">
		<div class="mwb-uwfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-uwfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-uwfw-on-boarding-close-btn">
						<a href="#">
							<span class="uwfw-close-form material-icons mwb-uwfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="mwb-uwfw-on-boarding-heading mdc-dialog__title"></h3>
					<p class="mwb-uwfw-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'ultimate-wishlist-for-woocommerce' ); ?></p>
					<form action="#" method="post" class="mwb-uwfw-on-boarding-form">
						<?php
						$uwfw_onboarding_deactive_html = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_generate_html( $uwfw_onboarding_form_deactivate );
						echo esc_html( $uwfw_onboarding_deactive_html );
						?>
						<div class="mwb-uwfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-uwfw-on-boarding-form-submit mwb-uwfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-uwfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-uwfw-on-boarding-form-no_thanks">
								<a href="#" id="<?php echo esc_attr( $mwb_plugin_deactivation_id ); ?>" class="<? echo 
								//desc - filter for trial.
								apply_filters('mwb_stand_no_thank_classes', 'ultimate-wishlist-for-woocommerce-no_thanks' )?> mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'ultimate-wishlist-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
