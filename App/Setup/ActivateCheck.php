<?php
/**
 * プラグインの動作要件チェック.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\Setup;

/**
 * 動作に必要な環境が揃っているかをチェックする.
 */
class ActivateCheck {
	/**
	 * 要件を満たしていない場合のメッセージ.
	 *
	 * @var string[]
	 */
	public $messages = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->check_rje_block_patterns_activate();
	}

	/**
	 * Check the required environment and Plugin Activation.
	 */
	public function check_rje_block_patterns_activate() {
		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			$this->messages['rje_r002_lp_a'] = 'スキンプラグインを利用するには「Snow Monkey」テーマを有効にしている必要があります';
		}
	}

	/**
	 * 必要なパッケージがアクティベートされてない場合のエラーメッセージ.
	 */
	public function make_alert_message() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>[類人猿スキン LPパターン用]</strong></p>';
		foreach ( $this->messages as $text ) {
			echo '<p>' . esc_html( $text ) . '</p>';
		}
		echo '</div>';
	}
}
