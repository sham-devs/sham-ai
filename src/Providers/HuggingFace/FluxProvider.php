<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Images\Request as ImagesRequest;
use Prism\Prism\Images\Response as ImagesResponse;
use Prism\Prism\ValueObjects\GeneratedImage;
use Prism\Prism\ValueObjects\Meta;
use Prism\Prism\ValueObjects\Usage;

class FluxProvider extends BaseHuggingFaceProvider
{
    /**
     * Flux uses 'parameters' key in the payload.
     */
    protected string $optionsKey = 'parameters';

    public function images(ImagesRequest $request): ImagesResponse
    {
        $runtimeOptions = [];
        $model = $request->model();
        $prompt = (string) $request->prompt();
        $options = $request->providerOptions();

        // Parse size to width/height. Prism exposes image dimensions via the
        // `size` provider option (e.g. '1024x1024'), consistent with core
        // image providers; explicit width/height options take precedence.
        $size = $this->parseSize((string) ($options['size'] ?? '1024x1024'));
        $runtimeOptions['width'] = $options['width'] ?? $size['width'];
        $runtimeOptions['height'] = $options['height'] ?? $size['height'];

        // Add other options if provided
        if (isset($options['num_inference_steps'])) {
            $runtimeOptions['num_inference_steps'] = (int) $options['num_inference_steps'];
        }
        if (isset($options['guidance_scale'])) {
            $runtimeOptions['guidance_scale'] = (float) $options['guidance_scale'];
        }

        $payload = $this->buildPayload($prompt, $runtimeOptions);
        $result = $this->sendRawRequest($model, $payload);

        $base64 = base64_encode($result);

        return new ImagesResponse(
            images: [
                new GeneratedImage(base64: $base64, mimeType: 'image/png'),
            ],
            usage: new Usage(0, 0),
            meta: new Meta(id: uniqid(), model: $model)
        );
    }
}
