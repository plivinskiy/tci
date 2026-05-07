<?php

if ( ! defined( 'WPINC' ) ) die;

class Nuliga_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/nuliga-public.css',
			[],
			$this->version,
			'all'
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/nuliga-public.js',
			[ 'jquery' ],
			$this->version,
			false
		);

		wp_localize_script( $this->plugin_name, 'script_vars', [
			'basis' => plugin_dir_url( __FILE__ ) . 'nutab/',
		] );
	}

	/**
	 * Registers all shortcodes provided by this plugin.
	 * Called on the 'init' hook.
	 */
	public function register_shortcodes() {
		add_shortcode( 'nuliga_pdf', [ $this, 'shortcode_pdf' ] );
	}

	/**
	 * [nuliga_pdf url="https://..." type="spielplan" highlight="TC Illertissen" title="Spielplan"]
	 *
	 * url       – full nuLiga PDF URL (required)
	 * type      – "spielplan" (default), "tabelle", or "beide"
	 * title     – optional heading; auto-detected from PDF when empty
	 * highlight – club/team name fragment; matching rows get the srsHome green highlight
	 */
	public function shortcode_pdf( $atts ) {
		$atts = shortcode_atts( [
			'url'       => '',
			'type'      => 'spielplan',
			'title'     => '',
			'highlight' => '',
		], $atts, 'nuliga_pdf' );

		if ( empty( $atts['url'] ) ) {
			return '<p class="nuliga-error">nuliga_pdf: Bitte eine URL angeben.</p>';
		}

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-nuliga-pdf-parser.php';
		$parser = new Nuliga_Pdf_Parser();
		$data   = $parser->get_schedule( $atts['url'] );

		if ( ! empty( $data['error'] ) ) {
			return '<p class="nuliga-error">' . esc_html( $data['error'] ) . '</p>';
		}

		$highlight   = strtolower( $atts['highlight'] );
		$table_title = $atts['title'] ?: ( $data['title'] ?? '' );
		$type        = $atts['type'];

		ob_start();

		if ( $table_title ) {
			echo '<p class="nuliga-pdf-title">' . esc_html( $table_title ) . '</p>';
		}

		if ( in_array( $type, [ 'tabelle', 'beide' ], true ) ) {
			$this->render_tabelle( $data['tabelle'] ?? [], $highlight );
		}
		if ( in_array( $type, [ 'spielplan', 'beide' ], true ) ) {
			$this->render_spielplan( $data['spielplan'] ?? [], $highlight );
		}

		return ob_get_clean();
	}

	private function render_tabelle( $rows, $highlight ) {
		if ( empty( $rows ) ) {
			echo '<p class="nuliga-error">Keine Tabellendaten gefunden.</p>';
			return;
		}
		?>
		<div class="nuliga-pdf-wrap nuliga-pdf-tab">
			<p class="nuliga-pdf-title">Tabelle</p>
			<div>
				<table class="srsTable nuliga-pdf-table">
					<thead>
						<tr>
							<th class="c">Rang</th>
							<th>Mannschaft</th>
							<th class="c">Beg.</th>
							<th class="c">Punkte</th>
							<th class="c">Matches</th>
							<th class="c">Sätze</th>
							<th class="c">Spiele</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $r ) :
							$row_class = '';
							if ( $highlight && str_contains( strtolower( $r['team'] ), $highlight ) ) {
								$row_class = ' class="srsHome"';
							}
							?>
							<tr<?php echo $row_class; ?>>
								<td class="c"><?php echo esc_html( $r['rang'] ); ?></td>
								<td><?php echo esc_html( $r['team'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['beg'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['punkte'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['matches'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['saetze'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['spiele'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	private function render_spielplan( $rows, $highlight ) {
		if ( empty( $rows ) ) {
			echo '<p class="nuliga-error">Keine Spielplandaten gefunden.</p>';
			return;
		}
		?>
		<div class="nuliga-pdf-wrap nuliga-pdf-plan">
			<p class="nuliga-pdf-title">Spielplan</p>
			<div>
				<table class="srsTable nuliga-pdf-table">
					<thead>
						<tr>
							<th>Datum</th>
							<th class="c">Zeit</th>
							<th>Heim</th>
							<th>Gast</th>
							<th class="c">Ergebnis</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$prev_datum = '';
						foreach ( $rows as $r ) :
							$row_class = '';
							if ( $highlight ) {
								$row_text = strtolower( $r['heim'] . ' ' . $r['gast'] );
								if ( str_contains( $row_text, $highlight ) ) {
									$row_class = ' class="srsHome"';
								}
							}
							// Show date only when it changes
							$datum_cell = ( $r['datum'] !== $prev_datum ) ? $r['datum'] : '';
							$prev_datum = $r['datum'];
							?>
							<tr<?php echo $row_class; ?>>
								<td><?php echo esc_html( $datum_cell ); ?></td>
								<td class="c"><?php echo esc_html( $r['zeit'] ); ?></td>
								<td><?php echo esc_html( $r['heim'] ); ?></td>
								<td><?php echo esc_html( $r['gast'] ); ?></td>
								<td class="c"><?php echo esc_html( $r['ergebnis'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
}
