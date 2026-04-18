<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Presentation\ValidatedRequests\TermMeta\Post;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation\ValidatedRequests\ValidatedRequestException;
use Hoo\WordPressPluginFramework\Http\RequestInterface;

readonly class ValidatedRequest implements RequestInterface
{
	public function __construct(
		protected RequestInterface $request,
	) {
		$this->validate();
	}

	public function get(string $key): ?string
	{
		return $this->request->get($key);
	}

	public function post(string $key): ?string
	{
		return $this->request->post($key);
	}

	protected function validate(): void
	{
		if ($this->request->post(Domain\TermMeta::KEY) === null) {
			throw new ValidatedRequestException('required post value is missing');
		}
	}
}