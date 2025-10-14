#!/bin/bash

# Fix font permissions for DomPDF
echo "Creating storage/fonts directory and setting permissions..."

# Create the fonts directory if it doesn't exist
mkdir -p storage/fonts

# Set proper permissions
chmod 755 storage/fonts
chown -R www-data:www-data storage/fonts

# Also ensure storage directory is writable
chmod -R 775 storage
chown -R www-data:www-data storage

echo "Font permissions fixed successfully!"
echo "Storage/fonts directory created with proper permissions."
