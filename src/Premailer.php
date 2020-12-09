<?php
namespace Pbc;
use GuzzleHttp\Client;
use voku\CssToInlineStyles\CssToInlineStyles;

/**
 * Premailer API PHP class
 * Premailer is a library/service for making HTML more palatable for various inept email clients, in particular GMail
 * Primary function is to convert style tags into equivalent inline styles so styling can survive <head> tag removal
 * Premailer is owned by Dialect Communications group
 * @link http://premailer.dialect.ca/api
 * @author Marcus Bointon <marcus@synchromedia.co.uk>
 */

class Premailer {

	/**
	 * Central static method for submitting either an HTML string or a URL, optionally retrieving converted versions
	 * @static
	 *
	 * @param string $html Raw HTML source
	 * @param string $url URL of the source file
	 * @param bool $fetchresult Whether to also fetch the converted output
	 * @param string $adaptor Which document handler to use (hpricot (default) or nokigiri)
	 * @param string $base_url Base URL for converting relative links
	 * @param int $line_length Length of lines in the plain text version (default 65)
	 * @param string $link_query_string Query string appended to links
	 * @param bool $preserve_styles Whether to preserve any link rel=stylesheet and style elements
	 * @param bool $remove_ids Remove IDs from the HTML document?
	 * @param bool $remove_classes Remove classes from the HTML document?
	 * @param bool $remove_comments Remove comments from the HTML document?
	 * @return array
	 * @throws \Exception @codeCoverageIgnore
	 */
	protected static function convert($html = '', $url = '', $fetchresult = true, $adaptor = 'hpricot', $base_url = '', $line_length = 65, $link_query_string = '', $preserve_styles = true, $remove_ids = false, $remove_classes = false, $remove_comments = false) {
        if ($url !== '') {
            $client = new Client();
            $response = $client->get($url);
            $html = $response->getBody()->getContents();
        }

	    $cssToInlineStyles = new CssToInlineStyles($html);
        $cssToInlineStyles->setUseInlineStylesBlock(true);
        if (!$preserve_styles) {
            $cssToInlineStyles->setStripOriginalStyleTags(true);
        }
        $converted = $cssToInlineStyles->convert();

        $cssToInlineStyles = new CssToInlineStyles($html);
        $cssToInlineStyles->setStripOriginalStyleTags(true);
        $cssToInlineStyles->setCleanup(true);
        $plain = $cssToInlineStyles->convert();

        return [
            'result' => $converted,
            'html' => $converted,
            'plain' => wordwrap(strip_tags($plain), $line_length)
        ];
	}

	/**
	 * Central static method for submitting either an HTML string or a URL, optionally retrieving converted versions
	 * @static
	 * @param string $html Raw HTML source
	 * @param bool $fetchresult Whether to also fetch the converted output
	 * @param string $adaptor Which document handler to use (hpricot (default) or nokigiri)
	 * @param string $base_url Base URL for converting relative links
	 * @param int $line_length Length of lines in the plain text version (default 65)
	 * @param string $link_query_string Query string appended to links
	 * @param bool $preserve_styles Whether to preserve any link rel=stylesheet and style elements
	 * @param bool $remove_ids Remove IDs from the HTML document?
	 * @param bool $remove_classes Remove classes from the HTML document?
	 * @param bool $remove_comments Remove comments from the HTML document?
	 * @return array Either a single element array containing the 'result' object, or three elements containing result, html and plain if $fetchresult is set
	 */
	public static function html($html, $fetchresult = true, $adaptor = 'hpricot', $base_url = '', $line_length = 65, $link_query_string = '', $preserve_styles = true, $remove_ids = false, $remove_classes = false, $remove_comments = false) {
		return self::convert($html, '', $fetchresult, $adaptor, $base_url, $line_length, $link_query_string, $preserve_styles, $remove_ids, $remove_classes, $remove_comments);
	}

	/**
	 * Central static method for submitting either an HTML string or a URL, optionally retrieving converted versions
	 * @static
	 * @param string $url URL of the source file
	 * @param bool $fetchresult Whether to also fetch the converted output
	 * @param string $adaptor Which document handler to use (hpricot (default) or nokigiri)
	 * @param string $base_url Base URL for converting relative links
	 * @param int $line_length Length of lines in the plain text version (default 65)
	 * @param string $link_query_string Query string appended to links
	 * @param bool $preserve_styles Whether to preserve any link rel=stylesheet and style elements
	 * @param bool $remove_ids Remove IDs from the HTML document?
	 * @param bool $remove_classes Remove classes from the HTML document?
	 * @param bool $remove_comments Remove comments from the HTML document?
	 * @return array Either a single element array containing the 'result' object, or three elements containing result, html and plain if $fetchresult is set
	 */
	public static function url($url, $fetchresult = true, $adaptor = 'hpricot', $base_url = '', $line_length = 65, $link_query_string = '', $preserve_styles = true, $remove_ids = false, $remove_classes = false, $remove_comments = false) {
		return self::convert('', $url, $fetchresult, $adaptor, $base_url, $line_length, $link_query_string, $preserve_styles, $remove_ids, $remove_classes, $remove_comments);
	}
}

/*
Simplest usage:
$pre = Premailer::html($var_with_some_html_in);
$html = $pre['html'];
$plain = $pre['plain'];
//Similarly for URLs:
$pre = Premailer::url($url);
*/
