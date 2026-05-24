/**
 * Husky install on Windows: ServBay's git.cmd breaks `husky` (spawnSync).
 * Prefer Git for Windows / Laragon git.exe and prepend to PATH, then run husky.
 */
const { spawnSync, execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');

function resolveGitExe() {
    const candidates = [
        process.env.PROGRAMFILES && path.join(process.env.PROGRAMFILES, 'Git', 'cmd', 'git.exe'),
        process.env['ProgramFiles(x86)'] &&
            path.join(process.env['ProgramFiles(x86)'], 'Git', 'cmd', 'git.exe'),
        'C:\\Program Files\\Git\\cmd\\git.exe',
        'C:\\laragon\\bin\\git\\cmd\\git.exe',
    ].filter(Boolean);

    for (const candidate of candidates) {
        if (fs.existsSync(candidate)) {
            return candidate;
        }
    }

    const where = spawnSync('where.exe', ['git'], { encoding: 'utf8', shell: true });
    if (where.status === 0) {
        for (const line of where.stdout.split(/\r?\n/)) {
            const trimmed = line.trim();
            if (trimmed.endsWith('git.exe') && !/ServBay/i.test(trimmed)) {
                return trimmed;
            }
        }
    }

    return null;
}

const gitExe = resolveGitExe();
if (!gitExe) {
    console.error('husky install: git.exe not found. Install Git for Windows or fix PATH.');
    process.exit(1);
}

const gitDir = path.dirname(gitExe);
const pathParts = process.env.PATH.split(path.delimiter).filter((part) => !/ServBay/i.test(part));
process.env.PATH = [gitDir, ...pathParts].join(path.delimiter);

try {
    execSync(`node "${path.join(root, 'node_modules', 'husky', 'bin.js')}"`, {
        cwd: root,
        stdio: 'inherit',
        env: process.env,
    });
} catch {
    process.exit(1);
}
