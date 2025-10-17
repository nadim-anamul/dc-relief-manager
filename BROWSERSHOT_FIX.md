# Browsershot PDF Fix for Bangla Text

## Problem
The Bangla fonts in the PDF were rendering as broken/disconnected characters because DomPDF doesn't support complex script shaping and ligatures required for Bangla text.

## Solution
Switched from **DomPDF** to **Browsershot** (which uses Chrome/Puppeteer) for PDF generation. Browsershot renders HTML like a real browser, providing perfect support for complex Bangla scripts.

## What Changed

### 1. Installed Browsershot
```bash
composer require spatie/browsershot
```

### 2. Updated ExportController.php

**Before (DomPDF):**
```php
$pdf = Pdf::loadView('exports.project-summary-pdf', [...])
    ->setPaper('a4', 'portrait')
    ->setOptions([...]);
return $pdf->download($fileName);
```

**After (Browsershot):**
```php
$html = view('exports.project-summary-pdf', [...])->render();
$tempPath = storage_path('app/temp/' . $fileName);

Browsershot::html($html)
    ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
    ->format('A4')
    ->margins(15, 15, 15, 15)
    ->showBackground()
    ->waitUntilNetworkIdle()
    ->save($tempPath);

return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
```

### 3. Updated project-summary-pdf.blade.php

**Before:**
```css
body {
    font-family: 'noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e', ...;
}
```

**After:**
```html
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, sans-serif;
}
</style>
```

## Why Browsershot is Better

### ✅ Advantages:
1. **Perfect Bangla Rendering**: Proper ligatures, conjuncts, and character shaping
2. **Modern CSS Support**: Full CSS3, Flexbox, Grid support
3. **Web Fonts**: Can use Google Fonts or any web fonts
4. **Better Layout**: Renders exactly like in browser
5. **More Reliable**: Uses Chrome's rendering engine

### ⚠️ Requirements:
1. **Node.js** must be installed (✓ Already installed: v18.17.0)
2. **Puppeteer** must be installed (✓ Already installed)
3. Requires more memory than DomPDF
4. Slightly slower generation time

## Testing

To test the new PDF export:

1. Visit `/admin/projects`
2. Apply any filters
3. Click **"Export PDF"** button
4. Open the downloaded PDF

### What You Should See:
✅ **বরাদ্দ সারসংক্ষেপ রিপোর্ট** - Perfect Bangla text  
✅ **১২৩৪৫৬৭৮৯০** - Bangla numbers  
✅ **৳১,২৩৪.৫৬** - Currency formatting  
✅ **সক্রিয়, সম্পন্ন** - Status badges  
✅ All characters properly connected and shaped  

## Browsershot Configuration

### Current Settings:
```php
Browsershot::html($html)
    ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox']) // Required for Docker/some servers
    ->format('A4')                    // Paper size
    ->margins(15, 15, 15, 15)        // Top, Right, Bottom, Left (in mm)
    ->showBackground()               // Include background colors/images
    ->waitUntilNetworkIdle()        // Wait for fonts/resources to load
    ->save($tempPath);
```

### Additional Options (if needed):
```php
->landscape()                        // Landscape orientation
->paperSize(297, 420)               // Custom size in mm
->scale(0.9)                        // Scale content (0.1 to 2)
->timeout(60)                       // Timeout in seconds
->setNodeBinary('/path/to/node')   // Custom Node.js path
->setNpmBinary('/path/to/npm')     // Custom NPM path
```

## Troubleshooting

### If PDF generation fails:

#### 1. Check Node.js and Puppeteer:
```bash
node --version                # Should show v18.17.0 or higher
npm list puppeteer           # Should show puppeteer@24.x.x
```

#### 2. Install Puppeteer if missing:
```bash
npm install puppeteer
```

#### 3. Check Chromium installation:
```bash
node -e "console.log(require('puppeteer').executablePath())"
```

#### 4. Manual Chromium install (if needed):
```bash
npx puppeteer install
```

#### 5. Test Browsershot directly:
```bash
php artisan tinker
>>> Browsershot::html('<h1>Test বাংলা</h1>')->save(storage_path('test.pdf'));
```

### Common Errors:

**Error: "Failed to launch the browser process"**
- Solution: Add `--no-sandbox` and `--disable-setuid-sandbox` options (already added)

**Error: "Chromium not found"**
- Solution: Run `npx puppeteer install`

**Error: "Timeout exceeded"**
- Solution: Increase timeout: `->timeout(120)`

**Error: "Cannot find module 'puppeteer'"**
- Solution: Run `npm install puppeteer`

## Performance Comparison

| Feature | DomPDF | Browsershot |
|---------|--------|-------------|
| Bangla Text | ❌ Broken | ✅ Perfect |
| Speed | Fast (~1s) | Slower (~3-5s) |
| Memory | Low | Higher |
| CSS Support | Limited | Full |
| Web Fonts | ❌ No | ✅ Yes |
| Complex Layouts | ❌ Limited | ✅ Full |

## File Size Comparison

- **DomPDF**: Smaller files (~50-100KB)
- **Browsershot**: Larger files (~200-500KB)

The larger file size is worth it for perfect Bangla rendering!

## Files Modified

1. `composer.json` - Added `spatie/browsershot` dependency
2. `app/Http/Controllers/ExportController.php` - Changed PDF generation method
3. `resources/views/exports/project-summary-pdf.blade.php` - Updated to use Google Fonts

## Production Deployment

### For Docker:
Add to Dockerfile:
```dockerfile
# Install Node.js and Puppeteer dependencies
RUN apt-get update && apt-get install -y \
    nodejs npm \
    chromium \
    && npm install -g puppeteer
```

### For Ubuntu/Debian Server:
```bash
# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Puppeteer
cd /path/to/project
npm install puppeteer

# Install Chromium dependencies
sudo apt-get install -y \
    chromium-browser \
    ca-certificates \
    fonts-liberation \
    libappindicator3-1 \
    libasound2 \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libc6 \
    libcairo2 \
    libcups2 \
    libdbus-1-3 \
    libexpat1 \
    libfontconfig1 \
    libgbm1 \
    libgcc1 \
    libglib2.0-0 \
    libgtk-3-0 \
    libnspr4 \
    libnss3 \
    libpango-1.0-0 \
    libpangocairo-1.0-0 \
    libstdc++6 \
    libx11-6 \
    libx11-xcb1 \
    libxcb1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxext6 \
    libxfixes3 \
    libxi6 \
    libxrandr2 \
    libxrender1 \
    libxss1 \
    libxtst6 \
    lsb-release \
    wget \
    xdg-utils
```

### For macOS (current):
✅ Already working! Node.js and Puppeteer are installed.

## Success Indicators

After the fix, your PDF should have:

✅ **Properly shaped Bangla text** - Characters connect correctly  
✅ **Clear ligatures** - ত্র, ক্ষ, etc. display as single units  
✅ **Correct conjuncts** - Complex character combinations render properly  
✅ **Beautiful typography** - Professional-looking Bangla text  
✅ **Perfect numbers** - ১২৩৪৫৬৭৮৯০ render clearly  

## Reverting to DomPDF (if needed)

If you need to revert to DomPDF:

1. Restore the original code in `ExportController.php`:
```php
$pdf = Pdf::loadView('exports.project-summary-pdf', [...])
    ->setPaper('a4', 'portrait')
    ->setOptions([...]);
return $pdf->download($fileName);
```

2. Remove Google Fonts from `project-summary-pdf.blade.php`
3. Use local font files instead

## Future Improvements

Potential enhancements:
1. Add PDF generation queue for large datasets
2. Cache generated PDFs for same filter combinations
3. Add "Download as Image" option using Browsershot->screenshot()
4. Implement PDF templates with more styling options
5. Add watermark support
6. Multi-page layout optimization

---

**Status**: ✅ Complete and Working  
**Tested**: ✅ macOS with Node.js v18.17.0 and Puppeteer 24.25.0  
**Bangla Support**: ✅ Perfect rendering with proper ligatures  

