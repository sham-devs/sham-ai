<?php

declare(strict_types=1);

namespace Sham\AI\Console\Commands;

use Illuminate\Console\Command;
use Sham\AI\AIPackage;
use App\Support\Translation\PackageScanner;

class AIScanCommand extends Command
{
    protected $signature = 'ai:translations:scan
        {--add : Auto-add missing keys}
        {--dry-run : Preview changes}';

    protected $description = 'Scan Sham AI package for missing translations';

    public function handle(): int
    {
        $scanner = new PackageScanner();
        $package = new AIPackage();

        $this->info("Scanning Package: {$package->getPackageName()}");
        $this->line("Namespace: {$package->getTranslationNamespace()}");
        $this->newLine();

        $result = $scanner->scan($package);
        $hasErrors = ! empty($result['invalid']) || ! empty($result['missing']);

        // Report invalid formats
        if (! empty($result['invalid'])) {
            $this->error('❌ INVALID KEY FORMATS DETECTED');
            foreach ($result['invalid'] as $invalid) {
                $this->line("  - Value: <fg=red>\"{$invalid['value']}\"</>");
                $this->line("    Context: {$invalid['context']}");
                $this->line("    File: ".basename($invalid['file']).":{$invalid['line']}");
                $this->line("    Suggestion: {$invalid['suggestion']}");
                $this->newLine();
            }
        }

        // Report missing translations
        if (! empty($result['missing'])) {
            foreach ($result['missing'] as $locale => $missingKeys) {
                $this->error("Missing translations for locale: {$locale}");
                foreach ($missingKeys as $item) {
                    $this->line("  - {$item['key']}");
                }
                $this->newLine();
            }
        }

        // Show stats
        $this->line('Stats:');
        $this->line("  - Total keys found: {$result['stats']['total_found']}");
        $this->line("  - Total invalid: {$result['stats']['total_invalid']}");
        $this->line("  - Locales: ".implode(', ', $result['stats']['locales']));

        if (! $hasErrors) {
            $this->newLine();
            $this->info("✓ All translations are present and formatted correctly.");

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
