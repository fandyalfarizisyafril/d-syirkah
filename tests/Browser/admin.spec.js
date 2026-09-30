import { test, expect } from '@playwright/test';

test.describe('Admin CMS', () => {
    test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set credentials for an existing local admin.');

    test('admin pages, repeaters, CSRF protection and logout work', async ({ page }, testInfo) => {
        const errors = [];
        page.on('pageerror', (error) => errors.push(error.message));
        await page.goto('/admin');
        await expect(page).toHaveURL(/admin\/login/);
        await page.getByLabel(/^Email/).fill(process.env.CMS_TEST_EMAIL);
        await page.getByLabel(/^Password/).fill(process.env.CMS_TEST_PASSWORD);
        await page.getByRole('button', { name: 'Masuk', exact: true }).click();
        await expect(page).toHaveURL(/\/admin$/);
        for (const path of ['/admin', '/admin/products', '/admin/products/create', '/admin/brands', '/admin/brands/create', '/admin/categories', '/admin/content/company', '/admin/content/focus', '/admin/content/values', '/admin/content/solutions', '/admin/content/legal', '/admin/inquiries', '/admin/account']) {
            const response = await page.goto(path);
            expect(response.status(), path).toBe(200);
            await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
            const geometry = await page.evaluate(() => ({
                width: innerWidth, scrollWidth: document.documentElement.scrollWidth,
                outside: [...document.querySelectorAll('body *')].filter((element) => element.getBoundingClientRect().right > innerWidth + 1).map((element) => `${element.tagName}.${element.className}`).slice(-15),
            }));
            expect(geometry.scrollWidth <= geometry.width, `${path}: ${JSON.stringify(geometry)}`).toBe(true);
            if (['/admin', '/admin/products/create'].includes(path)) {
                await page.screenshot({ path: testInfo.outputPath(path === '/admin' ? 'dashboard.png' : 'product-editor.png'), fullPage: true });
            }
        }
        await page.goto('/admin/products/create');
        await page.getByRole('button', { name: 'Tambah Parameter' }).click();
        await page.getByRole('textbox', { name: 'Parameter', exact: true }).fill('Voltage');
        await page.getByLabel(/^Nilai \/ Satuan/).fill('220 V');
        await expect(page.locator('[data-row]')).toHaveCount(1);
        await page.getByRole('button', { name: 'Tambah Parameter' }).click();
        await page.getByRole('textbox', { name: 'Parameter', exact: true }).last().fill('Power');
        const inputIds = await page.locator('[data-rows] input').evaluateAll((inputs) => inputs.map((input) => input.id));
        expect(new Set(inputIds).size).toBe(inputIds.length);
        await page.getByRole('button', { name: 'Hapus spesifikasi' }).last().click();
        await page.getByRole('button', { name: 'Hapus spesifikasi' }).click();
        await expect(page.locator('[data-row]')).toHaveCount(0);
        await page.goto('/admin/content/values');
        const count = await page.locator('[data-row]').count();
        await page.getByRole('button', { name: 'Tambah Item' }).click();
        await expect(page.locator('[data-row]')).toHaveCount(count + 1);
        await page.getByRole('button', { name: 'Hapus item', exact: true }).last().click();
        await expect(page.locator('[data-row]')).toHaveCount(count);
        const denied = await page.request.post('/admin/products', { form: { name: 'CSRF test' } });
        expect(denied.status()).toBe(419);
        await page.getByRole('button', { name: 'Keluar', exact: true }).click();
        await expect(page).toHaveURL(/admin\/login/);
        await page.goto('/admin/products');
        await expect(page).toHaveURL(/admin\/login/);
        expect(errors).toEqual([]);
    });

    test('published product receives an inquiry that admin can manage', async ({ page }, testInfo) => {
        test.skip(testInfo.project.name !== 'desktop', 'Run the write workflow once.');
        const name = `QA-${Date.now()}`;
        const slug = name.toLowerCase();
        page.on('dialog', (dialog) => dialog.accept());
        await page.goto('/admin/login');
        await page.getByLabel(/^Email/).fill(process.env.CMS_TEST_EMAIL);
        await page.getByLabel(/^Password/).fill(process.env.CMS_TEST_PASSWORD);
        await page.getByRole('button', { name: 'Masuk', exact: true }).click();
        await expect(page).toHaveURL(/\/admin$/);
        try {
            await page.goto('/admin/products/create');
            await page.getByLabel(/^Nama produk/).fill(name);
            await page.getByLabel(/^Slug URL/).fill(slug);
            await page.getByLabel(/^Brand/).selectOption({ index: 1 });
            await page.getByLabel(/^Kategori \/ Bidang/).selectOption({ index: 1 });
            await page.getByLabel(/^Ringkasan/).fill('Browser verification product.');
            await page.getByLabel(/^Deskripsi/).fill('Temporary product for end-to-end verification.');
            await page.getByRole('button', { name: 'Simpan Produk' }).click();
            await expect(page.getByText('Produk berhasil disimpan.')).toBeVisible();
            await page.goto(`/products/${slug}`);
            await expect(page.getByRole('heading', { level: 1 })).toHaveText(name);
            await page.goto(`/contact?product=${slug}`);
            const form = page.locator('#inquiry');
            await form.getByLabel(/^Nama/).fill(name);
            await form.getByLabel(/^Perusahaan/).fill('QA Browser Test');
            await form.getByLabel(/^Email/).fill('qa@example.test');
            await form.getByLabel(/^Nomor telepon/).fill('081234567890');
            await form.getByLabel(/^Kebutuhan/).fill('Automated verification, no sales follow-up.');
            await expect(form.locator('select option:checked')).toContainText(name);
            await form.getByRole('button', { name: 'Kirim Inquiry' }).click();
            await expect(page.getByRole('status')).toContainText('Permintaan Anda sudah diterima.');
            await page.goto(`/admin/inquiries?q=${name}`);
            await page.locator('tbody a').first().click();
            await expect(page.getByText(name, { exact: true })).toBeVisible();
            await page.getByLabel('Status inquiry').selectOption('closed');
            await page.getByLabel('Catatan internal').fill('Verified in browser.');
            await page.getByRole('button', { name: 'Simpan', exact: true }).click();
            await expect(page.getByLabel('Status inquiry')).toHaveValue('closed');
            await expect(page.getByLabel('Catatan internal')).toHaveValue('Verified in browser.');
        } finally {
            await page.goto(`/admin/inquiries?q=${name}`);
            if (await page.locator('tbody a').count()) {
                await page.locator('tbody a').first().click();
                await page.getByRole('button', { name: 'Hapus inquiry', exact: true }).click();
                await expect(page).toHaveURL(/\/admin\/inquiries$/);
            }
            await page.goto(`/admin/products?q=${name}`);
            const remove = page.getByRole('button', { name: `Hapus ${name}`, exact: true });
            if (await remove.count()) {
                await remove.click();
                await expect(page.getByText('Produk berhasil dihapus.')).toBeVisible();
            }
        }
    });
});
