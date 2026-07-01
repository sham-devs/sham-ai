<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Enums\FinishReason;
use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;
use Prism\Prism\ValueObjects\Meta;
use Prism\Prism\ValueObjects\Usage;

class MistralProvider extends BaseHuggingFaceProvider
{
    /**
     * Text generation models use 'parameters' key.
     */
    protected string $optionsKey = 'parameters';

    public function text(TextRequest $request): TextResponse
    {
        $runtimeOptions = [];
        $model = $request->model();
        $prompt = (string) $request->prompt();
        $options = $request->providerOptions();

        // Add text generation options if provided
        if (isset($options['max_new_tokens'])) {
            $runtimeOptions['max_new_tokens'] = (int) $options['max_new_tokens'];
        }
        if (isset($options['temperature'])) {
            $runtimeOptions['temperature'] = (float) $options['temperature'];
        }
        if (isset($options['top_p'])) {
            $runtimeOptions['top_p'] = (float) $options['top_p'];
        }
        if (isset($options['top_k'])) {
            $runtimeOptions['top_k'] = (int) $options['top_k'];
        }
        if (isset($options['do_sample'])) {
            $runtimeOptions['do_sample'] = (bool) $options['do_sample'];
        }

        $payload = $this->buildPayload($prompt, $runtimeOptions);
        $result = $this->sendRequest($model, $payload);

        $text = $result[0]['generated_text'] ?? '';

        return new TextResponse(
            steps: collect(),
            text: $text,
            finishReason: FinishReason::Stop,
            toolCalls: [],
            toolResults: [],
            usage: new Usage(0, 0),
            meta: new Meta(id: uniqid(), model: $model),
            messages: collect(),
            raw: $result
        );
    }
}
