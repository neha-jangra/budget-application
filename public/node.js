import puppeteer from 'puppeteer';

const url = process.argv[2] || "http://budget-app.test/reports";
const divTarget = '.main-container'; // Replace with the CSS selector of your div
const screenshotPath = process.argv[4] || "public/screenshots/screenshot.png";
const browserWidth = 1024;
const browserHeight = 768;
const timeout = 30000; // 30 seconds

(async () => {
    try {
        const browser = await puppeteer.launch({
            executablePath: "/opt/homebrew/bin/chromium",
            args: ['--no-sandbox'],
            dumpio: true // Enable debug mode
        });

        const page = await browser.newPage();
        
        // Set viewport and browser dimensions
        await page.setViewport({
            width: browserWidth,
            height: browserHeight
        });

        const navigationPromise = page.waitForNavigation({ waitUntil: "networkidle0" });

        await page.goto(url, { waitUntil: "networkidle0" });

        // Wait for the div to appear on the page
        await page.waitForSelector(divTarget, { timeout });

        // Get the bounding box of the div
        const divBoundingBox = await page.$eval(divTarget, element => {
            const { x, y, width, height } = element.getBoundingClientRect();
            return { x, y, width, height };
        });

        console.log(divBoundingBox);

        // Adjust the viewport size to fit the div
        await page.setViewport({
            width: Math.ceil(divBoundingBox.width) + browserWidth,
            height: Math.ceil(divBoundingBox.height) + browserHeight,
        });

        // Scroll to the div's position
        await page.evaluate(boundingBox => {
            window.scrollTo(boundingBox.x, boundingBox.y);
        }, divBoundingBox);

        // Generate screenshot of the div
        await page.screenshot({ path: screenshotPath, clip: divBoundingBox });

        console.log("Screenshot saved successfully.");

        await browser.close();
    } catch (error) {
        console.error("Error:", error);
    }
})();
