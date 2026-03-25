<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Infrastructure\Repository\Brand;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WordPressPlugin\LtProductFeeds\Domain;
use Hoo\WordPressPlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Brand\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Brand\Mapper $brandMapper,
	) {
	}

	public function all(): Domain\Brands
	{
		return $this->brandMapper->all($this->database->select($this->selectTermQuery));
	}
}