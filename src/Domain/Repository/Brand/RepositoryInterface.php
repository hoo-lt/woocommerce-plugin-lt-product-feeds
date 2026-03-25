<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\Brand;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Brands;
}