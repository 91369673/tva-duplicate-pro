#!/bin/bash

# Build script for tva-duplicate-pro WordPress plugin
# Creates a clean distribution zip file

# Get version from main plugin file
VERSION=$(grep "Version:" tva-duplicate-pro.php | sed 's/.*Version: \(.*\)/\1/' | tr -d '[:space:]')
PLUGIN_NAME="tva-duplicate-pro"
BUILD_DIR="build"
DIST_DIR="dist"
ARCHIVE_DIR="archive/$VERSION"

echo "Building $PLUGIN_NAME version $VERSION..."

# Create necessary directories
mkdir -p $BUILD_DIR
mkdir -p $DIST_DIR
mkdir -p $ARCHIVE_DIR

# Clean build directory
rm -rf $BUILD_DIR/*

# Copy required files to build directory
cp tva-duplicate-pro.php $BUILD_DIR/
cp -r includes $BUILD_DIR/
cp -r assets $BUILD_DIR/
# Add readme.txt for WordPress repository if needed
# cp readme.txt $BUILD_DIR/

# Remove unnecessary files
find $BUILD_DIR -name '.DS_Store' -type f -delete
find $BUILD_DIR -name '.git*' -type f -delete

# Create zip file
cd $BUILD_DIR
zip -r ../$DIST_DIR/$PLUGIN_NAME-$VERSION.zip .
cd ..

# Copy to archive
cp $DIST_DIR/$PLUGIN_NAME-$VERSION.zip $ARCHIVE_DIR/

echo "Build complete: $DIST_DIR/$PLUGIN_NAME-$VERSION.zip"
echo "Archive also saved to: $ARCHIVE_DIR/$PLUGIN_NAME-$VERSION.zip"

# Cleanup
rm -rf $BUILD_DIR