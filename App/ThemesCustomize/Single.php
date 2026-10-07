<?php
/**
 * Singleページ関連のカスタマイズ.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\ThemesCustomize;

/**
 * Singleページの出力を変更する.
 */
class Single {

	/**
	 * Constructor.
	 */
	public function __construct() {
		remove_action( 'snow_monkey_entry_meta_items', 'snow_monkey_entry_meta_items_author', 30 ); // author表示の削除.
		add_filter( 'snow_monkey_get_template_part_args_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_args' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_html' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/related-posts', array( $this, 'add_entries_class' ) );
	}

	/**
	 * 前へ次へのナビゲーション表記を変更.
	 *
	 * @param array $args テンプレートパーツの引数.
	 * @return array
	 */
	public function prev_next_nav_args( $args ) {
		$args['vars']['_next_label'] = '前の記事';
		$args['vars']['_prev_label'] = '次の記事';
		return $args;
	}

	/**
	 * 前へ次へナビゲーションの出力タグを変更.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function prev_next_nav_html( $html ) {
		$target_words = array(
			'figure'     => array(
				'before' => '/<div class="c-prev-next-nav__item-figure">(.*?)<\/div>/s',
				'after'  => '',
			),
			'title'      => array(
				'before' => '/<div class="c-prev-next-nav__item-title">(.*?)<\/div>/s',
				'after'  => '',
			),
			'root_class' => array(
				'before' => '/c-prev-next-nav/',
				'after'  => 'rje-r002lp-a_prev_next_nav',
			),
			'arrow_icon' => array(
				'before' => '/class="(?:fas|fa-solid) fa-angle-/', // Snow Monkey のバージョンにより Font Awesome のクラス表記が異なる.
				'after'  => 'class="rje-r002lp-a_pagination_arrow --',
			),
		);
		foreach ( $target_words as $word ) {
			$html = preg_replace( $word['before'], $word['after'], $html );
		}
		return $html;
	}

	/**
	 * Entries original class add.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_entries_class( $html ) {
		return str_replace( 'p-related-posts ', 'p-related-posts is-style-RJE_R002LP_news_list ', $html );
	}
}
