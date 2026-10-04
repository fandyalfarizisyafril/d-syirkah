import { test, expect } from '@playwright/test';

test('public pages share the homepage header and accessible navigation', async ({ page }, testInfo) => {
    let homepageStyle;
    for (const [path, active] of [
        ['/', '/'], ['/about', '/about'], ['/products', '/products'],
        ['/products/electric-motors-generators', '/products'], ['/brands', '/brands'],
        ['/brands/wolong', '/brands'], ['/solutions', '/solutions'], ['/contact', '/contact'],
        ['/not-a-page', null],
    ]) {
        const response = await page.goto(path);
        expect(response.status()).toBe(active === null ? 404 : 200);
        await page.evaluate(() => document.fonts.ready);
        const header = page.locator('.site-header');
        await expect(header.locator('.brand-mark .lucide-factory')).toBeVisible();
        await expect(header.getByRole('link', { name: 'Hubungi Kami', exact: true })).toBeVisible();
        await expect(page.locator('.utility-bar, .header-cta')).toHaveCount(0);
        await expect(page.locator('.site-header + .ruler-divider')).toHaveCount(1);
        await expect(page.locator('#main > .ruler-divider:first-child')).toHaveCount(0);
        const appearance = await header.evaluate(element => {
            return ['.header-inner', '.wordmark', '.wordmark strong', '.brand-mark', '.main-nav', '.round-contact'].map(selector => {
                const node = element.querySelector(selector);
                const css = getComputedStyle(node);
                return [css.width, css.height, css.color, css.backgroundColor, css.fontFamily, css.fontSize, css.fontWeight, css.borderRadius, css.gap];
            });
        });
        if (path === '/') homepageStyle = appearance;
        else expect(appearance, path).toEqual(homepageStyle);
        const current = header.locator('[aria-current="page"]');
        await expect(current).toHaveCount(active === null ? 0 : 1);
        if (active !== null) expect(new URL(await current.getAttribute('href')).pathname).toBe(active);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        const toggle = header.locator('.menu-toggle');
        if (await toggle.isVisible()) {
            await toggle.click();
            await expect(header.getByRole('navigation')).toBeVisible();
            await expect(toggle).toHaveAttribute('aria-expanded', 'true');
            await page.keyboard.press('Escape');
            await expect(toggle).toBeFocused();
            await expect(header.getByRole('navigation')).toBeHidden();
        }
        if (path === '/about') await header.screenshot({ path: testInfo.outputPath('shared-header.png') });
    }
    await page.locator('.site-header').getByRole('link', { name: 'Hubungi Kami', exact: true }).click();
    await expect(page).toHaveURL(/\/contact$/);
    await page.emulateMedia({ reducedMotion: 'reduce' });
    expect(await page.locator('.round-contact-text').evaluate(element => getComputedStyle(element).animationName)).toBe('none');
});
