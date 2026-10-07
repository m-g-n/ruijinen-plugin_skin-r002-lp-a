<?php
/**
 * コンテンツヘッダーのカスタマイズ.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\ThemesCustomize;

/**
 * ページタイトル上部にサブタイトルを表示する.
 */
class EntryHeader {

	/**
	 * サブタイトルのメタキー.
	 *
	 * @var string
	 */
	private $meta_key = 'rje_r002lp_a_sub_title';

	/**
	 * サブタイトル保存用 nonce のアクション名.
	 *
	 * @var string
	 */
	private $nonce_action = 'rje_r002lp_a_save_sub_title';

	/**
	 * サブタイトル保存用 nonce のフィールド名.
	 *
	 * @var string
	 */
	private $nonce_name = 'rje_r002lp_a_sub_title_nonce';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->input_subtitle();
		$this->view_subtitle();
	}

	/**
	 * ページサブタイトル用の入力ボックスの追加・保存.
	 */
	public function input_subtitle() {
		add_action( 'add_meta_boxes_page', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_page', array( $this, 'save_subtitle' ) );
	}

	/**
	 * サブタイトル入力用のメタボックスを追加.
	 */
	public function add_meta_box() {
		add_meta_box(
			$this->meta_key,
			'[類人猿] ページのサブタイトル',
			array( $this, 'render_meta_box' ),
			'page',
			'side',
			'high'
		);
	}

	/**
	 * サブタイトル入力用のメタボックスを出力.
	 *
	 * @param \WP_Post $post 投稿オブジェクト.
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( $this->nonce_action, $this->nonce_name );
		printf(
			'<input type="text" name="%1$s" value="%2$s" style="width:100%%" />',
			esc_attr( $this->meta_key ),
			esc_attr( get_post_meta( $post->ID, $this->meta_key, true ) )
		);
	}

	/**
	 * サブタイトルを保存.
	 *
	 * @param int $post_id 投稿ID.
	 */
	public function save_subtitle( $post_id ) {
		// メタボックスから送信された場合のみ処理する（クイック編集・REST API 等で消えないようにする）.
		if ( ! isset( $_POST[ $this->nonce_name ] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_name ] ) ), $this->nonce_action ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$subtitle = isset( $_POST[ $this->meta_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->meta_key ] ) ) : '';
		if ( '' !== $subtitle ) {
			update_post_meta( $post_id, $this->meta_key, $subtitle );
		} else {
			delete_post_meta( $post_id, $this->meta_key );
		}
	}

	/**
	 * ページタイトル上部にサブタイトルを追記するためのフック追加.
	 */
	public function view_subtitle() {
		add_filter( 'snow_monkey_template_part_render_template-parts/archive/entry/header/header', array( $this, 'add_sub_title' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/entry/header/header', array( $this, 'add_sub_title' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/common/page-header', array( $this, 'add_sub_title_eyecatch' ) );

		// 投稿アーカイブのタイトルを書換.
		add_filter(
			'rje_r002lp_a_page_sub_title',
			function ( $text ) {
				if ( is_category() || is_tag() ) {
					$text = 'NEWS';
				}
				return $text;
			}
		);
	}

	/**
	 * ページタイトル上部にサブタイトルを追記する.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_sub_title( $html ) {
		return $this->insert_subtitle( $html, '<h1 class="c-entry__title">' );
	}

	/**
	 * ページタイトル上部にサブタイトルを追記する（ページヘッダーの上にタイトルを表示）.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_sub_title_eyecatch( $html ) {
		return $this->insert_subtitle( $html, '<h1 class="c-page-header__title">' );
	}

	/**
	 * 指定したタイトルタグの直前にサブタイトルを挿入する.
	 *
	 * @param string $html      テンプレートパーツの出力HTML.
	 * @param string $title_tag サブタイトルを挿入する位置のタイトル開始タグ.
	 * @return string
	 */
	private function insert_subtitle( $html, $title_tag ) {
		$subtitle = $this->get_subtitle();
		if ( ! $subtitle ) {
			return $html;
		}
		return str_replace(
			$title_tag,
			'<div class="rje-r002lp-a_entry_subtitle">' . esc_html( $subtitle ) . '</div>' . $title_tag,
			$html
		);
	}

	/**
	 * サブタイトルのテキストの設定.
	 *
	 * @return string|null
	 */
	private function get_subtitle() {
		$text = null;
		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );
			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}
			$text = strtoupper( (string) $post_type );
		} elseif ( is_tax() ) {
			$taxonomy = get_taxonomy( get_query_var( 'taxonomy' ) );
			if ( $taxonomy && ! empty( $taxonomy->object_type ) ) {
				$text = strtoupper( $taxonomy->object_type[0] );
			}
		} elseif ( is_home() || is_page() ) {
			$queried_object = get_queried_object();
			if ( $queried_object instanceof \WP_Post ) {
				$text = get_post_meta( $queried_object->ID, $this->meta_key, true );
			}
		}
		return apply_filters( 'rje_r002lp_a_page_sub_title', $text );
	}
}
