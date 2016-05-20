<?php

namespace Core\Service\ChainFilter;



class ExchangeEurosStrategy extends AbstractChainFilterStrategy {
	public function filter($chain) {
		return $chain;
	}
}