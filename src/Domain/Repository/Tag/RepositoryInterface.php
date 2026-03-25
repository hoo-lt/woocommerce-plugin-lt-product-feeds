<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\Tag;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Tags;
}