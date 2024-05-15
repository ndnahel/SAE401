<?php

declare(strict_types=1);

namespace App\Service;

class Api
{
	/**
	 * @return string
	 */
	public function getApiKey(): string
	{
		return $_ENV['API_KEY'];
	}
}
