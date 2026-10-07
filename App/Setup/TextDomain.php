<?php
/**
 * 翻訳ファイルの読み込み.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\Setup;

/**
 * テキストドメインを読み込む.
 */
class TextDomain {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'load_textdomain_mofile', array( $this, 'load_textdomain_mofile' ), 10, 2 );
		load_plugin_textdomain( RJE_SKIN_R002_LP_A_TEXTDOMAIN, false, dirname( RJE_SKIN_R002_LP_A_BASENAME ) . '/languages' );
	}

	/**
	 * When local .mo file exists, load this.
	 *
	 * @param string $mofile Path to the MO file.
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @return string
	 */
	public function load_textdomain_mofile( $mofile, $domain ) {
		if ( RJE_SKIN_R002_LP_A_TEXTDOMAIN === $domain ) {
			$local_mofile = RJE_SKIN_R002_LP_A_PATH . 'languages/' . basename( $mofile );
			if ( file_exists( $local_mofile ) ) {
				return $local_mofile;
			}
		}
		return $mofile;
	}
}
