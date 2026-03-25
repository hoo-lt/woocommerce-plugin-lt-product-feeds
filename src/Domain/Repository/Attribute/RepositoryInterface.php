<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\Attribute;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Attributes;
}