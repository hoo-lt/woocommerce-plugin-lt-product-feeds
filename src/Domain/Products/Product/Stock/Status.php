<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product\Stock;

enum Status: string
{
	case InStock = 'instock';
	case OutOfStock = 'outofstock';
	case OnBackorder = 'onbackorder';
}