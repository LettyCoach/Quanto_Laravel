<?php
/**
 * WordPress �δ�������
 *
 * ���Υե�����ϡ����󥹥ȡ������ wp-config.php �������������ɤ����Ѥ��ޤ���
 * ���������ɤ�𤵤��ˤ��Υե������ "wp-config.php" �Ȥ���̾���ǥ��ԡ�����
 * ľ���Խ������ͤ����Ϥ��Ƥ⤫�ޤ��ޤ���
 *
 * ���Υե�����ϡ��ʲ��������ޤߤޤ���
 *
 * * �ǡ����١�������
 * * ��̩��
 * * �ǡ����١����ơ��֥���Ƭ��
 * * ABSPATH
 *
 * @link https://ja.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ���:
// Windows �� "���Ģ" �Ǥ��Υե�������Խ����ʤ��Ǥ������� !
// ����ʤ��Ȥ���ƥ����ȥ��ǥ���
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF ����)
// ����Ѥ���ɬ�� UTF-8 �� BOM �ʤ� (UTF-8N) ����¸���Ƥ���������

// ** �ǡ����١������� - ���ξ���ϥۥ��ƥ����褫�����ꤷ�Ƥ��������� ** //
/** WordPress �Τ���Υǡ����١���̾ */
define( 'DB_NAME', 'quanto_wp1' );

/** �ǡ����١����Υ桼����̾ */
define( 'DB_USER', 'quanto_wp1' );

/** �ǡ����١����Υѥ���� */
define( 'DB_PASSWORD', 'k9q6on0x0o' );

/** �ǡ����١����Υۥ���̾ */
define( 'DB_HOST', 'mysql204.xbiz.ne.jp' );

/** �ǡ����١����Υơ��֥���������ݤΥǡ����١�����ʸ�����å� */
define( 'DB_CHARSET', 'utf8' );

/** �ǡ����١����ξȹ��� (�ۤȤ�ɤξ���ѹ�����ɬ�פϤ���ޤ���) */
define( 'DB_COLLATE', '' );

/**#@+
 * ǧ���ѥ�ˡ�������
 *
 * ���줾���ۤʤ��ˡ��� (���) ��ʸ������ѹ����Ƥ���������
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org ����̩�������ӥ�} �Ǽ�ư�������뤳�Ȥ�Ǥ��ޤ���
 * ��Ǥ��ĤǤ��ѹ����ơ���¸�Τ��٤Ƥ� cookie ��̵���ˤǤ��ޤ�������ˤ�ꡢ���٤ƤΥ桼��������Ū�˺ƥ����󤵤��뤳�Ȥˤʤ�ޤ���
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
 * WordPress �ǡ����١����ơ��֥����Ƭ��
 *
 * ���줾��˥�ˡ��� (���) ����Ƭ����Ϳ���뤳�Ȥǰ�ĤΥǡ����١�����ʣ���� WordPress ��
 * ���󥹥ȡ��뤹�뤳�Ȥ��Ǥ��ޤ���Ⱦ�ѱѿ����Ȳ����Τߤ���Ѥ��Ƥ���������
 */
$table_prefix = 'wp_';

/**
 * ��ȯ�Ԥ�: WordPress �ǥХå��⡼��
 *
 * �����ͤ� true �ˤ���ȡ���ȯ������ (notice) ��ɽ�����ޤ���
 * �ơ��ޤ���ӥץ饰����γ�ȯ�Ԥˤϡ����γ�ȯ�Ķ��ˤ����Ƥ��� WP_DEBUG ����Ѥ��뤳�Ȥ򶯤��侩���ޤ���
 *
 * ����¾�ΥǥХå������ѤǤ�������ˤĤ��Ƥϥɥ�����ơ�������������������
 *
 * @link https://ja.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* ���������ͤϡ����ιԤȡ��Խ���ɬ�פʤΤϤ����ޤǤǤ��פιԤδ֤��ɲä��Ƥ��������� */



/* �Խ���ɬ�פʤΤϤ����ޤǤǤ� ! WordPress �ǤΥѥ֥�å��󥰤򤪳ڤ��ߤ��������� */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
