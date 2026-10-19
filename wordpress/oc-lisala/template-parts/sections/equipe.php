<?php
/**
 * Section « Équipe » : membres actifs de l'extension, filtres par groupe.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o       = ocl_options( 'sections' );
$can_fix = current_user_can( 'edit_posts' );
if ( ! $o['team_enabled'] ) {
	// Section masquée : seules les personnes qui gèrent le site voient pourquoi.
	if ( current_user_can( 'edit_theme_options' ) ) {
		echo '<section class="section section--team" id="equipe"><div class="container"><p class="editor-note">';
		printf(
			/* translators: %s: lien vers la page des réglages. */
			esc_html__( 'La section Équipe est masquée. Pour l\'afficher, cochez la case Afficher la section « Équipe » dans %s. (Message visible uniquement par les administrateurs connectés.)', 'oc-lisala' ),
			'<a href="' . esc_url( admin_url( 'admin.php?page=ocl-sections#equipe' ) ) . '">' . esc_html__( 'Gestion du site > Textes des sections', 'oc-lisala' ) . '</a>'
		);
		echo '</p></div></section>';
	}
	return;
}

$members = ocl_core_active() ? ocl_core_get_items( 'ocl_membre' ) : array();

// Membres enregistrés mais pas affichés (brouillon, en attente, programmés, ou décochés « Actif »).
$hidden_count = 0;
if ( $can_fix && ocl_core_active() ) {
	$hidden_count = count(
		get_posts(
			array(
				'post_type'      => 'ocl_membre',
				'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		)
	) - count( $members );
}
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

		<?php if ( $can_fix && ! ocl_core_active() ) : ?>
			<p class="editor-note">
				<?php esc_html_e( "Les membres de l'équipe ne peuvent pas s'afficher : l'extension « OC Lisala – Contenus du club » n'est pas activée (Extensions > Extensions installées). Message visible uniquement par les personnes connectées.", 'oc-lisala' ); ?>
			</p>
		<?php elseif ( $hidden_count > 0 ) : ?>
			<p class="editor-note">
				<?php
				printf(
					/* translators: 1: nombre de membres, 2: lien vers la liste. */
					esc_html( _n( '%1$d membre enregistré n\'est pas affiché : il est en brouillon ou décoché « Actif ». Ouvrez %2$s, cliquez sur le membre puis sur « Publier » (ou « Mettre à jour » avec la case « Actif » cochée). Message visible uniquement par les personnes connectées.', '%1$d membres enregistrés ne sont pas affichés : ils sont en brouillon ou décochés « Actif ». Ouvrez %2$s, cliquez sur chaque membre puis sur « Publier » (ou « Mettre à jour » avec la case « Actif » cochée). Message visible uniquement par les personnes connectées.', $hidden_count, 'oc-lisala' ) ),
					(int) $hidden_count,
					'<a href="' . esc_url( admin_url( 'edit.php?post_type=ocl_membre' ) ) . '">' . esc_html__( 'Gestion du site > Équipe', 'oc-lisala' ) . '</a>'
				);
				?>
			</p>
		<?php endif; ?>

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
