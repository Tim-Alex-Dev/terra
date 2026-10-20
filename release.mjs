// npm run release (on develop, committed): builds dist/, merges develop into master in a temporary worktree, commits dist/ there
// (only master tracks dist/, it is git-ignored) and pushes master. This folder stays on develop with its dist/ untouched.
import {execFileSync, spawnSync} from 'node:child_process';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';

const git = (args, cwd = '.') => execFileSync('git', args, {cwd, encoding: 'utf8'}).trim();
const run = (cmd, args, cwd = '.') => {
	const result = spawnSync(cmd, args, {cwd, stdio: 'inherit'});
	if (0 !== result.status) {
		throw new Error(`${path.basename(cmd)} ${args.join(' ')} failed`);
	}
};
const fail = message => {
	console.error(`Release stopped: ${message}`);
	process.exit(1);
};

if ('develop' !== git(['branch', '--show-current'])) {
	fail('switch to develop first');
}
if (git(['status', '--porcelain'])) {
	fail('commit or stash your changes on develop first');
}

run(process.execPath, ['node_modules/gulp/bin/gulp.js', 'prod']);
if (!fs.existsSync('dist/css/main.css')) {
	fail('the build did not create dist/');
}

const sha = git(['rev-parse', '--short', 'develop']);
const worktree = path.join(os.tmpdir(), `terra-release-${Date.now()}`);
run('git', ['worktree', 'add', '--quiet', worktree, 'master']);
try {
	const merged = 0 === spawnSync('git', ['merge-base', '--is-ancestor', 'develop', 'master']).status;
	if (!merged) {
		run('git', ['merge', '--no-ff', '--quiet', 'develop', '-m', `Merge develop (${sha}) into master`], worktree);
	}

	fs.rmSync(path.join(worktree, 'dist'), {recursive: true, force: true});
	fs.cpSync('dist', path.join(worktree, 'dist'), {recursive: true});
	run('git', ['add', '--force', '--all', 'dist'], worktree);
	if (git(['diff', '--cached', '--name-only'], worktree)) {
		run('git', ['commit', '--quiet', '-m', `build: dist for ${sha}`], worktree);
	}

	run('git', ['push', '--quiet', 'origin', 'master'], worktree);
	console.log(`Released ${sha}: master ${git(['rev-parse', '--short', 'master'])} pushed. Deploy it in cPanel (Update from Remote, Deploy HEAD Commit).`);
} finally {
	run('git', ['worktree', 'remove', '--force', worktree]);
}
