/**
 * Distributable Package Builder
 *
 * Packages production-ready WordPress theme into dist/negar-shop.zip
 * excluding dev tools, git history, and node_modules.
 */

const fs = require('fs');
const path = require('path');
const os = require('os');
const { execSync } = require('child_process');

const THEME_DIR = path.resolve(__dirname, '..');
const DIST_DIR = path.join(THEME_DIR, 'dist');
const ZIP_PATH = path.join(DIST_DIR, 'negar-shop.zip');
const TEMP_BASE = fs.mkdtempSync(path.join(os.tmpdir(), 'negar-build-'));
const STAGING_DIR = path.join(TEMP_BASE, 'negar-shop');

console.log('--- Packaging Negar Shop Production Distribution ---');

if (!fs.existsSync(DIST_DIR)) {
  fs.mkdirSync(DIST_DIR, { recursive: true });
}

// Exclude non-production paths
const EXCLUDE_NAMES = new Set([
  '.git',
  '.agents',
  '.claude',
  '.specify',
  '.gemini',
  'node_modules',
  'backups',
  'dist',
  'scratch',
  'tests',
  'scripts',
  '.env',
  'package.json',
  'package-lock.json',
  'negar-shop.zip'
]);

function copyDirClean(src, dest) {
  if (!fs.existsSync(dest)) {
    fs.mkdirSync(dest, { recursive: true });
  }
  const entries = fs.readdirSync(src, { withFileTypes: true });
  for (const entry of entries) {
    if (EXCLUDE_NAMES.has(entry.name)) continue;
    if (entry.name.endsWith('.log') || entry.name.endsWith('.zip')) continue;

    const srcPath = path.join(src, entry.name);
    const destPath = path.join(dest, entry.name);

    if (entry.isDirectory()) {
      copyDirClean(srcPath, destPath);
    } else {
      fs.copyFileSync(srcPath, destPath);
    }
  }
}

console.log('Staging clean theme files into temporary directory:', STAGING_DIR);
copyDirClean(THEME_DIR, STAGING_DIR);

if (fs.existsSync(ZIP_PATH)) {
  fs.unlinkSync(ZIP_PATH);
}

// Create ZIP using Windows built-in tar (bsdtar) with zip format
console.log('Compressing to', ZIP_PATH);
execSync(`tar.exe -a -c -f "${ZIP_PATH}" -C "${TEMP_BASE}" "negar-shop"`, { stdio: 'inherit' });

// Cleanup temp directory
try {
  fs.rmSync(TEMP_BASE, { recursive: true, force: true });
} catch (e) {
  // Ignore temp cleanup warnings
}

const stat = fs.statSync(ZIP_PATH);
console.log(`[SUCCESS] Generated clean distribution package: dist/negar-shop.zip (${(stat.size / 1024 / 1024).toFixed(2)} MB)`);
