# Testing

## Running Tests

The package uses PHPUnit for testing. Tests are located in the `tests/` directory.

### Running All Tests

```bash
cd /path/to/sham-ai
composer install
vendor/bin/phpunit
```

### Running Unit Tests Only

```bash
vendor/bin/phpunit --testsuite Unit
```

### Running Specific Test File

```bash
vendor/bin/phpunit tests/Unit/AIServiceTest.php
```

## Test Categories

| Category | Description | Location |
|----------|-------------|----------|
| Unit | Tests for individual components | `tests/Unit/` |
| Integration | Tests for full workflows | Host application |
| Feature | Tests for API endpoints | Host application |

## Test Structure

```
tests/
├── TestCase.php           # Base test class
└── Unit/
    ├── AIServiceTest.php
    ├── CapabilitiesTest.php
    └── ModelRegistryTest.php
```

## Writing Tests

Tests should follow these patterns:

### Unit Tests

```php
<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\AIService;
use Sham\AI\Models\AIModel;
use Sham\AI\Models\ModelRegistry;

class AIServiceTest extends TestCase
{
    public function test_service_loads_models_from_settings(): void
    {
        $modelsData = [
            [
                'id' => 'test-model',
                'name' => 'Test Model',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
                'config' => ['api_key' => 'test-key'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $models = $service->getModels();
        $this->assertCount(1, $models);
    }
}
```

### Key Testing Patterns

- Use closures to mock the settings resolver
- Create in-memory models with `AIModel` DTO
- Test capability filtering
- Test enable/disable state management
- Test model CRUD operations

## Mocking

The package tests use closures to mock the settings resolver:

```php
$service = new AIService(function ($key, $default) use ($modelsData) {
    return $key === 'sham-ai.models' ? $modelsData : $default;
});
```

For more complex mocking, use Mockery:

```php
$mock = mock(AIService::class);
$mock->shouldReceive('isCapabilityEnabled')
    ->once()
    ->with('translation')
    ->andReturn(true);
```

## Integration Tests in Host Application

Integration tests should be placed in the host application's test directory, not in this package. These tests verify:

- Service resolution through the container
- Settings integration
- Provider adapter resolution
- Encryption/decryption of API keys

Example integration test in host application:

```php
test('ai service can be resolved', function () {
    $service = app(\Sham\AI\AIService::class);
    expect($service)->toBeInstanceOf(\Sham\AI\AIService::class);
});
```
