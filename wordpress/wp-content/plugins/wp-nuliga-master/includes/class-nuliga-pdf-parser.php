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
	 *   'spielplan' => [ ['datum', 'zeit', 'heim', 'gast', 'ergebnis'], ... ],
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

		return [
			'title'     => $this->parse_title( $text ),
			'tabelle'   => $this->parse_tabelle_from_positions( $dataTm ),
			'spielplan' => $this->parse_spielplan( $text ),
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

	private function parse_spielplan( $text ) {
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

		// Day abbreviations used in German nuLiga PDFs
		$day_re = '(?:Mo|Di|Mi|Do|Fr|Sa|So)';
		// Date+time header pattern: "So.10.05.202609:00" (day, dot, dd.mm.yyyy, hh:mm)
		$header_re = "/^($day_re)\.(\d{2}\.\d{2}\.\d{4})(\d{2}:\d{2})(.*)/u";

		while ( $i < $n ) {
			$line = $sched_lines[ $i ];

			// End of schedule section
			if ( preg_match( '/^(Legende|nu\.Dokument)/i', $line ) ) break;

			if ( preg_match( $header_re, $line, $m ) ) {
				$datum = $m[1] . '. ' . $m[2]; // e.g. "So. 03.05.2026"
				$rest  = trim( $m[4] );
				$i++;

				if ( $rest !== '' && str_contains( $rest, "\t" ) ) {
					// ---- Single game on one line ----
					[ $home, $guest_raw ] = explode( "\t", $rest, 2 );
					[ $guest, $ergebnis ] = $this->split_guest_result( $guest_raw );
					$games[] = [
						'datum'    => $datum,
						'zeit'     => $m[3],
						'heim'     => trim( $home ),
						'gast'     => $guest,
						'ergebnis' => $ergebnis,
					];
				} else {
					// ---- Multi-game block ----
					$times = [ $m[3] ];

					// Collect additional time lines (format "HH:MM")
					while ( $i < $n && preg_match( '/^\d{2}:\d{2}$/', trim( $sched_lines[ $i ] ) ) ) {
						$times[] = trim( $sched_lines[ $i ] );
						$i++;
					}

					$count = count( $times );
					$homes = [];
					$guests = [];

					// Collect home teams
					for ( $k = 0; $k < $count && $i < $n; $k++, $i++ ) {
						$homes[] = trim( $sched_lines[ $i ] );
					}
					// Collect guest teams
					for ( $k = 0; $k < $count && $i < $n; $k++, $i++ ) {
						$guests[] = trim( $sched_lines[ $i ] );
					}
					// Skip trailing empty / Bem. / Erg. lines for this block
					while ( $i < $n && trim( $sched_lines[ $i ] ) === '' ) {
						$i++;
					}

					for ( $k = 0; $k < $count; $k++ ) {
						$games[] = [
							'datum'    => $datum,
							'zeit'     => $times[ $k ],
							'heim'     => $homes[ $k ] ?? '',
							'gast'     => $guests[ $k ] ?? '',
							'ergebnis' => '',
						];
					}
				}
			} else {
				$i++;
			}
		}

		return $games;
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
