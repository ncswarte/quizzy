<?php

App::uses('HtmlHelper', 'View/Helper');

class MyHtmlHelper extends HtmlHelper {
	public function url($url = null, $full = false) {
		if(!isset($url['language']) && isset($this->params['language'])) {
			//Fix for external links - they don't need languages
			if( is_array( $url ) )
				$url['language'] = $this->params['language'];
		}
		return parent::url($url, $full);
	}
}
