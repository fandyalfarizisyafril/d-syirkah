import { test, expect } from '@playwright/test';

test('public pages render without overflow, broken images, or JavaScript errors', async ({ page }, testInfo) => {
    const errors = [];
    page.on('pageerror', (error) => errors.push(error.message));
    page.on('console', (message) => {
        if (message.type() === 'error') errors.push(message.text());
    });

    for (const path of ['/', '/about', '/products', '/products/electric-motors-generators', '/brands', '/brands/wolong', '/solutions', '/contact']) {
        const response = await page.goto(path);
        expect(response.status()).toBe(200);
        await page.evaluate(() => document.fonts.ready);
        await expect(page.locator('h1')).toHaveCount(1);
        await expect(page.locator('.site-footer')).toBeVisible();
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        for (const picture of await page.locator('img').all()) {
            await picture.scrollIntoViewIfNeeded();
            await expect.poll(() => picture.evaluate((image) => image.complete && image.naturalWidth > 0)).toBe(true);
        }
        await page.evaluate(() => window.scrollTo({ top: 0, behavior: 'instant' }));
        await expect(page.locator('svg.lucide').first()).toBeVisible();
        const overflowingText = await page.locator('h1:not(.sr-only), h2:not(.sr-only), h3:not(.sr-only), .button, .wordmark, input, select').evaluateAll((elements) =>
            elements.filter((element) => element.clientWidth > 0 && element.scrollWidth > element.clientWidth + 1).map((element) => element.textContent.trim())
        );
        expect(overflowingText).toEqual([]);
        if (['/', '/products', '/contact'].includes(path)) {
            await page.screenshot({ path: testInfo.outputPath(path === '/' ? 'home.png' : `${path.slice(1)}.png`), fullPage: true });
            if (path === '/') await page.screenshot({ path: testInfo.outputPath('home-viewport.png') });
        }
    }
    expect(errors).toEqual([]);
});

test('homepage values carousel keeps its size and supports keyboard navigation', async ({ page }) => {
    await page.goto('/');
    const carousel = page.locator('[data-values-carousel]');
    await carousel.scrollIntoViewIfNeeded();
    await page.evaluate(() => document.fonts.ready);
    const initialHeight = await carousel.evaluate((element) => element.getBoundingClientRect().height);
    const next = page.getByRole('button', { name: 'Nilai perusahaan berikutnya' });
    const previous = page.getByRole('button', { name: 'Nilai perusahaan sebelumnya' });
    await expect(carousel.getByRole('heading', { name: 'Fokus Kami' })).toBeVisible();
    await next.focus();
    await page.keyboard.press('Enter');
    await expect(carousel.getByRole('heading', { name: 'Reliability' })).toBeVisible();
    expect(await carousel.evaluate((element) => element.getBoundingClientRect().height)).toBe(initialHeight);
    await previous.click();
    await expect(carousel.getByRole('heading', { name: 'Fokus Kami' })).toBeVisible();
    await previous.click();
    await expect(carousel.getByRole('heading', { name: 'Environmental Protection' })).toBeVisible();
    await page.emulateMedia({ reducedMotion: 'reduce' });
    expect(await page.locator('.round-contact-text').evaluate((element) => getComputedStyle(element).animationName)).toBe('none');
});

test('catalog search, filters, reset and inquiry preserve the selected product', async ({ page }) => {
    await page.goto('/products');
    await page.getByLabel('Cari produk atau brand').fill('Wolong');
    await page.getByRole('button', { name: 'Cari', exact: true }).click();
    await expect(page.locator('.product-card')).toHaveCount(1);
    await page.getByLabel('Bidang produk').selectOption('Mechanical');
    await page.getByRole('button', { name: 'Cari', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Produk tidak ditemukan' })).toBeVisible();
    await page.getByRole('link', { name: 'Reset', exact: true }).click();
    await expect(page.locator('.product-card')).toHaveCount(7);
    await page.getByRole('link', { name: 'Detail Chemical Metering Pump', exact: true }).click();
    await page.getByRole('link', { name: 'Minta Penawaran' }).click();
    await expect(page).toHaveURL(/contact\?product=chemical-metering-pump/);
    const mailto = await page.getByRole('link', { name: 'Kirim Permintaan via Email' }).getAttribute('href');
    expect(decodeURIComponent(mailto)).toContain('Chemical Metering Pump - Qdos');
});

test('navigation is usable on mobile and custom 404 offers recovery', async ({ page }) => {
    await page.goto('/');
    const toggle = page.getByRole('button', { name: 'Buka navigasi' });
    if (await toggle.isVisible()) {
        await toggle.click();
        await expect(page.locator('#main-navigation')).toBeVisible();
        await page.keyboard.press('Escape');
        await expect(toggle).toBeFocused();
        await expect(page.locator('#main-navigation')).toBeHidden();
        await toggle.click();
    }
    await page.getByRole('navigation', { name: 'Navigasi utama' }).getByRole('link', { name: 'Produk', exact: true }).click();
    await expect(page).toHaveURL(/\/products$/);
    const response = await page.goto('/products/not-found');
    expect(response.status()).toBe(404);
    await expect(page.getByRole('heading', { name: 'Halaman ini tidak tersedia.' })).toBeVisible();
    await page.getByRole('main').getByRole('link', { name: 'Katalog Produk' }).click();
    await expect(page.locator('.product-card')).toHaveCount(7);
});
