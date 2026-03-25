<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\TermRelationship;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\TermRelationship\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\TermRelationship\Query $selectTermRelationshipQuery,
		protected Infrastructure\Mapper\TermRelationship\Mapper $termRelationshipMapper,
	) {
	}

	public function objectIds(): array
	{
		return $this->termRelationshipMapper->objectIds($this->database->select(
			$this->selectTermRelationshipQuery
		));
	}
}