<?php
/**
* all helpers return a value rather than printing it
*/
class DZHelpers
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


	/**
	 * prints completed meta description and meta keyword tags
	 * 
	 * looks for local $_meta_keywords and $_meta_description variables but 
	 * defaults to the values defined in config.php.
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