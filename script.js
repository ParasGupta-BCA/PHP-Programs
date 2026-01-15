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

let currentCode = '';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchFiles();

    // Refresh button
    if (refreshFilesBtn) {
        refreshFilesBtn.addEventListener('click', () => {
            fetchFiles();
        });
    }
});

// Fetch files from GitHub
async function fetchFiles() {
    try {
        fileListEl.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';

        // Add timestamp to query to prevent aggressive caching
        const response = await fetch(`${API_URL}?t=${new Date().getTime()}`, {
            cache: 'no-store'
        });

        if (!response.ok) throw new Error('Failed to load files');
        const data = await response.json();

        fileListEl.innerHTML = ''; // Clear loading

        // Filter for PHP files only
        const phpFiles = data.filter(file => file.name.endsWith('.php'));

        if (phpFiles.length === 0) {
            fileListEl.innerHTML = '<li class="file-item">No PHP files found</li>';
            return;
        }

        phpFiles.forEach(file => {
            const li = document.createElement('li');
            li.className = 'file-item';
            li.innerHTML = `<ion-icon name="logo-php"></ion-icon> ${file.name}`;
            li.onclick = () => loadFile(file, li);
            fileListEl.appendChild(li);
        });

    } catch (error) {
        fileListEl.innerHTML = `<li class="file-item" style="color:var(--danger)">Error: ${error.message}</li>`;
    }
}

// Load specific file content
async function loadFile(file, element) {
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

        // Log to terminal
        renderOutput(`Loaded file: ${file.name}\nReady to execute...`, true);

    } catch (error) {
        codeDisplayEl.innerHTML = `// Error loading file: ${error.message}`;
        runBtn.innerHTML = '<ion-icon name="alert-circle"></ion-icon> Error';
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
                    body { font-family: monospace; padding: 20px; color: #555; }
                </style>
            </head>
            <body>${content}</body>
            </html>
        `);
    } else {
        // Check if content looks like full HTML
        if (content.trim().toLowerCase().includes('<html') || content.trim().toLowerCase().includes('<body')) {
            // Render as is (HTML)
            doc.write(content);
        } else {
            // Render as plaintext wrapped in pre
            doc.write(`
                <html>
                <head>
                    <style>
                        body { margin: 0; padding: 15px; background: #fff; font-family: 'Courier New', Courier, monospace; }
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
