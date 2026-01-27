const REPO_OWNER = 'ParasGupta-BCA';
const REPO_NAME = 'PHP-Programs';
const API_URL = `https://api.github.com/repos/${REPO_OWNER}/${REPO_NAME}/contents/`;
const PISTON_API = 'https://emkc.org/api/v2/piston/execute';

const fileListEl = document.getElementById('file-list');
const codeDisplayEl = document.getElementById('code-display');
const currentFilenameEl = document.getElementById('current-filename');
const runBtn = document.getElementById('run-btn');
const outputConsole = document.getElementById('output-console');
const clearConsoleBtn = document.getElementById('clear-console');
const refreshFilesBtn = document.getElementById('refresh-files');
const fileSearchInput = document.getElementById('file-search');
const copyBtn = document.getElementById('copy-btn');

const CACHE_KEY = 'php_repos_cache_v1';
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

let currentCode = '';
let currentActiveFile = null;
let displayedFilesJSON = '';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchFiles();

    // Refresh button
    if (refreshFilesBtn) {
        refreshFilesBtn.addEventListener('click', () => {
            // Force refresh: clear cache and fetch
            localStorage.removeItem(CACHE_KEY);
            fetchFiles();
        });
    }

    // Search functionality
    if (fileSearchInput) {
        fileSearchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            filterFiles(searchTerm);
        });
    }

    // Copy functionality
    if (copyBtn) {
        copyBtn.addEventListener('click', copyToClipboard);
    }
});

// Fetch files from GitHub
async function fetchFiles(isBackground = false) {
    if (!isBackground) {
        // checks if we already have files displayed (optimization)
        if (fileListEl.children.length === 0 || fileListEl.querySelector('.loading-spinner')) {
            fileListEl.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        }
    }

    let data = [];
    let usedCache = false;

    try {
        // 1. Try LocalStorage Cache first
        const cached = localStorage.getItem(CACHE_KEY);
        if (cached) {
            const parsed = JSON.parse(cached);
            if (Date.now() - parsed.timestamp < CACHE_DURATION) {
                // Cache is valid
                data = parsed.data;
                usedCache = true;
                if (!isBackground) console.log('Using cached file list.');
            }
        }

        // 2. Data not in cache or expired, fetch from API
        if (!usedCache) {
            // Remove timestamp to allow browser-level caching (304 Not Modified)
            const response = await fetch(API_URL);

            if (!response.ok) throw new Error(`GitHub API Error: ${response.status}`);
            data = await response.json();

            // Save to cache
            localStorage.setItem(CACHE_KEY, JSON.stringify({
                timestamp: Date.now(),
                data: data
            }));
        }

    } catch (error) {
        if (!isBackground) {
            console.warn('GitHub API failed or limit reached, using fallback file list.', error);
        }

        // Fallback file list to ensure the site works even if API rate limit is hit
        const fallbackFiles = [
            "Addition_program.php",
            "Constant.php",
            "Constants.php",
            "Data_Type.php",
            "Dot_Operator.php",
            "Even_Odd.php",
            "Factorial.php",
            "Fibonacci.php",
            "For_Loop_Table.php",
            "HelloWorld.php",
            "Increment-&-Decrement-Operators.php",
            "Operator.php",
            "Prime_Number.php",
            "String.php",
            "Switch_Case.php",
            "array.php",
            "bitwise.php",
            "calculate_truth_table.php",
            "code.php",
            "leapyear.php",
            "spaceship_opratior.php",
            "stringopraters.php",
            "truth_tables.php"
        ];

        data = fallbackFiles.map(name => ({
            name: name,
            download_url: `https://raw.githubusercontent.com/${REPO_OWNER}/${REPO_NAME}/main/${name}`
        }));
    }

    // Filter for PHP files only
    const phpFiles = data.filter(file => file.name.endsWith('.php'));

    // Check if files changed
    const newFilesJSON = JSON.stringify(phpFiles.map(f => f.name).sort());
    if (isBackground && newFilesJSON === displayedFilesJSON) {
        return; // No changes, do nothing
    }

    displayedFilesJSON = newFilesJSON;
    fileListEl.innerHTML = ''; // Clear list to rebuild

    if (phpFiles.length === 0) {
        fileListEl.innerHTML = '<li class="file-item">No PHP files found</li>';
        return;
    }

    phpFiles.forEach(file => {
        const li = document.createElement('li');
        li.className = 'file-item';
        if (currentActiveFile && currentActiveFile.name === file.name) {
            li.classList.add('active');
        }
        li.innerHTML = `<ion-icon name="logo-php"></ion-icon> ${file.name}`;
        li.onclick = () => loadFile(file, li);
        fileListEl.appendChild(li);
    });
}

// Filter files based on search term
function filterFiles(term) {
    const items = fileListEl.querySelectorAll('.file-item');
    items.forEach(item => {
        const fileName = item.innerText.toLowerCase();
        if (fileName.includes(term)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Poll for updates every 5 minutes (reduced from 60s to save API calls)
setInterval(() => fetchFiles(true), 300000);

// Load specific file content
async function loadFile(file, element) {
    currentActiveFile = file; // Track active file

    // UI Update
    document.querySelectorAll('.file-item').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    currentFilenameEl.innerText = file.name;
    runBtn.disabled = true;
    runBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Loading...';

    codeDisplayEl.innerHTML = '// Fetching code...';

    try {
        const response = await fetch(`${file.download_url}?t=${new Date().getTime()}`);
        const code = await response.text();

        currentCode = code;

        // Escape HTML for display
        const escapedCode = code
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");

        codeDisplayEl.innerHTML = escapedCode;

        // Re-highlight using Prism
        Prism.highlightElement(codeDisplayEl);

        runBtn.disabled = false;
        runBtn.innerHTML = '<ion-icon name="play"></ion-icon> Run Code';
        
        if (copyBtn) copyBtn.disabled = false;

        // Log to terminal
        renderOutput(`Loaded file: ${file.name}\nReady to execute...`, true);

    } catch (error) {
        codeDisplayEl.innerHTML = `// Error loading file: ${error.message}`;
        runBtn.innerHTML = '<ion-icon name="alert-circle"></ion-icon> Error';
        if (copyBtn) copyBtn.disabled = true;
    }
}

// Copy Code to Clipboard
async function copyToClipboard() {
    if (!currentCode) return;

    try {
        await navigator.clipboard.writeText(currentCode);
        
        // Show success tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'copy-tooltip';
        tooltip.innerText = 'Copied!';
        copyBtn.parentElement.appendChild(tooltip);
        
        setTimeout(() => tooltip.classList.add('show'), 10);
        setTimeout(() => {
            tooltip.classList.remove('show');
            setTimeout(() => tooltip.remove(), 300);
        }, 2000);

        // Change icon temporarily
        const originalIcon = copyBtn.querySelector('ion-icon').name;
        copyBtn.querySelector('ion-icon').name = 'checkmark-outline';
        setTimeout(() => {
            copyBtn.querySelector('ion-icon').name = originalIcon;
        }, 2000);

    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}

// Run Code using Piston API
runBtn.addEventListener('click', async () => {
    if (!currentCode) return;

    runBtn.disabled = true;
    runBtn.innerHTML = '<div class="spinner"></div> Running...';

    renderOutput('Running script...', true);

    try {
        const response = await fetch(PISTON_API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                language: 'php',
                version: '8.2.3',
                files: [
                    {
                        content: currentCode
                    }
                ]
            })
        });

        const result = await response.json();

        if (result.run) {
            // Combine stdout and stderr
            let output = result.run.stdout || '';
            if (result.run.stderr) {
                output += `\n\n[Error Output]:\n${result.run.stderr}`;
            }

            if (!output) {
                output = 'Script executed successfully (No output).';
            }

            renderOutput(output);
        } else {
            renderOutput('Error: Could not execute code.', true);
        }

    } catch (error) {
        renderOutput(`Execution API Error: ${error.message}`, true);
    }

    runBtn.disabled = false;
    runBtn.innerHTML = '<ion-icon name="play"></ion-icon> Run Code';
});

// Render output to Iframe
function renderOutput(content, isSystemMessage = false) {
    const frame = document.getElementById('output-frame');
    const doc = frame.contentDocument || frame.contentWindow.document;

    doc.open();

    if (isSystemMessage) {
        // Render as styled system message
        doc.write(`
            <html>
            <head>
                <style>
                    body { 
                        font-family: 'JetBrains Mono', monospace; 
                        padding: 20px; 
                        color: #ffffff; 
                        background-color: #0d1117;
                    }
                </style>
            </head>
            <body>${content}</body>
            </html>
        `);
    } else {
        // Check if content looks like full HTML
        if (content.trim().toLowerCase().includes('<html') || content.trim().toLowerCase().includes('<body')) {
            // Render as is (HTML) without injecting dark mode styles to mimic browser behavior
            doc.write(content);
        } else {
            // Render as plaintext wrapped in pre
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
                <body><pre>${content.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre></body>
                </html>
            `);
        }
    }

    doc.close();
}

// Clear output
clearConsoleBtn.addEventListener('click', () => {
    renderOutput('Ready to execute...', true);
});

// Resizer Logic
const resizer = document.getElementById('resizer');
const terminalContainer = document.getElementById('terminal-container');
const editorContainer = document.getElementById('editor-container');

let isResizing = false;

resizer.addEventListener('mousedown', (e) => {
    isResizing = true;
    resizer.classList.add('resizing');
    document.body.style.cursor = 'row-resize';
    document.body.style.userSelect = 'none'; // Prevent text selection
});

document.addEventListener('mousemove', (e) => {
    if (!isResizing) return;

    // Calculate new height
    // Total height - mouse Y position
    const containerRect = document.querySelector('.main-editor').getBoundingClientRect();
    const newHeight = containerRect.bottom - e.clientY;

    if (newHeight >= 100 && newHeight <= containerRect.height - 100) {
        terminalContainer.style.height = `${newHeight}px`;
    }
});

document.addEventListener('mouseup', () => {
    if (isResizing) {
        isResizing = false;
        resizer.classList.remove('resizing');
        document.body.style.cursor = '';
        document.body.style.userSelect = '';
    }
});
// Mobile Sidebar Logic
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

// Close sidebar when selecting a file on mobile
// We need to modify the loadFile function or intercept the click. 
// Since loadFile is called by onclick in HTML, we can add a global listener for file-items or modify fetchFiles.
// A simpler way without touching fetchFiles too much is:
document.getElementById('file-list').addEventListener('click', (e) => {
    if (e.target.closest('.file-item') && window.innerWidth <= 768) {
        closeSidebar();
    }
});
