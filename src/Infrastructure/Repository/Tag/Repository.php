<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\Tag;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Tag\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Tag\Mapper $tagMapper,
	) {
	}

	public function all(): Domain\Tags
	{
		return $this->tagMapper->all($this->database->select($this->selectTermQuery));
	}
}