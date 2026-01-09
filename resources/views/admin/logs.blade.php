<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('logs')" />

<div style="display: flex; flex-direction: column; height: calc(100vh - 200px); width: 100%; background-color: #f5f5f5;">
    <div style="padding: 20px; flex-shrink: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
            <h1 style="margin: 0; font-size: clamp(1.5rem, 5vw, 2.5rem); color: #333;">Error Logs</h1>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button id="refresh-logs" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; white-space: nowrap; transition: background-color 0.3s;">🔄 Refresh</button>
                <button id="delete-logs" style="padding: 8px 16px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; white-space: nowrap; transition: background-color 0.3s;">🗑️ Delete Logs</button>
            </div>
        </div>
    </div>

    <div id="logs-container" style="flex: 1; overflow-y: scroll; overflow-x: hidden; padding: 0 20px 20px 20px; display: flex; flex-direction: column; gap: 15px;">
        <div style="display: flex; justify-content: center; align-items: center; height: 100px;">
            <div style="text-align: center;">
                <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #007bff; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                <p style="margin-top: 15px; color: #666;">Loading logs...</p>
            </div>
        </div>
    </div>
</div>

<x-dashboardfooter />

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #logs-container::-webkit-scrollbar {
        width: 12px;
    }

    #logs-container::-webkit-scrollbar-track {
        background: #e9ecef;
    }

    #logs-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 6px;
    }

    #logs-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #refresh-logs:hover,
    #delete-logs:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    #refresh-logs:active,
    #delete-logs:active {
        transform: translateY(0);
    }

    .log-error {
        display: flex;
        flex-direction: column;
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
        border-radius: 4px;
        padding: 15px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #721c24;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        word-break: break-word;
        overflow-x: auto;
        flex-shrink: 0;
    }

    .log-error-header {
        font-weight: bold;
        margin-bottom: 10px;
        color: #721c24;
        border-bottom: 1px solid #f5c6cb;
        padding-bottom: 8px;
        word-break: break-all;
    }

    .log-error-content {
        margin-top: 8px;
        font-size: 11px;
        opacity: 0.9;
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .empty-logs {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
        text-align: center;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        #logs-container::-webkit-scrollbar {
            width: 8px;
        }

        .log-error {
            padding: 12px;
        }

        .log-error-header {
            font-size: 11px;
        }

        .log-error-content {
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        #logs-container::-webkit-scrollbar {
            width: 6px;
        }

        .log-error {
            padding: 10px;
        }

        .log-error-header {
            font-size: 10px;
        }

        .log-error-content {
            font-size: 9px;
        }
    }
</style>

<script>
    function loadLogs() {
        const container = document.getElementById('logs-container');
        container.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100px;">
                <div style="text-align: center;">
                    <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #007bff; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 15px; color: #666;">Loading logs...</p>
                </div>
            </div>
        `;

        fetch('{{ route("api.logs.errors") }}')
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';

                if (data.errors.length === 0) {
                    container.innerHTML = '<div class="empty-logs">✓ No errors found in logs</div>';
                    return;
                }

                data.errors.forEach((error) => {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'log-error';
                    
                    const firstLine = error.split('\n')[0];
                    const restLines = error.split('\n').slice(1).join('\n');

                    errorDiv.innerHTML = `
                        <div class="log-error-header">${escapeHtml(firstLine)}</div>
                        <div class="log-error-content">${escapeHtml(restLines)}</div>
                    `;
                    
                    container.appendChild(errorDiv);
                });
            })
            .catch(error => {
                container.innerHTML = '<div class="empty-logs">❌ Error loading logs: ' + error.message + '</div>';
            });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function deleteLogs() {
        if (!confirm('⚠️ Are you sure you want to delete all error logs? This action cannot be undone.')) {
            return;
        }

        const deleteBtn = document.getElementById('delete-logs');
        deleteBtn.disabled = true;
        deleteBtn.textContent = '⏳ Deleting...';

        fetch('{{ route("api.logs.delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            deleteBtn.disabled = false;
            deleteBtn.textContent = '🗑️ Delete Logs';

            if (data.success) {
                alert('✓ Log file deleted successfully');
                loadLogs();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            deleteBtn.disabled = false;
            deleteBtn.textContent = '🗑️ Delete Logs';
            alert('❌ Error: ' + error.message);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadLogs();
        document.getElementById('refresh-logs').addEventListener('click', loadLogs);
        document.getElementById('delete-logs').addEventListener('click', deleteLogs);
    });
</script>
