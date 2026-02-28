<?php
/**
 * Site footer — primary-color background, editorial layout.
 *
 * Structure:
 *   Grid (4 col)     — brand+contact | shop nav | info nav | social
 *   ─────────────────────────────────────────────────────────────────
 *   Bottom bar       — copyright | legal nav
 *
 * ACF fields consumed (options page):
 *   lenvy_site_logo_light       image (preferred on dark bg; unused here)
 *   lenvy_site_logo             image
 *   lenvy_footer_copyright_text text  ({year} placeholder)
 *   lenvy_footer_social_links   repeater  [platform, url]
 *   lenvy_contact_email         email
 *   lenvy_contact_phone         text
 *   lenvy_contact_address       textarea
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ── Gather data ───────────────────────────────────────────────────────────────

$logo_id = lenvy_field('lenvy_site_logo', 'options');

$copyright_raw = lenvy_field('lenvy_footer_copyright_text', 'options');
$copyright = $copyright_raw
	? str_replace('{year}', date('Y'), $copyright_raw)
	: sprintf(
		/* translators: 1: year, 2: site name */
		__('&copy; %1$s %2$s. All rights reserved.', 'lenvy'),
		date('Y'),
		get_bloginfo('name'),
	);

$social_links = lenvy_field('lenvy_footer_social_links', 'options') ?: [];
$contact_email = lenvy_field('lenvy_contact_email', 'options');
$contact_phone = lenvy_field('lenvy_contact_phone', 'options');
$contact_address = lenvy_field('lenvy_contact_address', 'options');
$kvk_number = lenvy_field('lenvy_kvk_number', 'options');
$btw_number = lenvy_field('lenvy_btw_number', 'options');

$social_labels = [
	'instagram' => __('Instagram', 'lenvy'),
	'facebook' => __('Facebook', 'lenvy'),
	'tiktok' => __('TikTok', 'lenvy'),
	'pinterest' => __('Pinterest', 'lenvy'),
	'youtube' => __('YouTube', 'lenvy'),
	'x' => __('X (Twitter)', 'lenvy'),
];
?>

<footer class="bg-neutral-50 border-t border-neutral-200 text-neutral-700 overflow-hidden">

	<!-- ── Main grid ─────────────────────────────────────────────────────── -->
	<div class="lenvy-container py-16 lg:py-20">
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

			<!-- Col 1: Brand + contact info ───────────────────────────── -->
			<div class="space-y-5">

				<!-- Logo -->
				<a
					href="<?php echo esc_url(home_url('/')); ?>"
					aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
					class="inline-block"
				>
					<?php if ($logo_id): ?>
						<?php echo lenvy_get_image($logo_id, 'medium', 'max-h-9 w-auto object-contain brightness-0 opacity-70'); ?>
					<?php else: ?>
						<span class="font-serif italic text-xl text-neutral-900 tracking-tight">
							<?php bloginfo('name'); ?>
						</span>
					<?php endif; ?>
				</a>

				<!-- Contact details under logo -->
				<div class="space-y-1.5 text-sm text-neutral-600 leading-relaxed">
					<?php if ($contact_address): ?>
					<p class="whitespace-pre-line"><?php echo esc_html($contact_address); ?></p>
					<?php endif; ?>
					<?php if ($contact_email): ?>
					<a
						href="mailto:<?php echo esc_attr($contact_email); ?>"
						class="block hover:text-black transition-colors duration-200 break-all"
					>
						<?php echo esc_html($contact_email); ?>
					</a>
					<?php endif; ?>
					<?php if ($contact_phone): ?>
					<a
						href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $contact_phone)); ?>"
						class="block hover:text-black transition-colors duration-200"
					>
						<?php echo esc_html($contact_phone); ?>
					</a>
					<?php endif; ?>
					<?php if ($kvk_number): ?>
					<p class="text-xs text-neutral-400"><?php echo esc_html('KVK: ' . $kvk_number); ?></p>
					<?php endif; ?>
					<?php if ($btw_number): ?>
					<p class="text-xs text-neutral-400"><?php echo esc_html('BTW: ' . $btw_number); ?></p>
					<?php endif; ?>
				</div>

			</div>

			<!-- Col 2: Shop nav ───────────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">
					<?php esc_html_e('Shop', 'lenvy'); ?>
				</h3>
				<?php if (has_nav_menu('footer')): ?>
				<nav aria-label="<?php esc_attr_e('Shop Navigation', 'lenvy'); ?>">
					<?php wp_nav_menu([
     	'theme_location' => 'footer',
     	'container' => false,
     	'menu_class' => 'space-y-3',
     	'walker' => new Lenvy_Footer_Nav_Walker(),
     	'fallback_cb' => false,
     	'depth' => 1,
     ]); ?>
				</nav>
				<?php endif; ?>
			</div>

			<!-- Col 3: Info nav ───────────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">
					<?php esc_html_e('Information', 'lenvy'); ?>
				</h3>
				<?php if (has_nav_menu('footer-secondary')): ?>
				<nav aria-label="<?php esc_attr_e('Information Navigation', 'lenvy'); ?>">
					<?php wp_nav_menu([
     	'theme_location' => 'footer-secondary',
     	'container' => false,
     	'menu_class' => 'space-y-3',
     	'walker' => new Lenvy_Footer_Nav_Walker(),
     	'fallback_cb' => false,
     	'depth' => 1,
     ]); ?>
				</nav>
				<?php else: ?>
				<!-- Static fallback until footer-secondary menu is assigned in WP admin -->
				<ul class="space-y-3">
					<?php
     $fallback = [
     	__('About Us', 'lenvy') => home_url('/about/'),
     	__('FAQ', 'lenvy') => home_url('/faq/'),
     	__('Contact', 'lenvy') => home_url('/contact/'),
     	__('Shipping', 'lenvy') => home_url('/shipping/'),
     	__('Returns', 'lenvy') => home_url('/returns/'),
     ];
     foreach ($fallback as $label => $href): ?>
					<li>
						<a
							href="<?php echo esc_url($href); ?>"
							class="text-sm font-light text-neutral-700 hover:text-black transition-colors duration-200"
						>
							<?php echo esc_html($label); ?>
						</a>
					</li>
					<?php endforeach;
     ?>
				</ul>
				<?php endif; ?>
			</div>

			<!-- Col 4: Social media ───────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400">
					<?php esc_html_e('Follow Us', 'lenvy'); ?>
				</h3>
				<?php if (!empty($social_links)): ?>
				<div class="flex flex-wrap gap-5">
					<?php foreach ($social_links as $social):

     	$platform = $social['platform'] ?? '';
     	$url = $social['url'] ?? '';
     	if (!$platform || !$url) {
     		continue;
     	}
     	?>
					<a
						href="<?php echo esc_url($url); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr($social_labels[$platform] ?? ucfirst($platform)); ?>"
						class="text-neutral-600 hover:text-black transition-colors duration-200"
					>
						<?php lenvy_icon($platform, '', 'sm'); ?>
					</a>
					<?php
     endforeach; ?>
				</div>
				<?php else: ?>
				<p class="text-sm text-neutral-500 italic">
					<?php esc_html_e('Coming soon.', 'lenvy'); ?>
				</p>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<!-- ── Bottom bar ────────────────────────────────────────────────────── -->
	<div class="lenvy-container border-t border-neutral-200 py-8">
		<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

			<p class="text-xs text-neutral-600">
				<?php echo wp_kses_post($copyright); ?>
			</p>

			<!-- Legal quick-links (hardcoded; client creates these pages) -->
			<nav
				class="flex items-center flex-wrap gap-x-5 gap-y-1"
				aria-label="<?php esc_attr_e('Legal', 'lenvy'); ?>"
			>
				<?php
    $legal = [
    	__('Privacy Policy', 'lenvy') => home_url('/privacy-policy/'),
    	__('Terms & Conditions', 'lenvy') => home_url('/terms-conditions/'),
    	__('Cookie Policy', 'lenvy') => home_url('/cookie-policy/'),
    ];
    foreach ($legal as $label => $href): ?>
				<a
					href="<?php echo esc_url($href); ?>"
					class="text-xs text-neutral-600 hover:text-black transition-colors duration-200"
				>
					<?php echo esc_html($label); ?>
				</a>
				<?php endforeach;
    ?>
			</nav>

		</div>

		<!-- Payment methods -->
		<div class="flex items-center justify-center border-t border-neutral-200 pt-6 mt-3">
			<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/payments/payment-methods.svg'); ?>" alt="<?php esc_attr_e('iDEAL, Maestro, Mastercard, Visa', 'lenvy'); ?>" width="168" height="26" loading="lazy">
		</div>
	</div>

</footer>
