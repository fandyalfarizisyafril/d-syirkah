import { test, expect } from '@playwright/test';

test('public pages share the homepage footer design and links', async ({ page }, testInfo) => {
    let homepageAppearance;
    let homepageLinks;
    for (const path of ['/', '/about', '/products', '/products/electric-motors-generators', '/brands', '/brands/wolong', '/solutions', '/contact', '/not-a-page']) {
        const response = await page.goto(path);
        expect(response.status()).toBe(path === '/not-a-page' ? 404 : 200);
        await page.evaluate(() => document.fonts.ready);
        const footer = page.getByRole('contentinfo');
        await expect(footer).toHaveCount(1);
        await footer.scrollIntoViewIfNeeded();
        await expect(footer.locator('.brand-mark .lucide-factory')).toBeVisible();
        const appearance = await footer.evaluate(element => {
            const selectors = ['.footer-grid', '.footer-wordmark', '.footer-wordmark strong', '.brand-mark', 'h2', 'p', 'address', '.footer-label', '.footer-bottom'];
            return [element, ...selectors.map(selector => element.querySelector(selector))].map(node => {
                const style = getComputedStyle(node);
                return [style.width, style.height, style.backgroundColor, style.color, style.fontFamily, style.fontSize, style.fontWeight, style.lineHeight, style.gridTemplateColumns, style.gap, style.padding, style.borderRadius];
            });
        });
        const links = await footer.locator('a').evaluateAll(elements => elements.map(element => ({ text: element.textContent.trim(), href: element.href })));
        if (path === '/') {
            homepageAppearance = appearance;
            homepageLinks = links;
        } else {
            expect(appearance, path).toEqual(homepageAppearance);
            expect(links, path).toEqual(homepageLinks);
        }
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        expect(await footer.locator('h2, p, address, a, .footer-bottom span').evaluateAll(elements => elements.every(element => element.scrollWidth <= element.clientWidth + 1))).toBe(true);
        await expect(footer.locator('a[href^="tel:"]')).toHaveCount(1);
        await expect(footer.locator('a[href^="mailto:"]')).toHaveCount(2);
        if (path === '/about') await footer.screenshot({ path: testInfo.outputPath('shared-footer.png'), style: '.site-header { visibility: hidden; }' });
    }
    const catalog = page.getByRole('contentinfo').getByRole('link', { name: 'Katalog Produk' });
    await catalog.focus();
    await expect(catalog).toBeFocused();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/products$/);
    await page.getByRole('contentinfo').getByRole('link', { name: 'Informasi Legal' }).click();
    await expect(page).toHaveURL(/\/about#legal$/);
    await expect(page.locator('#legal')).toBeVisible();
});
