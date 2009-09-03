<?php
/**
* all helpers return a value rather than printing it
*/
class Doozer_Helpers
{
	private $dz;
	
	public function __construct($dz)
	{
		$this->dz = $dz;
	}


	public function test_with_param($param)
	{
		return $param;
	}

	/*
	 * Creates a 'slug name' by stripping 
	 * special characters from the given page name
	 * and replacing spaces with dashes.
	 * 
	 * Used for setting unique id's on <li> elements
	 * in navigation as well as linking to files that follow its naming convention.
	 */
	public function slug($string)
	{
		return $this->replace_chars(strtolower($this->strip_special_chars($string)));
	}


	/*
	 * Strips special characters from a string.
	 */
	private function strip_special_chars($string)
	{
		# Define special characters that will be stripped from the name
		$special_chars = array('.',',','?','!','$','|','(',')',':','"',"'",'*','&#39;','&copy;','&reg;','&trade;');	
		$processed_string = str_replace($special_chars, '', $string);
		return $processed_string;
	}


	/*
	 * Loops through a hash of replacements
	 * and replaces the key with its value in the given string.
	 *
	 * $replacements array has default values which can be overridden when called
	 */
	private function replace_chars($string, $replacements=array('&amp;' => 'and','&' => 'and', ' ' => '-','/' => '-'))
	{
		return str_replace(array_keys($replacements), array_values($replacements), $string);
	}

	/*
	 * Converts a string to make it suitable for use in a title tag.
	 * Similar to slug_name, but keeps spaces.
	 */
	public function sanitize_title_text($string)
	{
		$sanitized_text = strip_special_chars($string);
		$sanitized_text = replace_chars($sanitized_text, $replacements=array('&amp;' => 'and', '&' => 'and', '/' => '-'));
		return $sanitized_text;
	}


	/**
	 * prints completed meta description and meta keyword tags
	 * 
	 * looks for local $_meta_keywords and $_meta_description variables but 
	 * defaults to the site values
	 */
	public function meta_tags()
	{
		$meta_tags = array('keywords' => $this->dz->config['meta_keywords'], 'description' => $this->dz->config['meta_description']);
		$html = '';
		foreach ($meta_tags as $name => $content)
		{
			$html .= $this->tag('meta', array('name' => $name, 'content' => $content))."\n";
		}
		return $html;
	}
	
	
	/**
	 * prints the script tag for creating a spam-friendly email link
	 */
	public function email_link_tag($address)
	{
		$pieces = explode("@", $address);
		$name = $pieces[0];
		$domain = $pieces[1];
		$js = "<!--\n";
		$js .= "var name = \"$name\";\n";
		$js .= "var domain = \"$domain\";\n";
		$js .= "document.write('<a href=\\\"mailto:' + name + '@' + domain + '\\\">');\n";
		$js .= "document.write(name + '@' + domain + '</a>');\n";
		$js .= "// -->\n";
		return $this->content_tag('script', $js, array('type' => 'text/javascript'));
	}


	public function image_tag($file, $alt='', $class='', $title='')
	{
		$file = "images/$file";
		if(file_exists($file))
		{
			list($w, $h) = getimagesize("$file");
			return $this->tag('img', array('src' => $file, 'width' => $w, 'height' => $h, 'alt' => $alt, 'class' => $class, 'title' => $title));
		}
	}

	/**
	 * tag helpers ported from rails
	 */
	public function tag($name, $options, $open = false, $escape = true)
	{
		$options = isset($options) ? $this->tag_options($options, $escape) : '';
		$close = $open ? '>' : ' />';
		return '<'.$name.$options.$close;
	}


	public function content_tag($name, $content, $options, $escape = true)
	{
		$options = isset($options) ? $this->tag_options($options, $escape) : '';
		return "<".$name.$tag_options.'>'.$content.'</'.$name.'>';
	}


	private function escape_once($html)
	{
		# TODO
		return $html;
	}


	private function tag_options($options, $escape = true)
	{
		$boolean_attributes = array('disabled', 'readonly', 'multiple', 'checked', 'autobuffer',
		                           'autoplay', 'controls', 'loop', 'selected', 'hidden', 'scoped', 'async',
		                           'defer', 'reversed', 'ismap', 'seemless', 'muted', 'required',
		                           'autofocus', 'novalidate', 'formnovalidate', 'open');
		if (isset($options))
		{
			$attrs = array();
			foreach ($options as $key => $value) {
				if (in_array($key, $boolean_attributes)) {
					if (isset($value)) {
						$attrs[] = "$key=\"$key\"";
					}
				}
				elseif (isset($value) && !empty($value)) {
					$final_value = (is_array($value)) ? implode(' ', $value) : $value;
					if ($escape) {
						$final_value = $this->escape_once($final_value);
					}
					$attrs[] = "$key=\"$final_value\"";
				}
			}
		}
		if (!empty($attrs)) {
			$attrs = implode(' ', $attrs);
			return " $attrs";
		}
	}


	protected function __call($method, $params) {
		return '';
	}
}

?>