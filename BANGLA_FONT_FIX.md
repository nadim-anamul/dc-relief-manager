# Bangla Font Fix for PDF Export

## Problem
Bangla fonts were not displaying in the PDF export because DomPDF was not configured to use the correct font directory and font family names.

## Solution Applied

### 1. Updated ExportController.php
Changed the DomPDF configuration to point to the correct font directory:

```php
->setOptions([
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => false,
    'isPhpEnabled' => false,
    'isFontSubsettingEnabled' => false,
    'fontDir' => public_path('fonts/'),      // ← Changed from storage_path
    'fontCache' => storage_path('fonts/'),
    'chroot' => public_path('fonts/'),       // ← Added
    'defaultFont' => 'Noto Sans Bengali',   // ← Added
]);
```

### 2. Updated project-summary-pdf.blade.php
Used the exact font family names that match the TTF files in `public/fonts/`:

```css
body {
    font-family: 'noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e', 
                 'Noto Sans Bengali', 
                 DejaVu Sans, 
                 sans-serif;
}

h1, h2, h3, h4, h5, h6, .bold, strong, b, th {
    font-family: 'noto_sans_bengali_bold_0538636ba885df734f7abc67aa2a017b', 
                 'Noto Sans Bengali', 
                 DejaVu Sans, 
                 sans-serif;
}
```

## Font Files Location

The Bangla fonts are located in: `/public/fonts/`

Available fonts:
- `noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e.ttf` (Normal weight)
- `noto_sans_bengali_bold_0538636ba885df734f7abc67aa2a017b.ttf` (Bold weight)
- `noto_sans_bengali_300_8f24546976b34a4efc4c99ee81df78d6.ttf` (Light weight)
- `noto_sans_bengali_500_233a13ac5533627fa94903e9481c83a7.ttf` (Medium weight)
- `noto_sans_bengali_600_dc359569f67860a7bbe38294e032eec4.ttf` (Semi-bold weight)

## Testing

To test if Bangla fonts are working:

1. Visit `/admin/projects`
2. Click "Export PDF" button
3. Open the downloaded PDF
4. Check if Bangla text is visible:
   - বরাদ্দ সারসংক্ষেপ রিপোর্ট (title)
   - Numbers: ১২৩৪৫৬৭৮৯০
   - Currency: ৳১,২৩৪.৫৬

## How DomPDF Loads Fonts

DomPDF uses the following process:

1. **Font Directory** (`fontDir`): Where TTF files are located
2. **Font Cache** (`fontCache`): Where DomPDF stores font metrics (UFM files)
3. **Font Family Name**: Must match the TTF filename (without extension) OR the name embedded in the font

For our fonts:
- TTF File: `noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e.ttf`
- Font Family in CSS: `'noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e'`
- UFM Cache: `storage/fonts/noto_sans_bengali_normal_c41f24173534e0be2884f9c81dc9de1e.ufm.json`

## Why This Works

1. **Correct Font Path**: DomPDF now looks for fonts in `public/fonts/` where they actually exist
2. **Exact Font Names**: Using the full filename (without extension) as the font-family name
3. **Font Cache**: UFM files in `storage/fonts/` provide font metrics for rendering
4. **Fallback Fonts**: Multiple font-family options ensure text displays even if primary font fails

## Troubleshooting

### If Bangla text still doesn't show:

1. **Verify font files exist:**
   ```bash
   ls -la public/fonts/noto_sans_bengali*.ttf
   ```

2. **Check font permissions:**
   ```bash
   chmod 644 public/fonts/*.ttf
   chmod 644 storage/fonts/*.ufm*
   ```

3. **Clear DomPDF cache:**
   ```bash
   rm storage/fonts/*.ufm.json
   ```
   Then regenerate by visiting the PDF export

4. **Test with simple PDF:**
   Create a test route that generates a minimal PDF with Bangla text to isolate the issue

### If you see squares/boxes instead of Bangla text:

This means the font is not being loaded. Check:
- Font files are in `public/fonts/`
- Font family name in CSS matches TTF filename
- DomPDF configuration points to correct directory

### If you see English instead of Bangla:

Check if:
- Locale is set to 'bn' in the Blade file
- Helper functions (bn_number, localized_attr) are being called
- Database has Bangla content in _bn fields

## Files Modified

1. `app/Http/Controllers/ExportController.php` (lines 232-241)
2. `resources/views/exports/project-summary-pdf.blade.php` (lines 8-23)

## Additional Notes

- DomPDF has limitations compared to browser PDF rendering
- Complex CSS layouts may not work as expected
- Font subsetting is disabled for better Bangla support
- The font cache speeds up subsequent PDF generations

## Success Indicators

✅ PDF title shows: "বরাদ্দ সারসংক্ষেপ রিপোর্ট"  
✅ Numbers show: ১২৩৪৫৬৭৮৯০  
✅ Currency shows: ৳১,২৩৪.৫৬  
✅ Table headers in Bangla: ক্রমিক, প্রকল্পের নাম, etc.  
✅ Status badges in Bangla: সক্রিয়, সম্পন্ন  

If all of these display correctly, the Bangla font is working! 🎉

