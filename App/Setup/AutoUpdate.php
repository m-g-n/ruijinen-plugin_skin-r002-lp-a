<?php
/**
 * GitHub を利用した自動更新.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\Setup;

use Inc2734\WP_GitHub_Plugin_Updater\Bootstrap as Updater;

/**
 * GitHub のリリースからプラグインを更新する.
 */
class AutoUpdate {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'activate_autoupdate' ) );
	}

	/**
	 * Activate auto update using GitHub.
	 *
	 * @return void
	 */
	public function activate_autoupdate() {
		new Updater(
			RJE_SKIN_R002_LP_A_BASENAME,
			'm-g-n',
			'ruijinen-plugin_skin-r002-lp-a',
			array(
				'description_url' => 'https://rui-jin-en.com/block_patterns/r002-lp/',
				'faq_url'         => 'https://rui-jin-en.com/help/',
				'changelog_url'   => 'https://rui-jin-en.com/category/product-renew/',
				'icons'           => array(
					'1x' => 'https://rui-jin-en.com/wp-content/uploads/2022/02/icon-64x64-1.png', // Image URL 64×64.
					'2x' => 'https://rui-jin-en.com/wp-content/uploads/2022/02/icon-128x128-1.png', // Image URL 128×128.
				),
				'tested'          => '6.7', // Tested up WordPress version.
				'requires_php'    => '7.4', // Requires PHP version.
				'requires'        => '6.1', // Requires WordPress version.
			)
		);
	}
}
