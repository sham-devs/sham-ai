<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\Images\Request as ImagesRequest;
use Prism\Prism\Text\Request as TextRequest;
use Sham\AI\Providers\HuggingFace\FluxProvider;
use Sham\AI\Providers\HuggingFace\LlamaProvider;

function makeTextRequest(string $prompt, array $providerOptions = []): TextRequest
{
    return new TextRequest(
        model: 'meta-llama/Llama-3.2-3B-Instruct',
        providerKey: 'huggingface-llama',
        systemPrompts: [],
        prompt: $prompt,
        messages: [],
        maxSteps: 1,
        maxTokens: null,
        temperature: null,
        topP: null,
        tools: [],
        clientOptions: [],
        clientRetry: [0],
        toolChoice: null,
        providerOptions: $providerOptions,
    );
}

function makeImagesRequest(string $prompt, array $providerOptions = []): ImagesRequest
{
    return new ImagesRequest(
        model: 'black-forest-labs/FLUX.1-dev',
        providerKey: 'huggingface-flux',
        systemPrompts: [],
        prompt: $prompt,
        clientOptions: [],
        clientRetry: [0],
        additionalContent: [],
        providerOptions: $providerOptions,
    );
}

it('runs the Llama text provider end-to-end through a faked HuggingFace request', function (): void {
    Http::fake([
        'api-inference.huggingface.co/*' => Http::response([
            ['generated_text' => 'Hello from Llama'],
        ], 200),
    ]);

    $provider = new LlamaProvider(['api_key' => 'test-key']);
    $request = makeTextRequest('Say hello', [
        'max_new_tokens' => 64,
        'temperature' => 0.5,
        'do_sample' => true,
    ]);

    $response = $provider->text($request);

    Http::assertSent(function ($httpRequest): bool {
        $payload = $httpRequest->data();

        return $httpRequest->hasHeader('Authorization', 'Bearer test-key')
            && $payload['inputs'] === 'Say hello'
            && $payload['parameters']['max_new_tokens'] === 64
            && $payload['parameters']['temperature'] === 0.5
            && $payload['parameters']['do_sample'] === true;
    });

    expect($response->text)->toBe('Hello from Llama')
        ->and($response->finishReason)->toBe(FinishReason::Stop)
        ->and($response->meta->model)->toBe('meta-llama/Llama-3.2-3B-Instruct');
});

it('runs the Flux image provider end-to-end through a faked HuggingFace request', function (): void {
    $pngBytes = 'raw-image-bytes';

    Http::fake([
        'api-inference.huggingface.co/*' => Http::response($pngBytes, 200, ['Content-Type' => 'image/png']),
    ]);

    $provider = new FluxProvider(['api_key' => 'test-key']);
    $request = makeImagesRequest('A red fox', [
        'size' => '768x512',
        'num_inference_steps' => 28,
    ]);

    $response = $provider->images($request);

    Http::assertSent(function ($httpRequest): bool {
        $payload = $httpRequest->data();

        return $payload['inputs'] === 'A red fox'
            && $payload['parameters']['width'] === 768
            && $payload['parameters']['height'] === 512
            && $payload['parameters']['num_inference_steps'] === 28;
    });

    expect($response->images)->toHaveCount(1)
        ->and($response->images[0]->base64)->toBe(base64_encode($pngBytes))
        ->and($response->images[0]->mimeType)->toBe('image/png')
        ->and($response->meta->model)->toBe('black-forest-labs/FLUX.1-dev');
});

it('lets explicit width/height options override the parsed size', function (): void {
    Http::fake([
        'api-inference.huggingface.co/*' => Http::response('img', 200),
    ]);

    $provider = new FluxProvider(['api_key' => 'test-key']);
    $request = makeImagesRequest('prompt', [
        'size' => '1024x1024',
        'width' => 640,
        'height' => 480,
    ]);

    $provider->images($request);

    Http::assertSent(function ($httpRequest): bool {
        $payload = $httpRequest->data();

        return $payload['parameters']['width'] === 640
            && $payload['parameters']['height'] === 480;
    });
});
