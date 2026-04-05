<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product;

readonly class Stock
{
	public function __construct(
		protected ?bool $manageStock,
		protected ?bool $parentManageStock,
		protected Stock\Status $status,
		protected ?Stock\Status $parentStatus,
		protected ?int $stock,
		protected ?int $parentStock,
	) {
	}

	public function status(): Stock\Status
	{
		if ($this->manageStock) {
			return $this->status;
		}

		if ($this->parentStatus !== null) {
			return $this->parentStatus;
		}

		return $this->status;
	}

	public function quantity(): ?int
	{
		if ($this->manageStock) {
			return $this->stock;
		}

		if ($this->parentStock !== null) {
			return $this->parentStock;
		}

		return $this->stock;
	}
}
