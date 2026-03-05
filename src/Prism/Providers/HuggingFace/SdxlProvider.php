<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers\HuggingFace;

use Prism\Prism\Images\Request as ImagesRequest;
use Prism\Prism\Images\Response as ImagesResponse;
use Prism\Prism\ValueObjects\GeneratedImage;
use Prism\Prism\ValueObjects\Usage;
use Prism\Prism\ValueObjects\Meta;

class SdxlProvider extends BaseHuggingFaceProvider
{
    public function images(ImagesRequest $request): ImagesResponse
    {
        $payload = [
            'inputs' => $request->prompt,
        ];

        $result = $this->sendRawRequest($request->model, $payload);
        
        $base64 = base64_encode($result);

        return new ImagesResponse(
            images: [
                new GeneratedImage(base64: $base64, mimeType: 'image/png')
            ],
            usage: new Usage(0, 0),
            meta: new Meta(id: uniqid(), model: $request->model)
        );
    }
}
