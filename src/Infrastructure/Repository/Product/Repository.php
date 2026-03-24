<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\Product;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repository\Product\RepositoryInterface
{
	public function __construct(
		protected readonly Domain\Repository\TermRelationship\RepositoryInterface $termRelationshipRepository,
		protected readonly DatabaseInterface $database,
		protected readonly Infrastructure\Database\Query\Select\Product\Simple\Query $selectSimpleProductQuery,
		protected readonly Infrastructure\Database\Query\Select\Product\Variable\Query $selectVariableProductQuery,
		protected readonly Infrastructure\Mapper\Product\Mapper $productMapper,
	) {
	}

	public function all(): Domain\Products
	{
		$termRelationshipObjectIds = $this->termRelationshipRepository->objectIds();

		return $this->productMapper->all([
			...$this->database->select(
				$this->selectSimpleProductQuery
					->withPostStatus('publish')
					->withPostIds(872)
			),
		]);
	}
}