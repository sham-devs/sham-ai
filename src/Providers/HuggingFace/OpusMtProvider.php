<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Enums\FinishReason;
use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;
use Prism\Prism\ValueObjects\Meta;
use Prism\Prism\ValueObjects\Usage;

class OpusMtProvider extends BaseHuggingFaceProvider
{
    /**
     * Opus-MT uses 'options' key like NLLB.
     */
    protected string $optionsKey = 'options';

    public function text(TextRequest $request): TextResponse
    {
        $runtimeOptions = [];
        $model = $request->model();
        $prompt = (string) $request->prompt();
        $options = $request->providerOptions();

        // Get src_lang from options or infer from request context
        if (isset($options['src_lang'])) {
            $runtimeOptions['src_lang'] = $options['src_lang'];
        } elseif (isset($options['fromLocale'])) {
            $runtimeOptions['src_lang'] = $this->extractLangCode($options['fromLocale']);
        }

        // Get tgt_lang from options or infer from request context
        if (isset($options['tgt_lang'])) {
            $runtimeOptions['tgt_lang'] = $options['tgt_lang'];
        } elseif (isset($options['toLocale'])) {
            $runtimeOptions['tgt_lang'] = $this->extractLangCode($options['toLocale']);
        }

        $payload = $this->buildPayload($prompt, $runtimeOptions);
        $result = $this->sendRequest($model, $payload);

        $text = $result[0]['translation_text'] ?? '';

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
