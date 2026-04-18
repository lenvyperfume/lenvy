<?php
/**
 * Site footer — HARDCODED match of Homepage.html `footer.site`.
 *
 * Structure:
 *   Top grid (1.4fr · 1fr · 1fr · 1fr, gap 64px, 64px border-bottom)
 *     Col 1 — logo + blurb + contact + socials
 *     Col 2 — Shop
 *     Col 3 — Hulp
 *     Col 4 — Over Lenvy
 *   Bottom bar (flex between, 28px top padding)
 *     Copyright · Legal nav · Payment chips
 *
 * Everything is hardcoded per the current design brief. Swap back to
 * nav_menu / ACF lookups later if editorial control is needed.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$home    = home_url('/');
$logo_id = lenvy_field('lenvy_site_logo', 'options');

$contact_email = 'hallo@lenvy.nl';
$contact_phone = '+31 (0)20 000 0000';
$contact_hours = __('Ma–Vr · 09:00–17:30', 'lenvy');

$socials = [
	['name' => 'Instagram', 'url' => 'https://instagram.com/', 'icon' => 'instagram'],
	['name' => 'TikTok',    'url' => 'https://tiktok.com/',    'icon' => 'tiktok'],
	['name' => 'Pinterest', 'url' => 'https://pinterest.com/', 'icon' => 'pinterest'],
];

$columns = [
	[
		'title' => __('Shop', 'lenvy'),
		'links' => [
			[ __('Dames',            'lenvy'), $home ],
			[ __('Heren',            'lenvy'), $home ],
			[ __('Unisex',           'lenvy'), $home ],
			[ __('Niche & zeldzaam', 'lenvy'), $home ],
			[ __('Samples & sets',   'lenvy'), $home ],
			[ __('Cadeaubonnen',     'lenvy'), $home ],
		],
	],
	[
		'title' => __('Hulp', 'lenvy'),
		'links' => [
			[ __('Contact',               'lenvy'), $home ],
			[ __('Verzending',            'lenvy'), $home ],
			[ __('Retourneren',           'lenvy'), $home ],
			[ __('Echtheidsgarantie',     'lenvy'), $home ],
			[ __('Veelgestelde vragen',   'lenvy'), $home ],
			[ __('Track je order',        'lenvy'), $home ],
		],
	],
	[
		'title' => __('Over Lenvy', 'lenvy'),
		'links' => [
			[ __('Ons verhaal',      'lenvy'), $home ],
			[ __('Partners & merken','lenvy'), $home ],
			[ __('Duurzaamheid',     'lenvy'), $home ],
			[ __('Pers',             'lenvy'), $home ],
			[ __('Vacatures',        'lenvy'), $home ],
		],
	],
];

$legal = [
	[ __('Privacybeleid',         'lenvy'), $home ],
	[ __('Algemene voorwaarden',  'lenvy'), $home ],
	[ __('Cookies',               'lenvy'), $home ],
];

$payment_methods_src = get_template_directory_uri() . '/assets/icons/payments/payment-methods.svg';
?>

<footer class="bg-neutral-950 text-white/70 pt-20 pb-7">
	<div class="lenvy-container">

		<!-- ── Top grid ────────────────────────────────────────────────── -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1fr] gap-12 lg:gap-16 pb-16 border-b border-white/10">

			<!-- Col 1 — brand -->
			<div>
				<a
					href="<?php echo esc_url($home); ?>"
					class="inline-flex items-center mb-6 text-white"
					aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
				>
					<?php if ($logo_id): ?>
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo lenvy_get_image($logo_id, 'medium', 'block max-h-10 w-auto object-contain brightness-0 invert');
						?>
					<?php else: ?>
						<span class="relative inline-flex items-baseline font-medium text-[40px] tracking-[-0.04em] leading-none pr-4">
							<?php bloginfo('name'); ?>
							<span
								class="absolute right-0 w-2 h-2 rounded-full bg-primary"
								style="top:-4px;"
								aria-hidden="true"
							></span>
						</span>
					<?php endif; ?>
				</a>

				<p class="max-w-[320px] text-[14px] leading-[1.6] text-white/60">
					<?php esc_html_e('Een zorgvuldig samengestelde bestemming voor originele parfums uit gerenommeerde huizen. Verzonden vanuit Amsterdam.', 'lenvy'); ?>
				</p>

				<div class="mt-8 flex flex-col gap-4 text-[14px]">
					<a
						href="mailto:<?php echo esc_attr($contact_email); ?>"
						class="text-white/70 hover:text-white transition-colors duration-200"
					>
						<?php echo esc_html($contact_email); ?>
					</a>
					<a
						href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $contact_phone)); ?>"
						class="text-white/70 hover:text-white transition-colors duration-200"
					>
						<?php echo esc_html($contact_phone); ?>
					</a>
					<span class="text-[13px] text-white/40">
						<?php echo esc_html($contact_hours); ?>
					</span>
				</div>

				<div class="mt-6 flex gap-3.5">
					<?php foreach ($socials as $social): ?>
						<a
							href="<?php echo esc_url($social['url']); ?>"
							target="_blank"
							rel="noopener noreferrer"
							aria-label="<?php echo esc_attr($social['name']); ?>"
							class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/15 text-white/80 hover:bg-white/10 hover:text-white transition-colors duration-200"
						>
							<?php lenvy_icon($social['icon'], '', 'sm'); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Cols 2-4 — link lists -->
			<?php foreach ($columns as $col): ?>
				<div>
					<h4 class="text-white text-[12px] font-medium tracking-[0.18em] uppercase m-0 mb-5">
						<?php echo esc_html($col['title']); ?>
					</h4>
					<ul class="flex flex-col gap-3 m-0 p-0 list-none">
						<?php foreach ($col['links'] as $link): ?>
							<li>
								<a
									href="<?php echo esc_url($link[1]); ?>"
									class="text-[14px] text-white/70 hover:text-white transition-colors duration-200"
								>
									<?php echo esc_html($link[0]); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>

		</div>

		<!-- ── Bottom bar ──────────────────────────────────────────────── -->
		<div class="pt-7 flex flex-wrap items-center justify-between gap-5 text-[12px] text-white/50">

			<span>
				<?php echo esc_html(sprintf(
					/* translators: %s: current year */
					__('© %s Lenvy B.V. · KVK 81234567 · BTW NL 8623.45.678.B01', 'lenvy'),
					date('Y'),
				)); ?>
			</span>

			<nav aria-label="<?php esc_attr_e('Juridisch', 'lenvy'); ?>" class="flex flex-wrap gap-6">
				<?php foreach ($legal as $link): ?>
					<a
						href="<?php echo esc_url($link[1]); ?>"
						class="text-[12px] text-white/50 hover:text-white transition-colors duration-200"
					>
						<?php echo esc_html($link[0]); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<img
				src="<?php echo esc_url($payment_methods_src); ?>"
				alt="<?php esc_attr_e('iDEAL, Maestro, Mastercard, Visa', 'lenvy'); ?>"
				width="168"
				height="26"
				loading="lazy"
				class="max-h-[26px] w-auto"
			/>

		</div>

	</div>
</footer>
