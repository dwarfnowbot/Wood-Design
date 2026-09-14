/**
 * PHP syntax check for the theme (no local PHP binary required).
 *
 *   cd wordpress/tools && npm i @php-wasm/node @php-wasm/universal
 *   node lint-php.mjs ../theme/maison-woodcraft
 *
 * Every .php file is copied into the PHP 8.3 WebAssembly filesystem and parsed
 * with token_get_all( ..., TOKEN_PARSE ). Files that do not parse are reported
 * with their error message; the script exits non-zero when anything fails.
 */
import { loadNodeRuntime } from '@php-wasm/node';
import { PHP } from '@php-wasm/universal';
import fs from 'node:fs';
import path from 'node:path';

const root = process.argv[2];
if (!root) {
	console.error('usage: node lint-php.mjs <theme-dir>');
	process.exit(2);
}

const files = [];
(function walk(dir) {
	for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
		const p = path.join(dir, entry.name);
		if (entry.isDirectory()) walk(p);
		else if (entry.name.endsWith('.php')) files.push(p);
	}
})(root);
files.sort();

const runtime = await loadNodeRuntime('8.3', { emscriptenOptions: { processId: 11 } });
const php = new PHP(runtime);

php.mkdir('/src');

const manifest = {};
files.forEach((file, index) => {
	const virtual = `/src/${String(index).padStart(3, '0')}.php`;
	php.writeFile(virtual, fs.readFileSync(file, 'utf8'));
	manifest[virtual] = path.relative(root, file);
});

php.writeFile('/manifest.json', JSON.stringify(manifest));

const script = `<?php
$files = json_decode(file_get_contents('/manifest.json'), true);
$failed = 0;
foreach ($files as $virtual => $label) {
    $code = file_get_contents($virtual);
    try {
        token_get_all($code, TOKEN_PARSE);
    } catch (Throwable $e) {
        $failed++;
        echo "SYNTAX " . $label . " :: " . $e->getMessage() . PHP_EOL;
    }
}
echo ($failed ? "FAILED " . $failed . " of " . count($files) : "OK - " . count($files) . " files parsed fine") . PHP_EOL;
`;
php.writeFile('/lint.php', script);

const result = await php.run({ scriptPath: '/lint.php' });

console.log((result.text || '').trim());

if (result.errors) {
	console.error(result.errors.trim());
}

const failed = (result.text || '').includes('FAILED');
process.exit(failed ? 1 : 0);
