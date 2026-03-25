<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\Product;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Products;
}