<?php
/**
 * プラグイン更新時のお知らせメッセージ.
 *
 * @package ruijinen-skin-r002-lp
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R002_LP\App\Setup;

/**
 * プラグイン一覧の更新アラートにメッセージを追加する.
 */
class InPluginUpdateMessage {

	/**
	 * お知らせJSONのキャッシュ時間（秒）.
	 */
	const CACHE_EXPIRATION = HOUR_IN_SECONDS;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'in_plugin_update_message-' . RJE_SKIN_R002_LP_A_BASENAME, array( $this, 'in_plugin_update_message' ) );
	}

	/**
	 * 更新画面のアラートボックスにメッセージを追加.
	 *
	 * @param array $data プラグインのデータ.
	 */
	public function in_plugin_update_message( $data ) {
		if ( empty( $data['new_version'] ) ) {
			return;
		}

		$notice = $this->get_the_notice_json( $data['new_version'] );
		if ( empty( $notice['message'] ) ) {
			return;
		}

		echo '<br>' . wp_kses_post( $notice['message'] );
		if ( ! empty( $notice['url'] ) ) {
			echo '<a href="' . esc_url( $notice['url'] ) . '" target="_blank" rel="noopener"> &#62;&#62;詳細を見る</a>';
		}
	}

	/**
	 * JSONからデータを取得して指定バージョンのメッセージ情報を返す.
	 *
	 * @param string $version バージョン.
	 * @return array|false メッセージ情報（message, url）. 該当なしの場合は false.
	 */
	private function get_the_notice_json( $version ) {
		$notices = $this->fetch_notices();
		if ( ! isset( $notices[ $version ] ) || ! is_array( $notices[ $version ] ) ) {
			return false;
		}
		return $notices[ $version ];
	}

	/**
	 * お知らせJSONを取得し、バージョンをキーにした配列で返す.
	 *
	 * @return array
	 */
	private function fetch_notices() {
		$cache_key = 'rje_skin_r002_lp_a_update_notice';
		$cached    = get_transient( $cache_key );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$notices  = array();
		$url      = 'https://rui-jin-en.com/update-notice/' . RJE_SKIN_R002_LP_A_KEY . '.json';
		$response = wp_remote_get( $url, array( 'timeout' => 5 ) );

		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$json = wp_remote_retrieve_body( $response );
			if ( function_exists( 'mb_convert_encoding' ) ) {
				$json = mb_convert_encoding( $json, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' ); // 文字コードをUTF-8に変換.
			}
			$items = json_decode( $json, true );

			// バージョンをキーにしたオブジェクトの配列になっているため、階層を1つ浅くする.
			if ( is_array( $items ) ) {
				foreach ( $items as $item ) {
					if ( is_array( $item ) ) {
						$notices = array_merge( $notices, $item );
					}
				}
			}
		}

		// 取得に失敗した場合も空配列をキャッシュし、毎回の外部リクエストを避ける.
		set_transient( $cache_key, $notices, self::CACHE_EXPIRATION );

		return $notices;
	}
}
