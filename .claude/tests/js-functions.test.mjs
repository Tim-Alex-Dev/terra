import { readFileSync } from 'node:fs';

// The theme JS files are ES modules with a .js extension: load them from their source without copying.
const load = async (file) => import('data:text/javascript;base64,' + readFileSync(new URL('../../assets/js/functions/' + file, import.meta.url)).toString('base64'));
const { throttle } = await load('throttle.js');

let fail = 0; const check = (n, ok) => { if (!ok) fail++; console.log(n.padEnd(52), ok ? 'PASS' : 'FAIL'); };
const sleep = ms => new Promise(r => setTimeout(r, ms));

// throttle: plain call (this === undefined in strict mode) must still get its trailing call
let calls = []; const f = throttle(v => calls.push(v), 50);
f(1); f(2); f(3);
check('leading call runs immediately', calls.length === 1 && calls[0] === 1);
await sleep(120);
check('trailing call uses the LAST args (plain call, no this)', calls.length === 2 && calls[1] === 3);
// method call keeps context
const obj = { n: 7, m: throttle(function (x) { calls.push(this.n + x); }, 30) };
calls = []; obj.m(1); obj.m(2); await sleep(90);
check('context preserved for method calls', calls[0] === 8 && calls[1] === 9);
// no trailing call when only one call happened
calls = []; const g = throttle(v => calls.push(v), 30); g('a'); await sleep(80);
check('single call -> no duplicate trailing call', calls.length === 1);

console.log(fail ? `\n${fail} FAILED` : '\nALL PASS'); process.exit(fail ? 1 : 0);
