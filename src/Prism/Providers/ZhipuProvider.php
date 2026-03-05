<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers;

use Prism\Prism\Providers\OpenAI\OpenAI;

class ZhipuProvider extends OpenAI
{
    public function __construct(string $apiKey)
    {
        parent::__construct(
            apiKey: $apiKey,
            url: 'https://open.bigmodel.cn/api/paas/v4',
            organization: null,
            project: null
        );
    }
}
