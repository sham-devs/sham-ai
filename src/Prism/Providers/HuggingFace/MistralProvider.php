<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers\HuggingFace;

use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\ValueObjects\Usage;
use Prism\Prism\ValueObjects\Meta;

class MistralProvider extends BaseHuggingFaceProvider
{
    /**
     * Text generation models use 'parameters' key.
     */
    protected string $optionsKey = 'parameters';

    public function text(TextRequest $request): TextResponse
    {
        $runtimeOptions = [];

        // Add text generation options if provided
        if (isset($request->options['max_new_tokens'])) {
            $runtimeOptions['max_new_tokens'] = (int) $request->options['max_new_tokens'];
        }
        if (isset($request->options['temperature'])) {
            $runtimeOptions['temperature'] = (float) $request->options['temperature'];
        }
        if (isset($request->options['top_p'])) {
            $runtimeOptions['top_p'] = (float) $request->options['top_p'];
        }
        if (isset($request->options['top_k'])) {
            $runtimeOptions['top_k'] = (int) $request->options['top_k'];
        }
        if (isset($request->options['do_sample'])) {
            $runtimeOptions['do_sample'] = (bool) $request->options['do_sample'];
        }

        $payload = $this->buildPayload($request->prompt, $runtimeOptions);
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
