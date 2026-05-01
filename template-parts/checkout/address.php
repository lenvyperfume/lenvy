<?php
/**
 * Checkout — shipping address + collapsible billing address.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$customer  = (array) ($args['customer'] ?? []);
$countries = (array) ($args['countries'] ?? ['Nederland', 'België']);

$fname = (string) ($customer['fname'] ?? '');
$lname = (string) ($customer['lname'] ?? '');
$addr  = (string) ($customer['addr']  ?? '');
$zip   = (string) ($customer['zip']   ?? '');
$city  = (string) ($customer['city']  ?? '');
?>

<section class="lenvy-checkout__section">
	<div class="lenvy-checkout__section-top">
		<h2 class="lenvy-checkout__section-title"><?php esc_html_e('Waar mogen we het bezorgen?', 'lenvy'); ?></h2>
		<div class="lenvy-checkout__section-aside"><?php esc_html_e('Verzending naar Nederland · België', 'lenvy'); ?></div>
	</div>

	<div class="lenvy-checkout__fld-grid">
		<div class="lenvy-checkout__fld lenvy-checkout__fld--select has-value">
			<label for="lc-land"><?php esc_html_e('Land / regio', 'lenvy'); ?></label>
			<select id="lc-land">
				<?php foreach ($countries as $c): ?>
					<option><?php echo esc_html($c); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="lenvy-checkout__fld">
			<label for="lc-phone">
				<?php esc_html_e('Telefoonnummer', 'lenvy'); ?>
				<span class="opt"><?php esc_html_e('optioneel', 'lenvy'); ?></span>
			</label>
			<input id="lc-phone" type="tel" placeholder="+31 6 …" autocomplete="tel" />
		</div>

		<div class="lenvy-checkout__fld<?php echo $fname ? ' has-value' : ''; ?>">
			<label for="lc-fname"><?php esc_html_e('Voornaam', 'lenvy'); ?></label>
			<input id="lc-fname" value="<?php echo esc_attr($fname); ?>" autocomplete="given-name" />
		</div>

		<div class="lenvy-checkout__fld<?php echo $lname ? ' has-value' : ''; ?>">
			<label for="lc-lname"><?php esc_html_e('Achternaam', 'lenvy'); ?></label>
			<input id="lc-lname" value="<?php echo esc_attr($lname); ?>" autocomplete="family-name" />
		</div>

		<div class="lenvy-checkout__fld lenvy-checkout__fld--full<?php echo $addr ? ' has-value' : ''; ?>">
			<label for="lc-addr"><?php esc_html_e('Straatnaam + huisnummer', 'lenvy'); ?></label>
			<input id="lc-addr" value="<?php echo esc_attr($addr); ?>" autocomplete="address-line1" />
		</div>

		<div class="lenvy-checkout__fld lenvy-checkout__fld--full">
			<label for="lc-addr2">
				<?php esc_html_e('Toevoeging', 'lenvy'); ?>
				<span class="opt"><?php esc_html_e('optioneel — bijv. III, A, sous', 'lenvy'); ?></span>
			</label>
			<input id="lc-addr2" autocomplete="address-line2" />
		</div>

		<div class="lenvy-checkout__fld<?php echo $zip ? ' has-value' : ''; ?>">
			<label for="lc-zip"><?php esc_html_e('Postcode', 'lenvy'); ?></label>
			<input id="lc-zip" value="<?php echo esc_attr($zip); ?>" autocomplete="postal-code" />
		</div>

		<div class="lenvy-checkout__fld<?php echo $city ? ' has-value' : ''; ?>">
			<label for="lc-city"><?php esc_html_e('Plaats', 'lenvy'); ?></label>
			<input id="lc-city" value="<?php echo esc_attr($city); ?>" autocomplete="address-level2" />
		</div>
	</div>

	<label class="lenvy-checkout__checkbox" data-billing-toggle>
		<input type="checkbox" data-billing-cb />
		<span class="lenvy-checkout__checkbox-box">
			<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<polyline points="20 6 9 17 4 12"/>
			</svg>
		</span>
		<span class="lenvy-checkout__checkbox-lbl">
			<?php esc_html_e('Factuuradres wijkt af van bezorgadres', 'lenvy'); ?>
			<small><?php esc_html_e('Vul hieronder een ander factuuradres in', 'lenvy'); ?></small>
		</span>
	</label>

	<div class="lenvy-checkout__billing" data-billing-block>
		<h3 class="lenvy-checkout__billing-title"><?php esc_html_e('Factuuradres', 'lenvy'); ?></h3>
		<div class="lenvy-checkout__fld-grid">
			<div class="lenvy-checkout__fld lenvy-checkout__fld--select">
				<label for="lc-b-land"><?php esc_html_e('Land / regio', 'lenvy'); ?></label>
				<select id="lc-b-land">
					<?php foreach ($countries as $c): ?>
						<option><?php echo esc_html($c); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="lenvy-checkout__fld">
				<label for="lc-b-company">
					<?php esc_html_e('Bedrijfsnaam', 'lenvy'); ?>
					<span class="opt"><?php esc_html_e('optioneel', 'lenvy'); ?></span>
				</label>
				<input id="lc-b-company" />
			</div>
			<div class="lenvy-checkout__fld">
				<label for="lc-b-fname"><?php esc_html_e('Voornaam', 'lenvy'); ?></label>
				<input id="lc-b-fname" />
			</div>
			<div class="lenvy-checkout__fld">
				<label for="lc-b-lname"><?php esc_html_e('Achternaam', 'lenvy'); ?></label>
				<input id="lc-b-lname" />
			</div>
			<div class="lenvy-checkout__fld lenvy-checkout__fld--full">
				<label for="lc-b-addr"><?php esc_html_e('Straatnaam + huisnummer', 'lenvy'); ?></label>
				<input id="lc-b-addr" />
			</div>
			<div class="lenvy-checkout__fld">
				<label for="lc-b-zip"><?php esc_html_e('Postcode', 'lenvy'); ?></label>
				<input id="lc-b-zip" />
			</div>
			<div class="lenvy-checkout__fld">
				<label for="lc-b-city"><?php esc_html_e('Plaats', 'lenvy'); ?></label>
				<input id="lc-b-city" />
			</div>
			<div class="lenvy-checkout__fld lenvy-checkout__fld--full">
				<label for="lc-b-vat">
					<?php esc_html_e('BTW-nummer', 'lenvy'); ?>
					<span class="opt"><?php esc_html_e('optioneel — voor zakelijke aankopen', 'lenvy'); ?></span>
				</label>
				<input id="lc-b-vat" placeholder="NL…B…" />
			</div>
		</div>
	</div>
</section>
