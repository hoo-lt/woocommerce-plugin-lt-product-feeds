<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Query\Select\Product\Simple;

use Hoo\WordPressPluginFramework\Database\Query\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Query\QueryException;
use wpdb;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected wpdb $wpdb,
		protected ?string $postStatus = null,
		protected ?array $postIds = null,
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function withPostStatus(string $postStatus): self
	{
		return new self(
			$this->wpdb,
			$postStatus,
			$this->postIds,
		);
	}

	public function withPostIds(int ...$postIds): self
	{
		return new self(
			$this->wpdb,
			$this->postStatus,
			$postIds,
		);
	}

	public function __invoke(): string
	{
		$args = [];

		if ($this->postStatus) {
			$args = [
				...$args,
				$this->postStatus
			];
		}

		if ($this->postIds) {
			$args = [
				...$args,
				...$this->postIds,
			];
		}

		return $this->wpdb->prepare(strtr($this->query, [
			':AND posts.post_status' => $this->postStatus ? 'AND posts.post_status = %s' : '',
			':AND posts.ID' => $this->postIds ? 'AND posts.ID IN (' . implode(',', array_map(fn() => '%d', $this->postIds)) . ')' : '',
		]), $args);
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
			':term_relationships' => $this->wpdb->term_relationships,
			':posts' => $this->wpdb->posts,
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
			':postmeta' => $this->wpdb->postmeta,
		]);
	}
}