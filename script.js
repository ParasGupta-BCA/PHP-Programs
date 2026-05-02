const REPO_OWNER = 'ParasGupta-BCA';
const REPO_NAME = 'PHP-Programs';
const REPO_API = `https://api.github.com/repos/${REPO_OWNER}/${REPO_NAME}`;
const RAW_BASE = `https://raw.githubusercontent.com/${REPO_OWNER}/${REPO_NAME}/main`;
const PISTON_API = 'https://emkc.org/api/v2/piston/execute';

/** Bump when manifest shape changes (invalidates API cache expectations). */
const MANIFEST_META = { version: 1 };

const fileListEl = document.getElementById('file-list');
const codeDisplayEl = document.getElementById('code-display');
const currentFilenameEl = document.getElementById('current-filename');
const runBtn = document.getElementById('run-btn');
const clearConsoleBtn = document.getElementById('clear-console');
const refreshFilesBtn = document.getElementById('refresh-files');
const fileSearchInput = document.getElementById('file-search');
const clearSearchBtn = document.getElementById('clear-search');
const copyBtn = document.getElementById('copy-btn');
const zoomInBtn = document.getElementById('zoom-in-btn');
const zoomOutBtn = document.getElementById('zoom-out-btn');
const zoomLevelEl = document.getElementById('zoom-level');

let currentFontSize = 14;
let currentSearchTerm = '';

const CACHE_KEY = 'php_repos_cache_v6';
const CACHE_DURATION = 5 * 60 * 1000;

let currentCode = '';
let currentActiveFile = null;
let displayedFilesJSON = '';

/** Base path for same-origin assets (GitHub Pages project site or local). */
function getPagesBasePath() {
    let path = window.location.pathname || '/';
    if (/index\.html?$/i.test(path)) {
        path = path.replace(/\/?index\.html?$/i, '/');
    }
    if (!path.endsWith('/')) {
        path += '/';
    }
    return path;
}

function manifestFetchUrl() {
    return `${window.location.origin}${getPagesBasePath()}files-manifest.json`;
}

function encodePathForRawUrl(repoPath) {
    return repoPath.split('/').map(encodeURIComponent).join('/');
}

function fileEntryFromPath(path) {
    const safe = path.replace(/\\/g, '/');
    return {
        name: safe.split('/').pop(),
        path: safe,
        download_url: `${RAW_BASE}/${encodePathForRawUrl(safe)}`,
    };
}

function customPathSort(a, b) {
    const aLower = a.toLowerCase();
    const bLower = b.toLowerCase();
    const isAExternal = aLower.startsWith('external_exam_preparation/');
    const isBExternal = bLower.startsWith('external_exam_preparation/');
    
    if (isAExternal && !isBExternal) return -1;
    if (!isAExternal && isBExternal) return 1;
    
    return aLower.localeCompare(bLower, undefined, { sensitivity: 'base' });
}

/** Prefer static manifest shipped with the site (no GitHub API limits on GitHub Pages). */
async function fetchFileListFromManifest() {
    const url = `${manifestFetchUrl()}?v=${MANIFEST_META.version}`;
    const res = await fetch(url, { cache: 'no-cache' });
    if (!res.ok) return null;
    const j = await res.json();
    const paths = Array.isArray(j) ? j : (j && Array.isArray(j.files) ? j.files : null);
    if (!paths || paths.length === 0) return null;
    const normalized = paths
        .filter((p) => typeof p === 'string' && p.endsWith('.php'))
        .map((p) => p.replace(/\\/g, '/'));
    normalized.sort(customPathSort);
    return normalized.map(fileEntryFromPath);
}

async function fetchPhpTreeList() {
    const refRes = await fetch(`${REPO_API}/git/ref/heads/main`);
    if (!refRes.ok) throw new Error(`Git ref: ${refRes.status}`);
    const refData = await refRes.json();
    const commitSha = refData.object.sha;

    const commitRes = await fetch(`${REPO_API}/git/commits/${commitSha}`);
    if (!commitRes.ok) throw new Error(`Git commit: ${commitRes.status}`);
    const commitData = await commitRes.json();
    const treeSha = commitData.tree.sha;

    const treeRes = await fetch(`${REPO_API}/git/trees/${treeSha}?recursive=1`);
    if (!treeRes.ok) throw new Error(`Git tree: ${treeRes.status}`);
    const treeData = await treeRes.json();
    if (!treeData.tree) return [];

    if (treeData.truncated) {
        console.warn('Git tree response truncated; file list may be incomplete.');
    }

    return treeData.tree
        .filter((e) => e.type === 'blob' && e.path.endsWith('.php'))
        .map((e) => fileEntryFromPath(e.path))
        .sort((a, b) => customPathSort(a.path, b.path));
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', () => {
    fetchFiles();

    if (refreshFilesBtn) {
        refreshFilesBtn.addEventListener('click', () => {
            localStorage.removeItem(CACHE_KEY);
            fetchFiles();
        });
    }

    if (fileSearchInput) {
        fileSearchInput.addEventListener('input', (e) => {
            currentSearchTerm = e.target.value.toLowerCase();
            filterFiles(currentSearchTerm);
            if (clearSearchBtn) {
                clearSearchBtn.style.display = currentSearchTerm ? 'flex' : 'none';
            }
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', () => {
            fileSearchInput.value = '';
            currentSearchTerm = '';
            filterFiles('');
            clearSearchBtn.style.display = 'none';
            fileSearchInput.focus();
        });
    }

    if (copyBtn) {
        copyBtn.addEventListener('click', copyToClipboard);
    }

    function updateZoom(delta) {
        let newSize = currentFontSize + delta;
        if (newSize < 8) newSize = 8;
        if (newSize > 40) newSize = 40;
        
        if (newSize !== currentFontSize) {
            currentFontSize = newSize;
            codeDisplayEl.style.setProperty('font-size', `${currentFontSize}px`, 'important');
            if (codeDisplayEl.parentElement) {
                codeDisplayEl.parentElement.style.setProperty('font-size', `${currentFontSize}px`, 'important');
            }
            if (zoomLevelEl) {
                zoomLevelEl.innerText = `${Math.round((currentFontSize / 14) * 100)}%`;
            }
        }
    }

    if (zoomInBtn) {
        zoomInBtn.addEventListener('click', () => updateZoom(2));
    }

    if (zoomOutBtn) {
        zoomOutBtn.addEventListener('click', () => updateZoom(-2));
    }
});

async function fetchFiles(isBackground = false) {
    if (!isBackground) {
        if (fileListEl.children.length === 0 || fileListEl.querySelector('.loading-spinner')) {
            fileListEl.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        }
    }

    let phpFiles = [];
    let listSource = '';

    try {
        const fromManifest = await fetchFileListFromManifest();
        if (fromManifest && fromManifest.length > 0) {
            phpFiles = fromManifest;
            listSource = 'manifest';
            if (!isBackground) {
                console.log(`Loaded ${phpFiles.length} PHP files from files-manifest.json`);
            }
        } else {
            let data = [];
            let usedCache = false;
            const cached = localStorage.getItem(CACHE_KEY);
            if (cached) {
                try {
                    const parsed = JSON.parse(cached);
                    if (Date.now() - parsed.timestamp < CACHE_DURATION && Array.isArray(parsed.data)) {
                        data = parsed.data;
                        usedCache = true;
                        if (!isBackground) console.log('Using cached file list (GitHub API).');
                    }
                } catch (_) {
                    localStorage.removeItem(CACHE_KEY);
                }
            }

            if (!usedCache) {
                data = await fetchPhpTreeList();
                localStorage.setItem(
                    CACHE_KEY,
                    JSON.stringify({ timestamp: Date.now(), data })
                );
            }

            phpFiles = Array.isArray(data)
                ? data.filter((file) => (file.path || file.name || '').endsWith('.php'))
                : [];
            listSource = usedCache ? 'cache' : 'api';
        }
    } catch (error) {
        if (!isBackground) {
            console.warn('File list fetch failed, using fallback.', error);
        }
        const fallbackPaths = [
            'Unit-01-Introduction-to-PHP/03-hello-world.php',
            'Unit-02-Functions-and-Arrays/14-arrays-introduction.php',
            'Unit-03-Strings-Dates-Forms-File-Handling/19-strings-basics.php',
            'Unit-04-Cookies-and-Sessions/28-cookies-vs-sessions-demo.php',
        ];
        phpFiles = fallbackPaths.map(fileEntryFromPath);
        listSource = 'fallback';
    }

    const newFilesJSON = JSON.stringify(phpFiles.map((f) => f.path || f.name).sort());
    if (isBackground && newFilesJSON === displayedFilesJSON) {
        return;
    }

    displayedFilesJSON = newFilesJSON;
    fileListEl.innerHTML = '';

    if (phpFiles.length === 0) {
        fileListEl.innerHTML =
            '<li class="file-item">No PHP files found. Ensure files-manifest.json is deployed.</li>';
        return;
    }

    if (listSource === 'fallback' && !isBackground) {
        const note = document.createElement('li');
        note.className = 'file-item list-banner';
        note.style.cursor = 'default';
        note.style.fontSize = '0.75rem';
        note.style.color = 'var(--text-muted)';
        note.style.lineHeight = '1.4';
        note.textContent =
            'Limited list (offline/rate limit). Push latest code so files-manifest.json is on GitHub Pages.';
        fileListEl.appendChild(note);
    }

    let currentFolder = '';

    phpFiles.forEach((file) => {
        const displayPath = file.path || file.name;
        
        let folderName = 'Root';
        let baseName = displayPath;
        const lastSlash = displayPath.lastIndexOf('/');
        if (lastSlash !== -1) {
            folderName = displayPath.substring(0, lastSlash);
            baseName = displayPath.substring(lastSlash + 1);
        }

        if (folderName !== currentFolder) {
            const header = document.createElement('li');
            header.className = 'group-header';
            header.innerHTML = `<ion-icon name="folder-open-outline"></ion-icon> <span>${escapeHtml(folderName)}</span>`;
            fileListEl.appendChild(header);
            currentFolder = folderName;
        }

        const li = document.createElement('li');
        li.className = 'file-item file-item-indented';
        li.dataset.path = displayPath;
        if (currentActiveFile && (currentActiveFile.path || currentActiveFile.name) === displayPath) {
            li.classList.add('active');
        }
        li.innerHTML = `<ion-icon name="logo-php"></ion-icon><span class="file-item-label">${escapeHtml(
            baseName
        )}</span>`;
        li.onclick = () => loadFile(file, li);
        fileListEl.appendChild(li);
    });

    if (currentSearchTerm) {
        filterFiles(currentSearchTerm);
    }
}

function filterFiles(term) {
    const banner = fileListEl.querySelector('.list-banner');
    if (banner) {
        banner.style.display = term ? 'none' : '';
    }

    const items = fileListEl.querySelectorAll('.file-item:not(.no-results):not(.list-banner)');
    let foundCount = 0;

    items.forEach((item) => {
        const fileName = (item.dataset.path || '').toLowerCase();
        if (fileName.includes(term)) {
            item.style.setProperty('display', 'flex', 'important');
            foundCount++;
        } else {
            item.style.setProperty('display', 'none', 'important');
        }
    });

    const headers = fileListEl.querySelectorAll('.group-header');
    headers.forEach((header) => {
        if (!term) {
            header.style.display = 'flex';
            return;
        }
        let hasVisible = false;
        let next = header.nextElementSibling;
        while (next && !next.classList.contains('group-header')) {
            if (next.style.display !== 'none' && !next.classList.contains('no-results') && !next.classList.contains('list-banner')) {
                hasVisible = true;
                break;
            }
            next = next.nextElementSibling;
        }
        header.style.display = hasVisible ? 'flex' : 'none';
    });

    const existingNoResults = fileListEl.querySelector('.no-results');
    if (foundCount === 0 && term !== '') {
        if (!existingNoResults) {
            const noRes = document.createElement('li');
            noRes.className = 'file-item no-results';
            noRes.style.cursor = 'default';
            noRes.style.color = 'var(--text-muted)';
            noRes.style.justifyContent = 'center';
            noRes.innerHTML = 'No matches found';
            fileListEl.appendChild(noRes);
        }
    } else if (existingNoResults) {
        existingNoResults.remove();
    }
}

setInterval(() => fetchFiles(true), 300000);

async function loadFile(file, element) {
    currentActiveFile = file;

    document.querySelectorAll('.file-item').forEach((el) => el.classList.remove('active'));
    element.classList.add('active');
    currentFilenameEl.innerText = file.path || file.name;
    runBtn.disabled = true;
    runBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Loading...';

    codeDisplayEl.innerHTML = '// Fetching code...';

    const srcUrl =
        file.download_url || `${RAW_BASE}/${encodePathForRawUrl(file.path || file.name)}`;

    try {
        const response = await fetch(`${srcUrl}${srcUrl.includes('?') ? '&' : '?'}t=${Date.now()}`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status} loading source`);
        }
        const code = await response.text();

        currentCode = code;

        const escapedCode = code
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        codeDisplayEl.innerHTML = escapedCode;
        codeDisplayEl.className = 'language-php';
        Prism.highlightElement(codeDisplayEl);

        runBtn.disabled = false;
        runBtn.innerHTML = '<ion-icon name="play"></ion-icon> Run Code';

        if (copyBtn) copyBtn.disabled = false;
        if (zoomInBtn) zoomInBtn.disabled = false;
        if (zoomOutBtn) zoomOutBtn.disabled = false;

        renderOutput(`Loaded file: ${file.path || file.name}\nReady to execute...`, true);
    } catch (error) {
        codeDisplayEl.innerHTML = `// Error loading file: ${escapeHtml(error.message)}`;
        runBtn.innerHTML = '<ion-icon name="alert-circle"></ion-icon> Error';
        if (copyBtn) copyBtn.disabled = true;
        if (zoomInBtn) zoomInBtn.disabled = true;
        if (zoomOutBtn) zoomOutBtn.disabled = true;
        renderOutput(`Load error: ${error.message}`, true);
    }
}

async function copyToClipboard() {
    if (!currentCode) return;

    const copySuccess = () => {
        const tooltip = document.createElement('div');
        tooltip.className = 'copy-tooltip';
        tooltip.innerText = 'Copied!';
        copyBtn.parentElement.appendChild(tooltip);

        setTimeout(() => tooltip.classList.add('show'), 10);
        setTimeout(() => {
            tooltip.classList.remove('show');
            setTimeout(() => tooltip.remove(), 300);
        }, 2000);

        const iconEl = copyBtn.querySelector('ion-icon');
        if (iconEl) {
            const originalIcon = iconEl.name;
            iconEl.name = 'checkmark-outline';
            setTimeout(() => {
                iconEl.name = originalIcon;
            }, 2000);
        }
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(currentCode);
            copySuccess();
            return;
        } catch (err) {
            console.warn('Clipboard API failed, trying fallback...', err);
        }
    }

    try {
        const textArea = document.createElement('textarea');
        textArea.value = currentCode;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '0';
        document.body.appendChild(textArea);

        textArea.focus();
        textArea.select();

        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);

        if (successful) {
            copySuccess();
        } else {
            throw new Error('execCommand copy failed');
        }
    } catch (err) {
        console.error('Copy fallback failed: ', err);
        alert('Could not copy code. Please select and copy manually.');
    }
}

runBtn.addEventListener('click', async () => {
    if (!currentCode) return;

    runBtn.disabled = true;
    runBtn.innerHTML = '<div class="spinner"></div> Running...';

    renderOutput('Initializing execution engine...', true);

    try {
        if (!window.phpWasm) {
            renderOutput('Loading PHP WebAssembly engine (this may take a few seconds on first run)...', true);
            const module = await import('https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs');
            window.phpWasm = new module.PhpWeb();
        }

        let outputBuffer = '';
        let errorBuffer = '';

        const outHandler = (e) => { outputBuffer += e.detail; };
        const errHandler = (e) => { errorBuffer += e.detail; };

        // Ensure engine is ready if not already
        renderOutput('Running script...', true);

        window.phpWasm.addEventListener('output', outHandler);
        window.phpWasm.addEventListener('error', errHandler);

        const exitCode = await window.phpWasm.run(currentCode);

        window.phpWasm.removeEventListener('output', outHandler);
        window.phpWasm.removeEventListener('error', errHandler);

        let output = outputBuffer || '';
        if (errorBuffer) {
            output += `${output ? '\n\n' : ''}[PHP stderr]:\n${errorBuffer}`;
        }
        if (exitCode !== 0 && exitCode != null && exitCode !== 255) {
            output += `${output ? '\n\n' : ''}[Exit code: ${exitCode}]`;
        }
        if (!output.trim()) {
            output = 'Script finished (no output). Tip: session/cookie/forms might need a full PHP local server setup.';
        }
        renderOutput(output);
    } catch (error) {
        renderOutput(`Execution Engine Error: ${error.message}`, true);
    }

    runBtn.disabled = false;
    runBtn.innerHTML = '<ion-icon name="play"></ion-icon> Run Code';
});

function renderOutput(content, isSystemMessage = false) {
    const frame = document.getElementById('output-frame');
    const doc = frame.contentDocument || frame.contentWindow.document;

    doc.open();

    const safe = escapeHtml(content);

    if (isSystemMessage) {
        doc.write(`
            <html>
            <head>
                <style>
                    body {
                        font-family: 'JetBrains Mono', monospace;
                        padding: 20px;
                        color: #ffffff;
                        background-color: #0d1117;
                        white-space: pre-wrap;
                        word-wrap: break-word;
                    }
                </style>
            </head>
            <body>${safe}</body>
            </html>
        `);
    } else {
        const raw = content.trim();
        const lower = raw.toLowerCase();
        if (lower.includes('<html') || lower.includes('<!doctype') || lower.includes('<body')) {
            doc.write(content);
        } else {
            doc.write(`
                <html>
                <head>
                    <style>
                        body {
                            margin: 0;
                            padding: 15px;
                            background: #ffffff;
                            color: #000000;
                            font-family: 'JetBrains Mono', Courier, monospace;
                        }
                        pre { white-space: pre-wrap; word-wrap: break-word; }
                    </style>
                </head>
                <body><pre>${safe}</pre></body>
                </html>
            `);
        }
    }

    doc.close();
}

clearConsoleBtn.addEventListener('click', () => {
    renderOutput('Ready to execute...', true);
});

const resizer = document.getElementById('resizer');
const terminalContainer = document.getElementById('terminal-container');

let isResizing = false;

resizer.addEventListener('mousedown', startResizing);
resizer.addEventListener('touchstart', (e) => {
    startResizing(e.touches[0]);
}, { passive: false });

function startResizing() {
    isResizing = true;
    resizer.classList.add('resizing');
    document.body.style.cursor = 'row-resize';
    document.body.style.userSelect = 'none';
}

function handleMouseMove(e) {
    if (!isResizing) return;

    const containerRect = document.querySelector('.main-editor').getBoundingClientRect();
    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

    if (!clientY) return;

    const newHeight = containerRect.bottom - clientY;

    if (newHeight >= 100 && newHeight <= containerRect.height - 100) {
        terminalContainer.style.height = `${newHeight}px`;
    }
}

document.addEventListener('mousemove', handleMouseMove);
document.addEventListener('touchmove', (e) => {
    if (isResizing) {
        handleMouseMove(e.touches[0]);
        if (e.cancelable) e.preventDefault();
    }
}, { passive: false });

function stopResizing() {
    if (isResizing) {
        isResizing = false;
        resizer.classList.remove('resizing');
        document.body.style.cursor = '';
        document.body.style.userSelect = '';
    }
}

document.addEventListener('mouseup', stopResizing);
document.addEventListener('touchend', stopResizing);

const sidebarToggleBtn = document.getElementById('sidebar-toggle');
const sidebar = document.querySelector('.sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');

function toggleSidebar() {
    sidebar.classList.toggle('open');
    sidebarOverlay.classList.toggle('active');
}

function closeSidebar() {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('active');
}

if (sidebarToggleBtn) {
    sidebarToggleBtn.addEventListener('click', toggleSidebar);
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
}

document.getElementById('file-list').addEventListener('click', (e) => {
    const item = e.target.closest('.file-item');
    if (
        item &&
        !item.classList.contains('list-banner') &&
        !item.classList.contains('no-results') &&
        window.innerWidth <= 1024
    ) {
        closeSidebar();
    }
});
