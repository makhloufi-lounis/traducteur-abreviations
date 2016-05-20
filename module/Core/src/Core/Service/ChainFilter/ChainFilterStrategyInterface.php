<?php

namespace Core\Service\ChainFilter;


interface ChainFilterStrategyInterface {
	public function filter($chain);
}