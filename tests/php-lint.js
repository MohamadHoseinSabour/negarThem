/**
 * PHP Lint Automation Runner
 *
 * Verifies syntax of all theme PHP files.
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

function getPhpFiles(dir, fileList = []) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    if (file === 'node_modules' || file === 'vendor' || file === '.git' || file === 'backups' || file === 'dist') continue;
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    if (stat.isDirectory()) {
      getPhpFiles(filePath, fileList);
    } else if (file.endsWith('.php')) {
      fileList.push(filePath);
    }
  }
  return fileList;
}

console.log('--- Scanning Theme PHP Files for Syntax Errors ---');
const rootDir = path.resolve(__dirname, '..');
const phpFiles = getPhpFiles(rootDir);
console.log(`Found ${phpFiles.length} PHP files to lint.`);

let errorCount = 0;
for (const file of phpFiles) {
  try {
    execSync(`php -d xdebug.mode=off -l "${file}"`, { stdio: 'pipe' });
  } catch (err) {
    errorCount++;
    console.error(`SYNTAX ERROR in ${path.relative(rootDir, file)}:`);
    console.error(err.stderr ? err.stderr.toString() : err.message);
  }
}

if (errorCount > 0) {
  console.error(`Lint failed with ${errorCount} syntax error(s).`);
  process.exit(1);
} else {
  console.log(`[PASS] All ${phpFiles.length} PHP files passed linting without syntax errors.`);
  process.exit(0);
}
