<?php

declare(strict_types=1);

namespace Sham\AI\Providers\HuggingFace;

use Prism\Prism\Images\Request as ImagesRequest;
use Prism\Prism\Images\Response as ImagesResponse;
use Prism\Prism\ValueObjects\GeneratedImage;
use Prism\Prism\ValueObjects\Meta;
use Prism\Prism\ValueObjects\Usage;

class SdxlProvider extends BaseHuggingFaceProvider
{
    /**
     * SDXL uses 'parameters' key in the payload.
     */
    protected string $optionsKey = 'parameters';

    public function images(ImagesRequest $request): ImagesResponse
    {
        $model = $request->model();
        $prompt = (string) $request->prompt();

        $payload = $this->buildPayload($prompt, []);

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
