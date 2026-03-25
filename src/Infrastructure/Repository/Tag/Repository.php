<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Infrastructure\Repository\Tag;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WordPressPlugin\LtProductFeeds\Domain;
use Hoo\WordPressPlugin\LtProductFeeds\Infrastructure;

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