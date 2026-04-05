<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product;

readonly class Gtin
{
	public function __construct(
		protected ?string $globalUniqueId,
	) {
	}

	public function __invoke(): ?string
	{
		return $this->globalUniqueId;
	}
}
