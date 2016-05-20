<?php

namespace Core\Service\ChainFilter;



class SeoUrlStrategy extends AbstractChainFilterStrategy {
	public function filter($chain) {
		$replaceAccentsFilter = new ReplaceAccentsStrategy ();
		$chain = strip_tags  ( $chain );
		$chain = $replaceAccentsFilter->filter ( $chain );
		$chain = strtolower ( $chain );
		$chain = trim ( $chain );
		$chain = preg_replace ( '/\-+/', '', $chain );
		$chain = preg_replace ( '/\:+/', '', $chain );
		$chain = preg_replace ( '/\,+/', '', $chain );
		$chain = preg_replace ( '/\'+/', '', $chain );
		$chain = preg_replace ( '/\"+/', '', $chain );
		$chain = preg_replace ( '/\/+/', '', $chain );
		$chain = preg_replace ( '/\%+/', '', $chain );
		$chain = preg_replace ( '/\s+/', '-', $chain );
		return $chain;
	}
}