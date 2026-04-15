<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Queries\Select\Product\Variation;

use Hoo\WordPressPluginFramework\Database\Queries\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Queries\QueryException;
use Hoo\WordPressPluginFramework\Database\Table\TableInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected TableInterface $table,
		protected array $ids = [],
		protected array $statuses = [],
		protected array $parentIds = [],
		protected array $parentStatuses = [],
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function withIds(int ...$ids): self
	{
		return new self(
			$this->table,
			$ids,
			$this->statuses,
			$this->parentIds,
			$this->parentStatuses
		);
	}

	public function withStatuses(Domain\Products\Product\Status ...$statuses): self
	{
		return new self(
			$this->table,
			$this->ids,
			$statuses,
			$this->parentIds,
			$this->parentStatuses
		);
	}

	public function withParentIds(int ...$parentIds): self
	{
		return new self(
			$this->table,
			$this->ids,
			$this->statuses,
			$parentIds,
			$this->parentStatuses
		);
	}

	public function withParentStatuses(Domain\Products\Product\Status ...$parentStatuses): self
	{
		return new self(
			$this->table,
			$this->ids,
			$this->statuses,
			$this->parentIds,
			$parentStatuses
		);
	}

	public function __toString(): string
	{
		return strtr($this->query, [
			':AND posts.ID' => $this->ids ? 'AND posts.ID IN (' . implode(',', $this->ids) . ')' : '',
			':AND posts.post_status' => $this->statuses ? "AND posts.post_status IN ('" . implode("','", array_map(fn($status) => $status->value, $this->statuses)) . "')" : '',
			':AND parent_posts.ID' => $this->parentIds ? 'AND parent_posts.ID IN (' . implode(',', $this->parentIds) . ')' : '',
			':AND parent_posts.post_status' => $this->parentStatuses ? "AND parent_posts.post_status IN ('" . implode("','", array_map(fn($status) => $status->value, $this->parentStatuses)) . "')" : '',
		]);
	}

	protected function path(): string
	{
		$path = __DIR__ . '/Query.sql';
		if (!file_exists($path)) {
			throw new QueryException('.sql file not found');
		}

		return $path;
	}

	protected function query(string $path): string
	{
		return strtr(file_get_contents($path), [
			':term_relationships' => ($this->table)('term_relationships'),
			':posts' => ($this->table)('posts'),
			':term_taxonomy' => ($this->table)('term_taxonomy'),
			':terms' => ($this->table)('terms'),
			':postmeta' => ($this->table)('postmeta'),
		]);
	}
}