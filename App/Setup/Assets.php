<?php
/**
 * アセットの読み込み.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\Setup;

/**
 * フロント用のスタイルを読み込む.
 */
class Assets {
	/**
	 * Snow Monkey のメインスタイルのハンドルを格納.
	 *
	 * @var string[]
	 */
	public $sm_style_handles = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'get_sm_style_handles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 11 ); // HACK: Snow MonkeyのCSSより前に読み込まれるときがあるため優先度を下げて読み込ませる.
	}

	/**
	 * Get Snow Monkey Style Handles.
	 */
	public function get_sm_style_handles() {
		// Snow Monkeyテーマからメインスタイルのハンドルを取得.
		if ( method_exists( '\Framework\Helper', 'get_main_style_handle' ) ) {
			$this->sm_style_handles = (array) \Framework\Helper::get_main_style_handle();
		}
	}

	/**
	 * Enqueue front assets.
	 */
	public function wp_enqueue_scripts() {
		$style_path = RJE_SKIN_R002_LP_A_PATH . 'dist/css/style.css';
		if ( ! file_exists( $style_path ) ) {
			return;
		}

		wp_enqueue_style(
			RJE_SKIN_R002_LP_A_KEY,
			RJE_SKIN_R002_LP_A_URL . 'dist/css/style.css',
			$this->sm_style_handles,
			(string) filemtime( $style_path )
		);
	}
}
