import { defineConfig } from 'vitepress'

export default defineConfig({
    title: "Sham AI",
    description: "AI Provider Integrations for Sham Packages",
    base: '/sham-ai/',
    srcDir: 'src',
    themeConfig: {
        nav: [
            { text: 'Home', link: '/' },
            { text: 'Providers', link: '/providers/' }
        ],
        sidebar: [
            {
                text: 'Providers',
                items: [
                    { text: 'OpenAI', link: '/providers/openai' },
                    { text: 'Anthropic', link: '/providers/anthropic' },
                    { text: 'Google', link: '/providers/google' },
                    { text: 'xAI', link: '/providers/xai' },
                    { text: 'Mistral', link: '/providers/mistral' },
                    { text: 'Zhipu', link: '/providers/zhipu' },
                    { text: 'Ollama', link: '/providers/ollama' },
                    { text: 'DeepSeek', link: '/providers/deepseek' },
                    { text: 'NLLB (ترجمة)', link: '/providers/huggingface-nllb' },
                    { text: 'Opus-MT (ترجمة)', link: '/providers/huggingface-opus-mt' },
                    { text: 'Llama (نصوص)', link: '/providers/huggingface-llama' },
                    { text: 'Qwen (نصوص)', link: '/providers/huggingface-qwen' },
                    { text: 'Flux (صور)', link: '/providers/huggingface-flux' },
                    { text: 'Stable Diffusion (صور)', link: '/providers/huggingface-sd' },
                    { text: 'SDXL (صور)', link: '/providers/huggingface-sdxl' },
                ]
            }
        ]
    }
})
