<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers\HuggingFace;

use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\ValueObjects\Usage;
use Prism\Prism\ValueObjects\Meta;

class LlamaProvider extends BaseHuggingFaceProvider
{
    public function text(TextRequest $request): TextResponse
    {
        $payload = [
            'inputs' => $request->prompt,
        ];

        $result = $this->sendRequest($request->model, $payload);
        
        $text = $result[0]['generated_text'] ?? '';

        return new TextResponse(
            steps: collect(),
            text: $text,
            finishReason: FinishReason::Stop,
            toolCalls: [],
            toolResults: [],
            usage: new Usage(0, 0),
            meta: new Meta(id: uniqid(), model: $request->model),
            messages: collect(),
            raw: $result
        );
    }
}
