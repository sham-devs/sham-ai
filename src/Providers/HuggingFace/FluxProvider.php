<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Images\Request as ImagesRequest;
use Prism\Prism\Images\Response as ImagesResponse;
use Prism\Prism\ValueObjects\GeneratedImage;
use Prism\Prism\ValueObjects\Usage;
use Prism\Prism\ValueObjects\Meta;

class FluxProvider extends BaseHuggingFaceProvider
{
    /**
     * Flux uses 'parameters' key in the payload.
     */
    protected string $optionsKey = 'parameters';

    public function images(ImagesRequest $request): ImagesResponse
    {
        $runtimeOptions = [];

        // Parse size to width/height
        $size = $this->parseSize($request->size);
        $runtimeOptions['width'] = $request->options['width'] ?? $size['width'];
        $runtimeOptions['height'] = $request->options['height'] ?? $size['height'];

        // Add other options if provided
        if (isset($request->options['num_inference_steps'])) {
            $runtimeOptions['num_inference_steps'] = (int) $request->options['num_inference_steps'];
        }
        if (isset($request->options['guidance_scale'])) {
            $runtimeOptions['guidance_scale'] = (float) $request->options['guidance_scale'];
        }

        $payload = $this->buildPayload($request->prompt, $runtimeOptions);
        $result = $this->sendRawRequest($request->model, $payload);

        $base64 = base64_encode($result);

        return new ImagesResponse(
            images: [
                new GeneratedImage(base64: $base64, mimeType: 'image/png'),
            ],
            usage: new Usage(0, 0),
            meta: new Meta(id: uniqid(), model: $request->model)
        );
    }
}
