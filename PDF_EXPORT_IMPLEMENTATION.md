# PDF Export Implementation for Projects

## Overview
The PDF export functionality for the projects page has been successfully updated to include:
- ত্রান কর্মসূচিসমূহ (Relief Programs) summary card
- বরাদ্দ (Allocations) table with all data
- Full Bangla language support
- Filter support (status, economic_year_id, relief_type_id)

## Changes Made

### 1. ExportController.php
**Path:** `app/Http/Controllers/ExportController.php`

**Changes:**
- Added `status` filter to both Excel and PDF export methods
- Updated query filters to match ProjectController logic
- Added default to current economic year if no year is specified
- Updated status filter to use Project model scopes (active, completed, upcoming)
- Updated relief type statistics calculation to include status filter

**Methods Modified:**
- `exportProjectSummaryExcel()` - Lines 101-110
- `exportProjectSummaryPdf()` - Lines 115-228

### 2. ProjectSummaryExport.php
**Path:** `app/Exports/ProjectSummaryExport.php`

**Changes:**
- Added `status` filter handling in collection method
- Added default to current economic year if not specified
- Updated query to match ProjectController logic
- Fixed date filtering to use whereHas on economicYear

**Methods Modified:**
- `collection()` - Lines 30-77

### 3. project-summary-pdf.blade.php
**Path:** `resources/views/exports/project-summary-pdf.blade.php`

**Complete Rewrite:**
- Changed document language to Bangla (lang="bn")
- Set locale to 'bn' at start of body
- Translated all headers and labels to Bangla
- Updated title to "বরাদ্দ সারসংক্ষেপ রিপোর্ট"
- Used Bangla helper functions (bn_number, bn_date, localized_attr)
- Improved styling for better PDF rendering

**Key Sections:**
1. **Header:** Shows report title, generation date, and system name in Bangla
2. **Summary Card (প্রকল্প সারসংক্ষেপ):** Shows total, active, and completed projects with total budget
3. **Relief Programs Card (ত্রান কর্মসূচিসমূহ):** Shows allocation by relief type with project counts
4. **Projects Table (বরাদ্দ টেবিল):** Shows all projects with columns:
   - ক্রমিক (Serial)
   - প্রকল্পের নাম (Project Name)
   - অর্থবছর (Economic Year)
   - ত্রাণের ধরন (Relief Type)
   - বরাদ্দকৃত (Allocated)
   - অবশিষ্ট (Available)
   - ব্যবহৃত (Used)
   - অবস্থা (Status)
   - মন্তব্য (Remarks)

## Features Implemented

### ✅ Filter Support
- **Status:** Active (সক্রিয়), Completed (সম্পন্ন), Upcoming (আসন্ন)
- **Economic Year:** Defaults to current year if not specified
- **Relief Type:** Filters by specific relief type

### ✅ Bangla Language Support
- All text translated to Bangla
- Numbers converted to Bangla numerals using `bn_number()`
- Dates formatted in Bangla using `bn_date()`
- Model attributes localized using `localized_attr()`

### ✅ Data Completeness
- Shows **all** projects (not paginated)
- Includes summary statistics
- Shows relief type allocations
- Displays formatted amounts with proper units

### ✅ PDF Styling
- Clean, professional layout
- Proper font support for Bangla (Noto Sans Bengali)
- Color-coded status badges
- Responsive table layout
- Border and styling for better readability

## Usage

### From the Web Interface
1. Navigate to `/admin/projects`
2. Apply desired filters (status, economic year, relief type)
3. Click the "Export PDF" button (red button in the header)
4. PDF will be downloaded with all filtered data in Bangla

### Direct URL
```
GET /admin/exports/project-summary/pdf?status={status}&economic_year_id={year_id}&relief_type_id={type_id}
```

**Parameters:**
- `status`: Optional - 'active', 'completed', or 'upcoming'
- `economic_year_id`: Optional - ID of economic year (defaults to current)
- `relief_type_id`: Optional - ID of relief type
- `start_date`: Optional - Start date filter
- `end_date`: Optional - End date filter

## Example PDF Output

### Header
```
বরাদ্দ সারসংক্ষেপ রিপোর্ট
তৈরির তারিখ: ১৭ অক্টোবর ২০২৫ ০৯:৩০ সকাল
ডিসি ত্রাণ ব্যবস্থাপনা সিস্টেম
```

### Summary Section
```
প্রকল্প সারসংক্ষেপ
মোট বরাদ্দ: ১৫
সক্রিয় বরাদ্দ: ১০
সম্পন্ন বরাদ্দ: ৫
মোট বাজেট: ৳১,২৩৪,৫৬৭.৮৯
```

### Relief Programs Section
```
ত্রান কর্মসূচিসমূহ
[Grid of relief types with amounts and project counts]
```

### Projects Table
```
ক্রমিক | প্রকল্পের নাম | অর্থবছর | ত্রাণের ধরন | বরাদ্দকৃত | অবশিষ্ট | ব্যবহৃত | অবস্থা | মন্তব্য
```

## Testing

To test the PDF export:

1. **With Filters:**
   ```bash
   curl "http://localhost/admin/exports/project-summary/pdf?status=active" -o test-active.pdf
   ```

2. **With Economic Year:**
   ```bash
   curl "http://localhost/admin/exports/project-summary/pdf?economic_year_id=1" -o test-year.pdf
   ```

3. **With Multiple Filters:**
   ```bash
   curl "http://localhost/admin/exports/project-summary/pdf?status=active&relief_type_id=2" -o test-filtered.pdf
   ```

## Dependencies

The implementation uses:
- Laravel DomPDF (`barryvdh/laravel-dompdf`)
- Noto Sans Bengali font (for Bangla rendering)
- Laravel Excel (`maatwebsite/excel`) for Excel exports
- Custom Bangla helper functions from `app/Support/helpers.php`

## Font Support

The PDF uses the following fonts for Bangla support:
- Noto Sans Bengali (primary)
- SolaimanLipi (fallback)
- Nikosh (fallback)

Font files should be located in `storage/fonts/`.

## Permissions

The export functionality requires the `exports.access` permission to be accessed.

## Notes

- The PDF generation uses DomPDF which has some CSS limitations
- Large datasets may take longer to generate
- The PDF is set to A4 portrait orientation
- All amounts are formatted with 2 decimal places
- Currency symbol (৳) is automatically added for money types

## Future Enhancements

Potential improvements that could be made:
1. Add page numbers and total pages
2. Include filter information in PDF header
3. Add charts/graphs for visual representation
4. Support for landscape orientation for wider tables
5. Customizable columns selection
6. Export date range in filename

## Troubleshooting

### PDF Not Generating
- Check if DomPDF is installed: `composer show barryvdh/laravel-dompdf`
- Verify font files exist in `storage/fonts/`
- Check PHP memory limit for large datasets

### Bangla Text Not Showing
- Ensure Bangla fonts are properly installed
- Check font paths in DomPDF configuration
- Verify locale is set to 'bn'

### Filters Not Working
- Check if query parameters are being passed correctly
- Verify Project model scopes (active, completed, upcoming) exist
- Check economic year relationships

## Related Files

- Controller: `app/Http/Controllers/ExportController.php`
- Export Class: `app/Exports/ProjectSummaryExport.php`
- PDF View: `resources/views/exports/project-summary-pdf.blade.php`
- Model: `app/Models/Project.php`
- Routes: `routes/web.php` (lines 117-129)
- Index View: `resources/views/admin/projects/index.blade.php` (lines 18-31)

