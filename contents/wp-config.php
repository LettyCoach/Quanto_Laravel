<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * データベース設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link https://ja.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** データベース設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define( 'DB_NAME', 'quanto_wp1' );

/** データベースのユーザー名 */
define( 'DB_USER', 'quanto_wp1' );

/** データベースのパスワード */
define( 'DB_PASSWORD', 'k9q6on0x0o' );

/** データベースのホスト名 */
define( 'DB_HOST', 'mysql204.xbiz.ne.jp' );

/** データベースのテーブルを作成する際のデータベースの文字セット */
define( 'DB_CHARSET', 'utf8' );

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define( 'DB_COLLATE', '' );

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Uw@r>C*x~H7%euqVgU)]3e|V Q,Ax{+yC-+F]/9pJe39O^GVNM:jW_hYP@eIel^-' );
define( 'SECURE_AUTH_KEY',  'd F3H@@T/w) vC;*N?$x;gs+m QS?w2vRO[Z_noSyXzbHd$-v^][lhLhX;W03K^u' );
define( 'LOGGED_IN_KEY',    'd335rkqh%#SHrey-!0eFGZwG40B(K=kZ7a&}0oR8=j_u~Gs:0:KBhu_Kf0i&7*l(' );
define( 'NONCE_KEY',        'SMaga1:sGe!&)jIbZjz3-!j_1Xun7+,B(T(4<YSKlR;Y-hNxorY]l{vO<<Zc_ASA' );
define( 'AUTH_SALT',        'DwkN<K(&*7=?,E]2vca{mY9N6RD-^ym5Y#%a@W)Dp?g$m> {kXdI)e)e=aXF8 ;M' );
define( 'SECURE_AUTH_SALT', 'fnP 5-f: 88gQ2kz?+G~fI4Ox]0@lC!0OJWiT85X*WN7708eB{z96YaZu&8e.UU;' );
define( 'LOGGED_IN_SALT',   '`r^ZA/L_-]<4:ntlv$`Qigja.1B5~[m5FT,uQ@t&h|bkn(FqiXx[qcOeqg@SOC*G' );
define( 'NONCE_SALT',       '}R&xC.19<2)%b`I%Uq]v9|S[{Vq3?l_qLhEMk)z.#XGW+[ttZ`)5!n@cHZ|R-uW$' );
define( 'WP_CACHE_KEY_SALT','_5tVSG& UD@uHpTexjKqZ6^uXlN~hc~Ep1ScDn1JipJAaxC6(]Ov2#jk6r:}+w?7' );

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数についてはドキュメンテーションをご覧ください。
 *
 * @link https://ja.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* カスタム値は、この行と「編集が必要なのはここまでです」の行の間に追加してください。 */



/* 編集が必要なのはここまでです ! WordPress でのパブリッシングをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
