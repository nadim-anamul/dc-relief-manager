# Component Quick Reference Guide

## 🎯 New Reusable Components

### Page Header
```blade
<x-page-header 
    title="User Management"
    description="Manage system users"
    icon-color="blue">
    <x-slot name="icon">
        <svg class="w-6 h-6"><!-- icon --></svg>
    </x-slot>
    <x-slot name="actions">
        <button>Add New</button>
    </x-slot>
</x-page-header>
```

### Stats Card
```blade
<x-stats-card 
    title="Total Users"
    value="125"
    description="Active accounts"
    icon-color="blue">
    <x-slot name="icon">
        <svg><!-- icon --></svg>
    </x-slot>
</x-stats-card>
```

### Section Card
```blade
<x-section-card title="Section Title" subtitle="Description">
    <x-slot name="actions">
        <button>Action</button>
    </x-slot>
    <!-- Content here -->
</x-section-card>
```

### Table Container
```blade
<x-table-container 
    title="Users" 
    :totalCount="$users->total()"
    currentRange="Showing 1-10 of 50">
    <thead>
        <tr>
            <th class="table-header">Name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="table-cell">John</td>
        </tr>
    </tbody>
</x-table-container>
```

### Filter Section
```blade
<x-filter-section 
    action="{{ route('users.index') }}"
    :hasFilters="request()->hasAny(['search', 'status'])"
    resetUrl="{{ route('users.index') }}">
    
    <div>
        <label>Search</label>
        <input type="text" name="search" class="input-field">
    </div>
    
    <div>
        <label>Status</label>
        <select name="status" class="input-field">
            <option value="">All</option>
        </select>
    </div>
</x-filter-section>
```

### Action Button
```blade
<x-action-button 
    variant="primary" 
    size="md"
    href="{{ route('users.create') }}">
    <x-slot name="icon">
        <svg class="w-4 h-4"><!-- icon --></svg>
    </x-slot>
    Add User
</x-action-button>
```

**Variants**: primary, secondary, success, danger, warning, info, outline  
**Sizes**: xs, sm, md, lg, xl

## 📝 Form Components

### Form Input
```blade
<x-form-input 
    label="Email Address"
    name="email"
    type="email"
    placeholder="Enter your email"
    :required="true"
    help="We'll never share your email">
</x-form-input>
```

### Form Select
```blade
<x-form-select 
    label="Status"
    name="status"
    placeholder="Select status"
    :required="true">
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</x-form-select>
```

### Form Textarea
```blade
<x-form-textarea 
    label="Description"
    name="description"
    :rows="4"
    placeholder="Enter description">
</x-form-textarea>
```

## 🎨 CSS Utility Classes

### Buttons
```html
<button class="btn-primary">Primary</button>
<button class="btn-secondary">Secondary</button>
<button class="btn-success">Success</button>
<button class="btn-danger">Danger</button>
```

### Badges
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-danger">Danger</span>
```

### Cards
```html
<div class="card p-6">
    <h3>Card Title</h3>
    <p>Card content</p>
</div>
```

### Tables
```html
<th class="table-header">Header</th>
<td class="table-cell">Cell</td>
```

### Inputs
```html
<input type="text" class="input-field" placeholder="Enter text">
```

## 📱 Responsive Grid Patterns

### 1-2-3-4 Column Grid
```html
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <!-- Cards -->
</div>
```

### 1-2-3 Column Grid
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cards -->
</div>
```

### Stack to Row
```html
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
    <!-- Content -->
</div>
```

## 🎯 Common Patterns

### Page with Filters and Table
```blade
<x-main-layout>
    <x-slot name="header">
        <x-page-header title="Users" description="Manage users" icon-color="blue">
            <x-slot name="icon"><svg>...</svg></x-slot>
            <x-slot name="actions">
                <x-action-button variant="primary" href="...">Add User</x-action-button>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-stats-card title="Total" value="100" icon-color="blue">
                <x-slot name="icon"><svg>...</svg></x-slot>
            </x-stats-card>
        </div>

        <!-- Filters -->
        <x-filter-section action="..." :hasFilters="true" resetUrl="...">
            <!-- Filters -->
        </x-filter-section>

        <!-- Table -->
        <x-table-container title="Users" :totalCount="$users->total()">
            <thead><tr><th class="table-header">Name</th></tr></thead>
            <tbody><tr><td class="table-cell">John</td></tr></tbody>
        </x-table-container>
    </div>
</x-main-layout>
```

### Form Page
```blade
<x-main-layout>
    <x-slot name="header">
        <x-page-header title="Create User" icon-color="green">
            <x-slot name="icon"><svg>...</svg></x-slot>
        </x-page-header>
    </x-slot>

    <x-section-card title="User Information">
        <form method="POST" action="..." class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input label="Name" name="name" :required="true" />
                <x-form-input label="Email" name="email" type="email" :required="true" />
            </div>

            <x-form-textarea label="Bio" name="bio" :rows="4" />

            <div class="flex justify-end gap-3">
                <x-action-button variant="outline" href="...">Cancel</x-action-button>
                <x-action-button variant="primary" type="submit">Save</x-action-button>
            </div>
        </form>
    </x-section-card>
</x-main-layout>
```

## 🌙 Dark Mode Classes
Always include dark mode variants:
```html
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
```

## ✅ Icon Sizes
- Buttons: `w-4 h-4`
- Stats cards: `w-5 h-5` or `w-6 h-6`
- Page headers: `w-6 h-6`

## 🎨 Color Scheme
- Primary: Blue
- Success: Green  
- Warning: Yellow
- Danger: Red
- Info: Indigo
- Secondary: Gray

---
For more details, see `STYLE_CONSISTENCY_GUIDE.md`

