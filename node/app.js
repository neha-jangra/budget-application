// const puppeteer = require('puppeteer');
// (async () => {
//     const browser = await puppeteer.launch();
//     const page = await browser.newPage();
//     await page.goto(process.argv[2]);
//     await page.screenshot({ path: process.argv[3] });
//     await browser.close();
// })();
import express from 'express';
import puppeteer from 'puppeteer';

const app = express();

app.get('/screenshot', async (req, res) => {
    try {
        // Launch Puppeteer
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        
        // Generate HTML content (replace this with your HTML)
        const htmlContent = `
            <html>
                <head>
                    <title>HTML to Image</title>
                </head>
                <body>
                    <h1>Hello, World!</h1>
                    <p>This is a sample HTML content.</p>
                </body>
            </html>
        `;
        
        // Set the HTML content of the page
        await page.setContent(htmlContent);
        
        // Capture screenshot
        const screenshot = await page.screenshot({ encoding: 'binary' });
        
        // Send the image file as a response
        res.set('Content-Type', 'image/png');
        res.send(screenshot);
        
        // Close Puppeteer browser
        await browser.close();
    } catch (error) {
        console.error('Error:', error);
        res.status(500).send('Internal Server Error');
    }
});
app.get('/pdf', async (req, res) => {
    try {
        // Launch Puppeteer
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        
        // Generate HTML content (replace this with your HTML)
        const htmlContent = `
            <html>
                <head>
                    <title>HTML to Image</title>
                </head>
                <body>
                    <h1>Hello, World!</h1>
                    <p>This is a sample HTML content.</p>
                </body>
            </html>
        `;
        
        // Set the HTML content of the page
        await page.setContent(htmlContent);
        
        // Capture screenshot
        const screenshot = await page.screenshot({ encoding: 'binary' });
        
        // Generate PDF
        const pdfBuffer = await page.pdf({ format: 'A4' });
        
        // Send the PDF file as a response
        res.set('Content-Type', 'application/pdf');
        res.send(pdfBuffer);
        
        // Close Puppeteer browser
        await browser.close();
    } catch (error) {
        console.error('Error:', error);
        res.status(500).send('Internal Server Error');
    }
});

// Start the Express server
const port = 3001;
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});

