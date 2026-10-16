<?php
/**
 * Section « Équipe » : membres actifs de l'extension, filtres par groupe.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'sections' );
if ( ! $o['team_enabled'] ) {
	return;
}

$members = ocl_core_active() ? ocl_core_get_items( 'ocl_membre' ) : array();
$filters = array(
	'staff'     => __( 'Staff', 'oc-lisala' ),
	'gardien'   => __( 'Gardiens', 'oc-lisala' ),
	'defenseur' => __( 'Défenseurs', 'oc-lisala' ),
	'milieu'    => __( 'Milieux', 'oc-lisala' ),
	'attaquant' => __( 'Attaquants', 'oc-lisala' ),
	'autre'     => __( 'Autres', 'oc-lisala' ),
);
$present = array();
foreach ( $members as $member ) {
	$present[ ocl_core_field( $member->ID, 'group' ) ] = true;
}
$filters = array_intersect_key( $filters, $present );
?>
<section class="section section--team" id="equipe"<?php echo ocl_labelledby( $o['team_title'], 'team-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<?php
		ocl_section_head(
			array(
				'eyebrow' => $o['team_eyebrow'],
				'title'   => $o['team_title'],
				'intro'   => $o['team_intro'],
				'id'      => 'team-title',
			)
		);
		?>

		<?php if ( ! $members ) : ?>
			<?php if ( $o['team_empty'] ) : ?>
				<p class="empty-state"><?php echo esc_html( $o['team_empty'] ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<?php if ( $o['team_filters'] && count( $filters ) > 1 ) : ?>
				<div class="filters" role="group" aria-label="<?php esc_attr_e( "Filtrer l'effectif", 'oc-lisala' ); ?>">
					<button type="button" class="filter is-active" data-filter="all" aria-pressed="true"><?php esc_html_e( 'Tous', 'oc-lisala' ); ?></button>
					<?php foreach ( $filters as $key => $label ) : ?>
						<button type="button" class="filter" data-filter="<?php echo esc_attr( $key ); ?>" aria-pressed="false"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<ul class="team-grid" id="team-grid" aria-live="polite">
				<?php foreach ( $members as $member ) : ?>
					<?php
					$group       = ocl_core_field( $member->ID, 'group' );
					$is_staff    = 'staff' === $group;
					$number      = ocl_core_field( $member->ID, 'number' );
					$role        = ocl_core_field( $member->ID, 'role' );
					$description = ocl_core_field( $member->ID, 'description' );
					$name        = get_the_title( $member );
					?>
					<li class="player-card<?php echo $is_staff ? ' player-card--staff' : ''; ?>" data-group="<?php echo esc_attr( $group ); ?>">
						<div class="player-card__photo">
							<?php
							if ( has_post_thumbnail( $member ) ) {
								echo get_the_post_thumbnail(
									$member,
									'ocl-card',
									array(
										'alt'     => $name,
										'loading' => 'lazy',
										'sizes'   => '(max-width: 640px) 50vw, 260px',
									)
								);
							}
							?>
							<?php if ( '' !== $number && ! $is_staff ) : ?>
								<span class="player-card__number"><span class="screen-reader-text"><?php esc_html_e( 'Numéro', 'oc-lisala' ); ?> </span><?php echo esc_html( $number ); ?></span>
							<?php endif; ?>
						</div>
						<div class="player-card__body">
							<?php if ( '' !== $role ) : ?>
								<span class="player-card__role"><?php echo esc_html( $role ); ?></span>
							<?php endif; ?>
							<span class="player-card__name"><?php echo esc_html( $name ); ?></span>
							<?php if ( '' !== $description ) : ?>
								<span class="player-card__desc"><?php echo esc_html( $description ); ?></span>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
