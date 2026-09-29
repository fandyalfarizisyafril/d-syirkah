import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/Browser',
    workers: 1,
    reporter: 'list',
    use: {
        baseURL: process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000',
        channel: process.env.PLAYWRIGHT_CHANNEL || 'chrome',
        screenshot: 'only-on-failure',
    },
    projects: [
        { name: 'mobile-small', use: { viewport: { width: 320, height: 740 } } },
        { name: 'mobile', use: { viewport: { width: 390, height: 844 } } },
        { name: 'tablet', use: { viewport: { width: 768, height: 1024 } } },
        { name: 'desktop', use: { viewport: { width: 1440, height: 1000 } } },
        { name: 'wide-desktop', use: { viewport: { width: 1920, height: 1080 } } },
    ],
});
