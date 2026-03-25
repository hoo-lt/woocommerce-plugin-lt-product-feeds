<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Infrastructure\Repository\Category;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WordPressPlugin\LtProductFeeds\Domain;
use Hoo\WordPressPlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Category\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Category\Mapper $categoryMapper,
	) {
	}

	public function all(): Domain\Categories
	{
		return $this->categoryMapper->all($this->database->select($this->selectTermQuery));
	}
}