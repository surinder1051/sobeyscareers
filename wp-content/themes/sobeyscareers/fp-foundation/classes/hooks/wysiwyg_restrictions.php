<?php //phpcs:ignore
/**
 * WYSIWYG Restrictions
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'Wysiwyg_Restrictions' ) ) {

	/**
	 * Restrictions for WYSIWYG editor that remove
	 * - strikethrough
	 * - hr
	 * - forecolor
	 * - pastetext
	 * - removeformat
	 * - charmap
	 * - outdent
	 * - indent
	 * - undo
	 * - redo
	 * - fontsizeselect
	 * - text/html editor
	 * - advance toolbar toggle
	 */
	class Wysiwyg_Restrictions {

		/**
		 * Setup the hooks
		 *
		 * @see self::myplugin_tinymce_buttons_2()
		 * @see self::myplugin_tinymce_buttons_1()
		 * @see self::my_editor_settings()
		 */
		public function __construct() {

			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			add_filter( 'mce_buttons_2', array( $this, 'myplugin_tinymce_buttons_2' ) );
			add_filter( 'mce_buttons', array( $this, 'myplugin_tinymce_buttons_1' ) );
			add_filter( 'wp_editor_settings', array( $this, 'my_editor_settings' ) );

		}

		/**
		 * Remove advanced toolbar options
		 *
		 * @param array $buttons are the default button items.
		 *
		 * @return array $buttons - advanced toolbar button
		 */
		public function myplugin_tinymce_buttons_1( $buttons ) {
			$remove = array( 'wp_adv' );
			return array_diff( $buttons, $remove );
		}

		/**
		 * Remove buttons from the tinymce editor
		 *
		 * @param array $buttons are the default button items.
		 *
		 * @return array $buttons - removed buttons
		 */
		public function myplugin_tinymce_buttons_2( $buttons ) {

			$remove = array( 'strikethrough', 'hr', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'fontsizeselect', 'FontSizes', 'fontsizes', 'font_sizes', 'Font Sizes', 'fontsize_formats' );

			return array_diff( $buttons, $remove );
		}

		/**
		 * Disable text/html tab in WYSIWYG editor.
		 *
		 * @param array $settings are the tinymce settings.
		 *
		 * @return array $settings (modified)
		 */
		public function my_editor_settings( $settings ) {
			$settings['quicktags'] = false;
			$settings['entity_encoding'] = 'numeric';
			return $settings;
		}

	}

	new Wysiwyg_Restrictions();
}


if ( ! function_exists( 'fp_customize_tinymce' ) ) {

	/**
	 * Customize the paste function
	 *
	 * @param string $in is the tinymce objects array.
	 *
	 * @return array $in (updated)
	 */
	function fp_customize_tinymce( $in ) {
		$in['paste_postprocess'] = "function(pl, o) { console.log('bart');	o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;/ig, \" \"); }";
		/**
		 * $in['paste_postprocess'] = "function(pl,o){
		 * // remove the following tags completely:
		 * o.content = o.content.replace(/<\/*(applet|area|article|aside|audio|base|basefont|bdi|bdo|body|button|canvas|command|datalist|details|embed|figcaption|figure|font|footer|frame|frameset|head|header|hgroup|hr|html|iframe|img|keygen|link|map|mark|menu|meta|meter|nav|noframes|noscript|object|optgroup|output|param|progress|rp|rt|ruby|script|section|source|span|style|summary|time|title|track|video|wbr)[^>]*>/gi,'');
		 * // remove all attributes from these tags:
		 *  o.content = o.content.replace(/<(div|table|tbody|tr|td|th|p|b|font|strong|i|em|h1|h2|h3|h4|h5|h6|hr|ul|li|ol|code|blockquote|address|dir|dt|dd|dl|big|cite|del|dfn|ins|kbd|q|samp|small|s|strike|sub|sup|tt|u|var|caption) [^>]*>/gi,'<$1>');
		 * // keep only href in the a tag (needs to be refined to also keep _target and ID):
		 * o.content = o.content.replace(/<a [^>]*href=(\"|')(.*?)(\"|')[^>]*>/gi,'<a href=\"$2\">');
		 * // replace br tag with p tag:
		 * if (o.content.match(/<br[\/\s]*>/gi)) {
		 *  o.content = o.content.replace(/<br[\s\/]*>/gi,'</p><p>');
		 * }
		 * // replace div tag with p tag:
		 * o.content = o.content.replace(/<(\/)*div[^>]*>/gi,'<$1p>');
		 * // remove double paragraphs:
		 * o.content = o.content.replace(/<\/p>[\s\\r\\n]+<\/p>/gi,'</p></p>');
		 * o.content = o.content.replace(/<\<p>[\s\\r\\n]+<p>/gi,'<p><p>');
		 *  o.content = o.content.replace(/<\/p>[\s\\r\\n]+<\/p>/gi,'</p></p>');
		 * o.content = o.content.replace(/<\<p>[\s\\r\\n]+<p>/gi,'<p><p>');
		 * o.content = o.content.replace(/(<\/p>)+/gi,'</p>');
		 * o.content = o.content.replace(/(<p>)+/gi,'<p>');
		 *  }";
		 *
		 * $in['setup'] = 'function(ed) {
		 * ed.onKeyDown.add(function(ed, evt) {
		 * // debugger
		 * if ( $(ed.getBody()).text().length+1 > this.settings.maxlength){
		 * evt.preventDefault();
		 * evt.stopPropagation();
		 * return false;});} ';
		 * init.maxlength = textarea.context.getAttribute('maxlength');
		 * init.setup = function(ed) {
		 * ed.onKeyDown.add(function(ed, evt) {
		 * if ( $(ed.getBody()).text().length+1 > this.settings.maxlength){
		 * evt.preventDefault();
		 * evt.stopPropagation();
		 * return false;
		 * }
		 * });
		 * }
		 */

		return $in;
	}

	add_filter( 'tiny_mce_before_init', 'fp_customize_tinymce' );
}
