<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Presentation\Actions\TermMeta\Post;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation\ValidatedRequests\TermMeta\Post\ValidatedRequest;

readonly class Action
{
	public function __construct(
		protected ValidatedRequest $validatedRequest,
		protected Domain\Repository\TermMeta\RepositoryInterface $termMetaRepository,
	) {
	}

	public function __invoke(int $id): void
	{
		$this->termMetaRepository->set(
			$id,
			Domain\TermMeta::from(
				$this->validatedRequest->post(Domain\TermMeta::KEY)
			)
		);
	}
}
