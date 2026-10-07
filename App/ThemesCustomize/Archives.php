<?php
/**
 * 投稿アーカイブ関連のカスタマイズ.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\ThemesCustomize;

/**
 * 投稿アーカイブの出力を変更する.
 */
class Archives {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'inc2734_wp_basis_posts_pagination_args', array( $this, 'change_arrow' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/archive/archive', array( $this, 'add_entries_class' ) );
	}

	/**
	 * Pagination Customize.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_the_posts_pagination/
	 * @param array $args ページネーションの引数.
	 * @return array
	 */
	public function change_arrow( $args ) {
		$args = is_array( $args ) ? $args : array();

		$args['prev_text'] = '<i class="rje-r002lp-a_pagination_arrow --left" aria-hidden="true"></i>';
		$args['next_text'] = '<i class="rje-r002lp-a_pagination_arrow --right" aria-hidden="true"></i>';
		return $args;
	}

	/**
	 * Entries original class add.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_entries_class( $html ) {
		return str_replace(
			'<div class="p-archive">',
			'<div class="p-archive is-style-RJE_R002LP_news_list">',
			$html
		);
	}
}
