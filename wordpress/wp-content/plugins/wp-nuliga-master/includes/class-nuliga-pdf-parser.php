<?php

if ( ! defined( 'WPINC' ) ) die;

/**
 * Fetches and parses nuLiga ScheduleReportFOP PDFs.
 *
 * The PDF contains two side-by-side sections:
 *  – Tabelle (standings): left column
 *  – Spielplan (schedule): right column
 *
 * Both are extracted from the PDF text output.
 */
class Nuliga_Pdf_Parser {

	const CACHE_EXPIRY = 43200; // 12 hours

	/**
	 * Main entry point. Returns cached-or-fresh result:
	 * [
	 *   'title'     => string,
	 *   'tabelle'   => [ ['rang', 'team', 'beg', 'punkte', 'matches', 'saetze', 'spiele'], ... ],
	 *   'spielplan' => [ ['datum', 'zeit', 'heim', 'gast', 'ergebnis', 'spielort'], ... ],
	 *   'error'     => string|null,
	 * ]
	 */
	public function get_schedule( $url ) {
		if ( ! $this->is_valid_nuliga_url( $url ) ) {
			return [ 'error' => 'Ungültige nuLiga URL' ];
		}

		$cache_key = 'nuliga_pdf_' . md5( $url );
		$cached    = get_transient( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}

		try {
			$pdf_content = $this->fetch_pdf( $url );
			$data        = $this->parse_pdf( $pdf_content );
		} catch ( Exception $e ) {
			return [ 'error' => $e->getMessage() ];
		}

		if ( empty( $data['error'] ) ) {
			set_transient( $cache_key, $data, self::CACHE_EXPIRY );
		}
		return $data;
	}

	// -------------------------------------------------------------------------
	// Helpers

	private function is_valid_nuliga_url( $url ) {
		return (bool) preg_match( '#^https?://[a-z0-9-]+\.liga\.nu/#i', $url );
	}

	private function fetch_pdf( $url ) {
		$response = wp_remote_get( $url, [
			'timeout'   => 30,
			'sslverify' => false,
			'headers'   => [
				'Accept-Language' => 'de',
				'User-Agent'      => 'Mozilla/5.0 (compatible)',
				'Accept'          => 'application/pdf',
			],
		] );

		if ( is_wp_error( $response ) ) {
			throw new Exception( 'PDF-Abruf fehlgeschlagen: ' . $response->get_error_message() );
		}
		$code = wp_remote_retrieve_response_code( $response );
		if ( $code !== 200 ) {
			throw new Exception( "HTTP Fehler: $code" );
		}
		$body = wp_remote_retrieve_body( $response );
		if ( substr( $body, 0, 4 ) !== '%PDF' ) {
			throw new Exception( 'Antwort ist kein PDF-Dokument' );
		}
		return $body;
	}

	// -------------------------------------------------------------------------
	// PDF → text

	private function parse_pdf( $content ) {
		$autoload = plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';
		if ( ! file_exists( $autoload ) ) {
			throw new Exception(
				'PDF-Parser nicht installiert. Bitte im Plugin-Verzeichnis ausführen: composer install'
			);
		}
		require_once $autoload;

		$config = new \Smalot\PdfParser\Config();
		$config->setRetainImageContent( false );

		$pdf    = ( new \Smalot\PdfParser\Parser( [], $config ) )->parseContent( $content );
		$text   = $pdf->getText();
		$dataTm = $pdf->getPages()[0]->getDataTm(); // standings fit on first page

		$tabelle    = $this->parse_tabelle_from_positions( $dataTm );
		$team_names = array_column( $tabelle, 'team' );

		return [
			'title'     => $this->parse_title( $text ),
			'tabelle'   => $tabelle,
			'spielplan' => $this->parse_spielplan( $text, $team_names ),
			'error'     => null,
		];
	}

	// -------------------------------------------------------------------------
	// Title

	private function parse_title( $text ) {
		// First two non-empty lines are typically "Region | Saison" and "Kategorie Gr."
		$lines = $this->lines( $text );
		$title_parts = [];
		foreach ( $lines as $line ) {
			if ( $line === '' ) continue;
			// Stop before section headers
			if ( preg_match( '/^(Rang|Termin|Spielleiter)/i', $line ) ) break;
			$title_parts[] = $line;
			if ( count( $title_parts ) >= 2 ) break;
		}
		return implode( ' – ', $title_parts );
	}

	// -------------------------------------------------------------------------
	// Standings (Tabelle) — reconstructed from DataTm positions
	//
	// Items with x < STANDINGS_MAX_X belong to the standings table (left column).
	// Each row is uniquely identified by its Y coordinate.
	// Column assignment is based on X position thresholds derived from the header row.

	const STANDINGS_MAX_X = 400; // X cutoff between standings (left) and schedule (right)

	private function parse_tabelle_from_positions( $data_tm ) {
		// Collect items for the left column only
		$left_items = [];
		foreach ( $data_tm as $entry ) {
			if ( ! isset( $entry[0], $entry[1] ) ) continue;
			$text = trim( (string) $entry[1] );
			if ( $text === '' ) continue;
			$x = (float) $entry[0][4];
			$y = (float) $entry[0][5];
			if ( $x > self::STANDINGS_MAX_X ) continue;
			$left_items[] = [ 'x' => $x, 'y' => $y, 'text' => $text ];
		}

		if ( empty( $left_items ) ) return [];

		// Group by Y coordinate (tolerance ±2 points)
		$y_rows = [];
		foreach ( $left_items as $item ) {
			$placed = false;
			foreach ( $y_rows as &$row ) {
				if ( abs( $row['y'] - $item['y'] ) <= 2 ) {
					$row['cells'][] = $item;
					$placed = true;
					break;
				}
			}
			unset( $row );
			if ( ! $placed ) {
				$y_rows[] = [ 'y' => $item['y'], 'cells' => [ $item ] ];
			}
		}

		// Sort rows by Y ascending (higher Y = lower on page in this PDF's coordinate system)
		usort( $y_rows, fn( $a, $b ) => $a['y'] <=> $b['y'] );

		// Sort cells within each row by X ascending (left to right)
		foreach ( $y_rows as &$row ) {
			usort( $row['cells'], fn( $a, $b ) => $a['x'] <=> $b['x'] );
		}
		unset( $row );

		// Identify the header row (contains "Rang" or "Mannschaft")
		$header_row = null;
		foreach ( $y_rows as $row ) {
			$texts = array_map( fn( $c ) => $c['text'], $row['cells'] );
			$flat  = implode( ' ', $texts );
			if ( str_contains( $flat, 'Rang' ) || str_contains( $flat, 'Mannschaft' ) ) {
				$header_row = $row;
				break;
			}
		}
		if ( $header_row === null ) return [];

		// Build column X boundaries from header row
		// Expected columns (left to right): Rang, Mannschaft, Beg., Punkte, Matches, Sätze, Spiele
		$header_cols = array_map( fn( $c ) => $c['x'], $header_row['cells'] );
		$col_keys    = [ 'rang', 'team', 'beg', 'punkte', 'matches', 'saetze', 'spiele' ];

		// Assign header cells to column keys by position order
		$col_x = [];
		foreach ( $header_row['cells'] as $idx => $cell ) {
			if ( isset( $col_keys[ $idx ] ) ) {
				$col_x[ $col_keys[ $idx ] ] = $cell['x'];
			}
		}

		// Parse data rows that follow the header
		$header_y = $header_row['y'];
		$rows     = [];

		foreach ( $y_rows as $row ) {
			if ( $row['y'] <= $header_y ) continue; // skip header and everything above it

			// Each data row must start with a rank digit
			$first = trim( $row['cells'][0]['text'] ?? '' );
			if ( ! preg_match( '/^\d+$/', $first ) ) continue;

			// Assign cells to columns by X proximity
			$mapped = array_fill_keys( $col_keys, '' );
			foreach ( $row['cells'] as $cell ) {
				$key = $this->nearest_column( $cell['x'], $col_x );
				if ( $key ) {
					$mapped[ $key ] .= ( $mapped[ $key ] !== '' ? ' ' : '' ) . $cell['text'];
				}
			}

			// Strip club ID from team name: "RSV Wullenstetten (04240)" → "RSV Wullenstetten"
			$mapped['team'] = preg_replace( '/\s*\(\d+\)\s*$/', '', trim( $mapped['team'] ) );

			$rows[] = $mapped;
		}

		return $rows;
	}

	/** Returns the column key whose X coordinate is closest to $x (within 30 points). */
	private function nearest_column( $x, $col_x ) {
		$best_key  = null;
		$best_dist = PHP_FLOAT_MAX;
		foreach ( $col_x as $key => $cx ) {
			$dist = abs( $x - $cx );
			if ( $dist < $best_dist && $dist <= 30 ) {
				$best_dist = $dist;
				$best_key  = $key;
			}
		}
		return $best_key;
	}

	// -------------------------------------------------------------------------
	// Schedule (Spielplan)
	//
	// Text format after "TerminHeimmannschaft\tGastmannschaft\tBem.Erg." header:
	//
	//   Single game:
	//     So.03.05.202609:00RSV Wullenstetten\tTSF Ludwigsfeld Neu-Ulm 5:4
	//
	//   Multi-game date:
	//     So.10.05.202609:00             ← date + first time (no home/guest)
	//     09:00                          ← additional times (N-1 lines)
	//     ...
	//     TV Bellenberg II               ← N home teams
	//     ...
	//     TC Illertissen                 ← N guest teams
	//     ...
	//     [empty lines for Bem./Erg.]

	private function parse_spielplan( $text, $team_names = [] ) {
		$lines = $this->lines( $text );

		// Find schedule header
		$start = -1;
		foreach ( $lines as $i => $line ) {
			if ( str_starts_with( $line, 'Termin' ) && str_contains( $line, 'Heim' ) ) {
				$start = $i + 1;
				break;
			}
		}
		if ( $start === -1 ) return [];

		$sched_lines = array_slice( $lines, $start );
		$games = [];
		$i     = 0;
		$n     = count( $sched_lines );

		$day_re = '(?:Mo|Di|Mi|Do|Fr|Sa|So)';
		// Normal header: "So.10.05.202609:00..."
		$header_re    = "/^($day_re)\.(\d{2}\.\d{2}\.\d{4})(\d{2}:\d{2})(.*)/u";
		// Rescheduled header: day abbreviation alone (date/time follow on separate lines)
		$split_day_re = "/^($day_re)\.$/u";

		while ( $i < $n ) {
			$line = $sched_lines[ $i ];

			if ( preg_match( '/^(Legende|nu\.Dokument)/i', $line ) ) break;

			$datum      = null;
			$first_time = null;
			$rest       = null;

			if ( preg_match( $header_re, $line, $m ) ) {
				$datum      = $m[1] . '. ' . $m[2];
				$first_time = $m[3];
				$rest       = trim( $m[4] );
				$i++;

			} elseif ( preg_match( $split_day_re, $line, $ms ) ) {
				// Rescheduled game: day on its own, then blank, date, blank, time on separate lines
				$i++;
				while ( $i < $n && trim( $sched_lines[ $i ] ) === '' ) $i++;
				$dp = trim( $sched_lines[ $i ] ?? '' );
				if ( ! preg_match( '/^\d{2}\.\d{2}\.\d{4}$/', $dp ) ) { $i++; continue; }
				$i++;
				while ( $i < $n && trim( $sched_lines[ $i ] ) === '' ) $i++;
				$ft = trim( $sched_lines[ $i ] ?? '' );
				if ( ! preg_match( '/^\d{2}:\d{2}$/', $ft ) ) { $i++; continue; }
				$i++;
				$datum      = $ms[1] . '. ' . $dp;
				$first_time = $ft;
				$rest       = '';

			} else {
				$i++;
				continue;
			}

			// ---- Single game — tab-separated ----
			if ( $rest !== '' && str_contains( $rest, "\t" ) ) {
				[ $home, $guest_raw ] = explode( "\t", $rest, 2 );
				[ $guest, $ergebnis ] = $this->split_guest_result( $guest_raw );
				$games[] = [
					'datum'    => $datum,
					'zeit'     => $first_time,
					'heim'     => trim( $home ),
					'gast'     => $guest,
					'ergebnis' => $ergebnis,
					'spielort' => '',
				];
				continue;
			}

			// ---- Single game — no tab (long team names) ----
			if ( $rest !== '' ) {
				[ $home, $guest, $ergebnis ] = $this->split_by_team_names( $rest, $team_names );
				$games[] = [
					'datum'    => $datum,
					'zeit'     => $first_time,
					'heim'     => $home,
					'gast'     => $guest,
					'ergebnis' => $ergebnis,
					'spielort' => '',
				];
				continue;
			}

			// ---- Multi-game block ----
			// Collect additional times. Blank lines between times are skipped only when
			// another time follows (handles both rescheduled blocks and regular multi-game dates).
			$times = [ $first_time ];
			while ( $i < $n ) {
				$tl = trim( $sched_lines[ $i ] );
				if ( preg_match( '/^\d{2}:\d{2}$/', $tl ) ) {
					$times[] = $tl;
					$i++;
				} elseif ( $tl === '' && isset( $sched_lines[ $i + 1 ] ) &&
					preg_match( '/^\d{2}:\d{2}$/', trim( $sched_lines[ $i + 1 ] ) ) ) {
					$i++; // skip blank between times
				} else {
					break;
				}
			}

			$count      = count( $times );
			$homes      = [];
			$spielorten = array_fill( 0, $count, '' );
			$guests     = [];

			// Collect home teams — skip blank lines and "» ursprünglich..." remarks.
			// "Spielort: ..." lines are associated with the preceding home team and skipped.
			while ( count( $homes ) < $count && $i < $n ) {
				$tl = trim( $sched_lines[ $i++ ] );
				if ( $tl === '' || str_starts_with( $tl, '»' ) ) continue;
				if ( str_starts_with( $tl, 'Spielort:' ) ) {
					$last = count( $homes ) - 1;
					if ( $last >= 0 ) {
						$spielorten[ $last ] = trim( substr( $tl, 9 ) );
					}
					continue;
				}
				$homes[] = $tl;
			}

			// Collect guest teams — skip blank lines and remarks
			while ( count( $guests ) < $count && $i < $n ) {
				$tl = trim( $sched_lines[ $i++ ] );
				if ( $tl === '' || str_starts_with( $tl, '»' ) ) continue;
				$guests[] = $tl;
			}

			// Collect results by scanning: skip blanks/remarks, stop at next date header
			$results = array_fill( 0, $count, '' );
			$r_idx   = 0;
			while ( $i < $n && $r_idx < $count ) {
				$tl = trim( $sched_lines[ $i ] );
				if ( preg_match( '/^\d+:\d+$|^w\.?o\.?$/i', $tl ) ) {
					$results[ $r_idx++ ] = $tl;
					$i++;
				} elseif ( $tl === '' || str_starts_with( $tl, '»' ) ) {
					$i++;
				} else {
					break; // next date header or end of section
				}
			}

			for ( $k = 0; $k < $count; $k++ ) {
				$games[] = [
					'datum'    => $datum,
					'zeit'     => $times[ $k ],
					'heim'     => $homes[ $k ] ?? '',
					'gast'     => $guests[ $k ] ?? '',
					'ergebnis' => $results[ $k ] ?? '',
					'spielort' => $spielorten[ $k ] ?? '',
				];
			}
		}

		return $games;
	}

	/**
	 * Splits "TeamA TeamB 4:2" into [home, guest, result] using known team names.
	 * Tries each team name as home prefix, then looks for a second team name after it.
	 * Falls back to [full string, "", ""] if no match found.
	 */
	private function split_by_team_names( $rest, $team_names ) {
		// Longest names first to avoid partial matches (e.g. "TC X II" before "TC X")
		usort( $team_names, fn( $a, $b ) => strlen( $b ) - strlen( $a ) );

		foreach ( $team_names as $home ) {
			if ( ! str_starts_with( $rest, $home ) ) continue;
			$after = ltrim( substr( $rest, strlen( $home ) ) );

			foreach ( $team_names as $guest ) {
				if ( $guest === $home ) continue;
				if ( ! str_starts_with( $after, $guest ) ) continue;
				$tail = trim( substr( $after, strlen( $guest ) ) );
				// Whatever remains is the result (e.g. "4:2") or empty
				$ergebnis = preg_match( '/^\d+:\d+$|^w\.?o\.?$/i', $tail ) ? $tail : '';
				return [ $home, $guest, $ergebnis ];
			}
		}

		// No team match found — return the raw string as home, empty guest
		return [ trim( $rest ), '', '' ];
	}

	/**
	 * Splits "TSF Ludwigsfeld Neu-Ulm 5:4" into ["TSF Ludwigsfeld Neu-Ulm", "5:4"].
	 * Returns ["team", ""] when no result is present.
	 */
	private function split_guest_result( $raw ) {
		$raw = trim( $raw );
		// Result is the last token if it matches score format (digits:digits or "w:o")
		if ( preg_match( '/^(.*?)\s+(\d+:\d+|w\.?o\.?)(\s*)$/u', $raw, $m ) ) {
			return [ trim( $m[1] ), $m[2] ];
		}
		return [ $raw, '' ];
	}

	// -------------------------------------------------------------------------
	// Utility

	/** Split text into trimmed lines, normalise line endings. */
	private function lines( $text ) {
		return array_map( 'trim', explode( "\n", str_replace( [ "\r\n", "\r" ], "\n", $text ) ) );
	}
}
