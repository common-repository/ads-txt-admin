<?php
if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly

//must check that the user has the required capability
if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}

$includes_path = __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once $includes_path . 'AdsTxtManager.php';

$url_to_ads_txt      = home_url('/ads.txt');
$hidden_field_name   = 'unveil_media_ads_txt_hidden';
$path_to_ads_txt     = Unveil_Media_Ads_Txt::get_file_path();
$uploads_dir_path    = dirname($path_to_ads_txt);
$elements_to_display = array();
$ads_txt_manager     = new AdsTxtManager($path_to_ads_txt, $url_to_ads_txt);


if (!wp_is_writable(dirname($path_to_ads_txt))) {

	$elements_to_display[] = 'cannot_write_file_error';

} else {

	$elements_to_display[] = 'form';

}

// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {

	if (isset($_POST['no-authorised'])) {

		$ads_txt_manager->saveNoAuthorisedSellers();

	} else {

		// Read their posted value
		check_admin_referer('unveil_media_ads_txt_options');

		$rows = $_POST['rows'];

		if ($ads_txt_manager->saveData($rows)) {
			$elements_to_display[] = 'saved_info_success';
		} else {
			$elements_to_display[] = 'saved_info_fail';
		}

	}
}

$file_content = $ads_txt_manager->getFileContent();
?>

<div class="um-ads-txt col-md-12">
	<div class="row header-row">
		<img class="pull-left" src="<?= plugin_dir_url(__FILE__) . 'img/um-ads-txt.png' ?>">
		<h1 class="pull-left"><?php echo __('Ads.txt Admin', 'unveil-media-ads-txt'); ?></h1>
	</div>

	<div class="um-videowall-info">
		<a href="http://videoyield.com"
		   title="<?php _e('Visit www.videoyield.com for details', 'unveil-media-ads-txt'); ?>" target="_blank">
			<img src="<?= plugin_dir_url(__FILE__) . 'img/um-info.png' ?>"
				 alt="<?php _e('VideoYield Videowall info', 'unveil-media-ads-txt'); ?>">
		</a>
	</div>

	<?php if (in_array('cannot_write_file_error', $elements_to_display)): ?>
		<div class="error">
			<p><strong><?php _e('Your uploads dir is not writable', 'unveil-media-ads-txt'); ?></strong></p>
			<?php _e('To allow plugin to edit ads.txt file you have to make it writable.', 'unveil-media-ads-txt'); ?>
			<p>
				<?php _e('You can change file permissions via command line or FTP client.', 'unveil-media-ads-txt'); ?>
				<br/>
				<?php _e('Whatever you choose allow server to write in the uploads folder e.g.',
					'unveil-media-ads-txt'); ?><br/>
				<i>chown -R www-data:www-data <?= $uploads_dir_path ?>;</i>
			</p>
		</div>
	<?php endif; ?>

	<?php if (in_array('saved_info', $elements_to_display)): ?>
		<div class="updated">
			<p><strong><?php _e('ads.txt file has been updated.', 'unveil-media-ads-txt'); ?></strong></p>
		</div>
	<?php endif; ?>

	<?php if (in_array('form', $elements_to_display)): ?>
		<div class="col-md-12">
			<form name="form" method="post" action="" class="form-rows-wrapper">
				<?php wp_nonce_field('unveil_media_ads_txt_options') ?>
				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

				<?php
				$tooltips = array(
					"(Required) The canonical domain name of the
                        SSP, Exchange, Header Wrapper, etc system that
                        bidders connect to. This may be the operational
                        domain of the system, if that is different than the
                        parent corporate domain, to facilitate WHOIS and
                        reverse IP lookups to establish clear ownership of
                        the delegate system. Ideally the SSP or Exchange
                        publishes a document detailing what domain name
                        to use.",
					"(Required) The identifier associated with the seller
                        or reseller account within the advertising system in
                        field #1. This must contain the same value used in
                        transactions (i.e. OpenRTB bid requests) in the
                        field specified by the SSP/exchange. Typically, in
                        OpenRTB, this is publisher.id. For OpenDirect it is
                        typically the publisher’s organization ID.",
					"(Required) An enumeration of the type of account.
                        A value of ‘DIRECT’ indicates that the Publisher
                        (content owner) directly controls the account
                        indicated in field #2 on the system in field #1. This
                        tends to mean a direct business contract between
                        the Publisher and the advertising system. A value
                        of ‘RESELLER’ indicates that the Publisher has
                        authorized another entity to control the account
                        indicated in field #2 and resell their ad space via
                        the system in field #1. Other types may be added
                        in the future. Note that this field should be treated
                        as case insensitive when interpreting the data.",
					"(Optional) An ID that uniquely identifies the
                        advertising system within a certification authority
                        (this ID maps to the entity listed in field #1). A
                        current certification authority is the Trustworthy
                        Accountability Group (aka TAG), and the TAGID
                        would be included here."
				);
				?>

				<div class="row table-header-row">
					<div class="col-sm-2">
						<label for="">
							<?php _e('Domain name of the advertising system', 'unveil-media-ads-txt'); ?>
						</label>
						<div class="um-tooltip">?
							<span class="tooltiptext"><?php echo $tooltips[0]; ?></span>
						</div>
					</div>
					<div class="col-sm-2">
						<label for="">
							<?php _e("Publisher's Account ID", 'unveil-media-ads-txt'); ?>
						</label>
						<div class="um-tooltip">?
							<span class="tooltiptext"><?php echo $tooltips[1]; ?></span>
						</div>
					</div>
					<div class="col-sm-2">
						<label for="">
							<?php _e('Type of Account/Relationship', 'unveil-media-ads-txt'); ?>
						</label>
						<div class="um-tooltip">?
							<span class="tooltiptext"><?php echo $tooltips[2]; ?></span>
						</div>
					</div>
					<div class="col-sm-2">
						<label for="">
							<?php _e('Certification Authority ID', 'unveil-media-ads-txt'); ?>
						</label>
						<div class="um-tooltip">?
							<span class="tooltiptext"><?php echo $tooltips[3]; ?></span>
						</div>
					</div>
				</div>

				<?php
				$i     = -1;
				$lines = $ads_txt_manager->getLines();

				if (!$lines) {
					$lines = array(',,,');
				}

				foreach ($lines as $line):
					$lineData = explode(',', $line);
					array_map('trim', $lineData);

					if (count($lineData) === 3) {
						$lineData[] = '';
					}

					++$i;
					?>
					<div class="row table-fields-row">
						<div class="col-sm-2">
							<input name="rows[<?= $i ?>][domain]" type="text" class="form-control"
								   value="<?= $lineData[0] ?>"/>
						</div>
						<div class="col-sm-2">
							<input name="rows[<?= $i ?>][publisher_id]" type="text" class="form-control"
								   value="<?= $lineData[1] ?>"/>
						</div>
						<div class="col-sm-2">
							<select name="rows[<?= $i ?>][type]" type="text" class="form-control">
								<option value="DIRECT" <?= (trim($lineData[2]) === 'DIRECT' ? 'selected' : '') ?>>
									DIRECT
								</option>
								<option value="RESELLER" <?= (trim($lineData[2]) === 'RESELLER' ? 'selected' : '') ?>>
									RESELLER
								</option>
							</select>
						</div>
						<div class="col-sm-2">
							<input name=rows[<?= $i ?>][authority_id]" type="text" class="form-control"
								   value="<?= $lineData[3] ?>"/>
						</div>
						<div class="col-sm-2">
							<input type="button" class="um-ads-btn um-rem-btn" onclick="removeRow(this)"
								   value="<?php _e('Remove', 'unveil-media-ads-txt'); ?>">
						</div>
					</div>
				<?php endforeach; ?>

				<input type="button" class="um-ads-btn um-add-btn" onclick="addRow()"
					   value="<?php _e('Add row', 'unveil-media-ads-txt'); ?>">

				<p class="submit">
					<input type="button" name="Submit" class="button-primary" onclick="validateFields()"
						   value="<?php _e('Save ads.txt file', 'unveil-media-ads-txt'); ?>"/>
				</p>
				<p>
                    <span class="error-wrapper">
                        <span class="error-msg err-domain"><?php _e('Error in domain name. Must be',
								'unveil-media-ads-txt'); ?> <strong><?php _e('yourdomain.com',
									'unveil-media-ads-txt'); ?></strong>.</span>
                        <span class="error-msg err-fields"><?php _e('Some required fields are empty. Fill them.',
								'unveil-media-ads-txt'); ?></span>
                    </span>
				</p>
			</form>

			<h4>
				<?php _e('Ads.txt file preview', 'unveil-media-ads-txt'); ?>
				-
				<a href="<?= $url_to_ads_txt ?>" title="<?php _e('Open ads.txt file', 'unveil-media-ads-txt'); ?>"
				   target="_blank"><?php _e('Open ads.txt file', 'unveil-media-ads-txt'); ?></a>
			</h4>
			<?php if ($ads_txt_manager->getLines()): ?>
				<textarea class="um-ads-txt-file-preview" cols="140" rows="10" readonly><?= $file_content; ?></textarea>
			<?php else: ?>
				<i><?php _e('There are no authorized sellers or resellers listed in this ads.txt file.',
						'unveil-media-ads-txt'); ?></i>
			<?php endif; ?>

			<hr/>

			<h4>
				<?php _e('No Authorised Sellers / Resellers', 'unveil-media-ads-txt'); ?>
			</h4>
			<form action="" method="POST">
				<?php wp_nonce_field('unveil_media_ads_txt_options') ?>
				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
				<?php _e('This will remove all authorised sellers from your ads.txt file. Be sure you mean to delete all details.',
					'unveil-media-ads-txt'); ?>
				<br/>
				<input type="submit" name="no-authorised" class="button"
					   onclick="return confirm('<?php _e('are you sure you want to remove authorised sellers/resellers?',
						   'unveil-media-ads-txt'); ?>')"
					   value="<?php _e('No Authorised Sellers / Resellers', 'unveil-media-ads-txt'); ?>"/>
			</form>
		</div>
	<?php endif; ?>

	<div class="um-footer">
		<div class="um-footer-right">
			<?php _e('Version', 'unveil-media-ads-txt'); ?>: <?= UNVEIL_MEDIA_ADS_TXT_VERSION ?>
		</div>
	</div>
</div>
