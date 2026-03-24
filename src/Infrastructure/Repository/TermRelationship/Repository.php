<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\TermRelationship;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repository\TermRelationship\RepositoryInterface
{
	public function __construct(
		protected readonly DatabaseInterface $database,
		protected readonly Infrastructure\Database\Query\Select\TermRelationship\Query $selectTermRelationshipQuery,
		protected readonly Infrastructure\Mapper\TermRelationship\Mapper $termRelationshipMapper,
	) {
	}

	public function objectIds(): array
	{
		return $this->termRelationshipMapper->objectIds($this->database->select(
			$this->selectTermRelationshipQuery
		));
	}
}