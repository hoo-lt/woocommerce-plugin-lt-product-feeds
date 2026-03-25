<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Domain\Repository\TermMeta;

use Hoo\WordPressPlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function get(int $id): Domain\TermMeta;
	public function set(int $id, Domain\TermMeta $termMeta): void;
}