<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Presentation\Controllers\TermMeta;

use Hoo\WordPressPluginFramework\Http\RequestInterface;
use Hoo\WordPressPluginFramework\View\ViewInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation;

class Controller
{
	public function __construct(
		protected readonly RequestInterface $request,
		protected readonly ViewInterface $view,
		protected readonly Presentation\Mapper\TermMeta\Mapper $termMetaMapper,
		protected readonly Domain\Repository\TermMeta\RepositoryInterface $termMetaRepository,
	) {
	}

	public function index(int $id): string
	{
		return ($this->view)('term-meta.index', [
			'icon' => $this->termMetaMapper->icon(
				$this->termMetaRepository->get($id)
			),
		]);
	}

	public function add(): string
	{
		return ($this->view)('term-meta.add', [
			'options' => $this->termMetaMapper->options()
		]);
	}

	public function edit(int $id): string
	{
		return ($this->view)('term-meta.edit', [
			'selected' => $this->termMetaMapper->option(
				$this->termMetaRepository->get($id)
			),
			'options' => $this->termMetaMapper->options()
		]);
	}

	public function set(int $id): void
	{
		$value = $this->request->post(Domain\TermMeta::KEY);
		if ($value) {
			$this->termMetaRepository->set($id, Domain\TermMeta::from($value));
		}
	}
}
