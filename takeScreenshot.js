const puppeteer = require('puppeteer');
(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(process.argv[2]);
    await page.screenshot({ path: process.argv[3] });
    await browser.close();
})();
