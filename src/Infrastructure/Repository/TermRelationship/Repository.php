<?php

namespace Hoo\WordPressPlugin\LtProductFeeds\Infrastructure\Repository\TermRelationship;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WordPressPlugin\LtProductFeeds\Domain;
use Hoo\WordPressPlugin\LtProductFeeds\Infrastructure;

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