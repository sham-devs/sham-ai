import { defineConfig } from 'vitepress'
import providers from '../data/providers.json'

// Generate sidebar items from provider data source
const providerSidebarItems = [
    { text: 'Overview', link: '/providers/' },
    ...Object.entries(providers).map(([id, provider]) => ({
        text: provider.sidebarLabel,
        link: `/providers/${id}`
    }))
]

export default defineConfig({
    title: "Sham AI",
    description: "AI Provider Integrations for Sham Packages",
    base: '/sham-ai/',
    srcDir: 'src',
    themeConfig: {
        nav: [
            { text: 'Home', link: '/' },
            {
                text: 'Providers',
                link: '/providers/',
                activeMatch: '/providers/'
            }
        ],
        sidebar: [
            {
                text: 'Providers',
                items: providerSidebarItems
            }
        ]
    }
})
