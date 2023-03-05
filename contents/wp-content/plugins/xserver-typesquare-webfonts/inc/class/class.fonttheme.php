<?php

class TypeSquare_ST_Fonttheme {
	private static $fonttheme;
	private static $instance;

	private function __construct(){}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public static function get_fonttheme() {
		static $fonttheme;

		$fonttheme = array(
			'basic' => array(
				'name'	=> 'ベーシック',
				'fonts' => array(
					'title'   => '新ゴ B',
					'lead'    => '新ゴ B',
					'content' => '新ゴ R',
					'bold'    => '新ゴ B',
				),
			),
			'stylish' => array(
				'name'	=> 'スタイリッシュ',
				'fonts' => array(
					'title'   => '見出ゴMB31',
					'lead'    => '見出ゴMB31',
					'content' => 'TBUDゴシック R',
					'bold'    => 'TBUDゴシック E',
				),
			),
			'news' => array(
				'name'	=> 'ニュース',
				'fonts' => array(
					'title'   => 'リュウミン B-KL',
					'lead'    => 'リュウミン B-KL',
					'content' => '黎ミン M',
					'bold'    => 'リュウミン B-KL',
				),
			),
			'business' => array(
				'name'	=> 'ビジネス',
				'fonts' => array(
					'title'   => 'リュウミン B-KL',
					'lead'    => 'リュウミン B-KL	',
					'content' => 'TBUDゴシック R',
					'bold'    => 'TBUDゴシック E',
				),
			),
			'fashion' => array(
				'name'	=> 'ファッション',
				'fonts' => array(
					'title'   => '丸フォーク M',
					'lead'    => '丸フォーク M',
					'content' => '新ゴ R',
					'bold'    => '新ゴ B',
				),
			),
			'elegant' => array(
				'name'	=> 'エレガント',
				'fonts' => array(
					'title'   => 'A1明朝',
					'lead'    => 'A1明朝',
					'content' => '黎ミン M',
					'bold'    => 'リュウミン B-KL',
				),
			),
			'pop' => array(
				'name'	=> 'ポップ',
				'fonts' => array(
					'title'   => 'ぶらっしゅ',
					'lead'    => 'ぶらっしゅ',
					'content' => 'じゅん501',
					'bold'    => 'G2サンセリフ',
				),
			),
			'comical' => array(
				'name'	=> 'コミカル',
				'fonts' => array(
					'title'   => '新ゴ シャドウ',
					'lead'    => '新ゴ シャドウ',
					'content' => 'じゅん501',
					'bold'    => 'G2サンセリフ-B',
				),
			),
			'wafu' => array(
				'name'	=> '和風',
				'fonts' => array(
					'title'   => 'さくらぎ蛍雪',
					'lead'    => 'しまなみ',
					'content' => 'リュウミン R-KL',
					'bold'    => 'リュウミン B-KL',
				),
			),
			'hannari' => array(
				'name'	=> 'はんなり',
				'fonts' => array(
					'title'   => '那欽',
					'lead'    => '那欽',
					'content' => '黎ミン M',
					'bold'    => 'リュウミン B-KL',
				),
			),
			'natural' => array(
				'name'	=> 'ナチュラル',
				'fonts' => array(
					'title'   => 'はるひ学園',
					'lead'    => 'はるひ学園',
					'content' => 'シネマレター',
					'bold'    => '竹 B',
				),
			),
			'retro' => array(
				'name'	=> 'レトロ',
				'fonts' => array(
					'title'   => '秀英にじみ丸ゴシック B',
					'lead'    => 'シネマレター',
					'content' => 'トーキング',
					'bold'    => 'じゅん 501',
				),
			),
			'horror' => array(
				'name'	=> 'ホラー',
				'fonts' => array(
					'title'   => 'TB古印体',
					'lead'    => 'TB古印体',
					'content' => '陸隷',
					'bold'    => '陸隷',
				),
			),
			'casual' => array(
				'name'	=> 'カジュアル',
				'fonts' => array(
					'title'   => '秀英にじみ丸ゴシック B',
					'lead'    => '丸フォーク M',
					'content' => 'トーキング',
					'bold'    => '秀英にじみ丸ゴシック B',
				),
			),
			'traditional' => array(
				'name'	=> '伝統',
				'fonts' => array(
					'title'   => '教科書ICA M',
					'lead'    => 'さくらぎ蛍雪',
					'content' => 'さくらぎ蛍雪',
					'bold'    => '教科書ICA M',
				),
			),
			'sensitive' => array(
				'name'	=> '繊細',
				'fonts' => array(
					'title'   => 'A1明朝',
					'lead'    => 'しまなみ',
					'content' => 'リュウミン R-KL',
					'bold'    => 'リュウミン B-KL',
				),
			),
		);

		$options = get_option( 'typesquare_custom_theme' );
		if ( $options && isset( $options['theme'] ) && is_array( $options['theme'] ) ) {
			$fonttheme = $fonttheme + $options['theme'];
		}
		return $fonttheme;
	}

	public static function get_custom_theme_json() {
		$options = get_option( 'typesquare_custom_theme' );
		return json_encode($options['theme']);
	}
}
