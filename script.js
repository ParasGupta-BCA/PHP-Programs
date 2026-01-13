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

let currentCode = '';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchFiles();
});

// Fetch files from GitHub
async function fetchFiles() {
    try {
        const response = await fetch(API_URL);
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
        const response = await fetch(file.download_url);
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
        logToTerminal(`Loaded file: ${file.name}`, 'text-muted');

    } catch (error) {
        codeDisplayEl.innerHTML = `// Error loading file: ${error.message}`;
        runBtn.innerHTML = '<ion-icon name="alert-circle"></ion-icon> Error';
    }
}

// Run Code using Piston API
runBtn.addEventListener('click', async () => {
    if (!currentCode) return;

    runBtn.disabled = true;
    runBtn.innerHTML = '<div class="spinner" style="width:16px;height:16px;border-width:2px;"></div> Running...';
    
    logToTerminal('----------------------------------');
    logToTerminal('Executing script...', 'text-muted');

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
            if (result.run.stdout) {
                logToTerminal(result.run.stdout);
            }
            if (result.run.stderr) {
                logToTerminal(result.run.stderr, 'text-danger');
            }
            if (!result.run.stdout && !result.run.stderr) {
                logToTerminal('Script executed successfully (No output).', 'text-success');
            }
        } else {
            logToTerminal('Error: Could not execute code.', 'text-danger');
        }

    } catch (error) {
        logToTerminal(`Execution API Error: ${error.message}`, 'text-danger');
    }

    runBtn.disabled = false;
    runBtn.innerHTML = '<ion-icon name="play"></ion-icon> Run Code';
});

// Helper: Log to custom terminal
function logToTerminal(message, cssClass = '') {
    const line = document.createElement('div');
    line.className = `terminal-line ${cssClass}`;
    line.innerText = message;
    outputConsole.appendChild(line);
    // Auto scroll to bottom
    outputConsole.scrollTop = outputConsole.scrollHeight;
}

// Clear console
clearConsoleBtn.addEventListener('click', () => {
    outputConsole.innerHTML = '<div class="terminal-line text-muted">Console cleared.</div>';
});
