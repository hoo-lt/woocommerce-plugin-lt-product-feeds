<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\Category;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Categories;
}