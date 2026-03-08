<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\ValueObjects\Usage;
use Prism\Prism\ValueObjects\Meta;

class NllbProvider extends BaseHuggingFaceProvider
{
    /**
     * NLLB uses 'options' key in the payload.
     */
    protected string $optionsKey = 'options';

    public function text(TextRequest $request): TextResponse
    {
        // Build runtime options from request
        $runtimeOptions = [];

        // Get src_lang from options or infer from request context
        if (isset($request->options['src_lang'])) {
            $runtimeOptions['src_lang'] = $request->options['src_lang'];
        } elseif (isset($request->options['fromLocale'])) {
            $runtimeOptions['src_lang'] = $this->extractLangCode($request->options['fromLocale']);
        }

        // Get tgt_lang from options or infer from request context
        if (isset($request->options['tgt_lang'])) {
            $runtimeOptions['tgt_lang'] = $request->options['tgt_lang'];
        } elseif (isset($request->options['toLocale'])) {
            $runtimeOptions['tgt_lang'] = $this->extractLangCode($request->options['toLocale']);
        }

        // Build payload with merged options (defaults + runtime)
        $payload = $this->buildPayload($request->prompt, $runtimeOptions);

        $result = $this->sendRequest($request->model, $payload);

        $text = $result[0]['translation_text'] ?? '';

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
