<!-- Phosphor Icons -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --bg-body: var(--bs-body-bg);
        --bg-user: #2563eb;
        --bg-bot: var(--bs-dark-bg-subtle);
        --text-dark: var(--bs-body-color);
        --text-gray: var(--bs-secondary-color);
        --text-light: var(--bs-tertiary-color);
        --border: var(--bs-border-color);
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --radius-lg: 16px;
        --radius-md: 12px;
        --radius-sm: 8px;
    }

    /* --- CHAT CONTAINER WRAPPER --- */
    .chat-app-container {
        height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
        background-color: var(--bg-body);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
    }

    /* --- HEADER (inside chat app) --- */
    .chat-header {
        background: var(--bg-body);
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
        z-index: 10;
    }

    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-logo-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--primary), #3b82f6);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .chat-header-title h1 {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.2;
        color: var(--text-dark);
    }

    .chat-header-title p {
        font-size: 12px;
        color: var(--text-gray);
        margin-top: 2px;
    }

    .chat-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--text-gray);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.2s;
    }

    .chat-btn-icon:hover {
        background-color: var(--bs-dark-bg-subtle);
    }

    /* --- CHAT AREA --- */
    #chat-container {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background-color: var(--bg-body);
        min-height: 0;
    }

    /* Scrollbar */
    #chat-container::-webkit-scrollbar {
        width: 6px;
    }
    #chat-container::-webkit-scrollbar-thumb {
        background-color: var(--bs-dark-border-subtle);
        border-radius: 20px;
    }
    #chat-container::-webkit-scrollbar-track {
        background: transparent;
    }

    /* Welcome Screen */
    .welcome-screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        opacity: 0.7;
        flex: 1;
    }

    .welcome-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary), #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 16px;
    }

    /* --- MESSAGES --- */
    .message-row {
        display: flex;
        width: 100%;
        animation: slideIn 0.3s ease-out forwards;
    }

    .message-row.user {
        justify-content: flex-end;
    }

    .message-row.bot {
        justify-content: flex-start;
    }

    .message-container {
        max-width: 85%;
        width: fit-content;
    }

    @media (min-width: 768px) {
        .message-container {
            max-width: 65%;
        }
    }

    .bubble {
        padding: 14px 18px;
        border-radius: var(--radius-lg);
        font-size: 15px;
        line-height: 1.5;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .bubble-user {
        background-color: var(--bg-user);
        color: white;
        border-bottom-right-radius: var(--radius-sm);
    }

    .bubble-bot {
        background-color: var(--bg-bot);
        color: var(--text-dark);
        border-bottom-left-radius: var(--radius-sm);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .msg-time {
        font-size: 11px;
        margin-top: 8px;
        opacity: 0.7;
        display: block;
    }
    
    .bubble-user .msg-time { 
        color: rgba(255, 255, 255, 0.9);
        text-align: right;
    }
    
    .bubble-bot .msg-time { 
        color: var(--text-gray);
        text-align: left;
    }

    .bubble-system {
        background: #fef2f2;
        color: #dc2626;
        border-radius: var(--radius-lg);
        padding: 12px 18px;
        font-size: 14px;
        text-align: center;
        margin: 8px auto;
        font-family: monospace;
        max-width: 90%;
        word-wrap: break-word;
        word-break: break-word;
    }

    .link-text {
        text-decoration: underline;
        color: inherit;
        word-break: break-all;
    }
    .bubble-bot .link-text { color: var(--primary); }

    /* Raw JSON Debug View */
    .debug-view {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
        font-size: 12px;
        font-family: 'Monaco', 'Consolas', monospace;
        color: var(--text-gray);
        overflow-x: auto;
        background: var(--bs-dark-bg-subtle);
        padding: 10px;
        border-radius: var(--radius-sm);
        word-break: break-word;
        white-space: pre-wrap;
    }
    .debug-label {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* --- LOADING --- */
    .loading-container {
        display: none;
        padding: 0 20px 12px 20px;
        margin-left: 0;
    }

    .typing-bubble {
        background: var(--bg-bot);
        padding: 14px 18px;
        border-radius: var(--radius-lg);
        border-bottom-left-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .dot {
        width: 7px;
        height: 7px;
        background-color: var(--text-gray);
        border-radius: 50%;
        opacity: 0.5;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .dot:nth-child(1) { animation-delay: -0.32s; }
    .dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes bounce { 
        0%, 80%, 100% { transform: scale(0); opacity: 0.4; } 
        40% { transform: scale(1); opacity: 0.8; } 
    }
    @keyframes slideIn { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    @keyframes spin { 
        to { transform: rotate(360deg); } 
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* --- FOOTER INPUT --- */
    .chat-footer {
        background: var(--bg-body);
        padding: 20px;
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }

    .input-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        position: relative;
        width: 100%;
    }

    .text-area-container {
        flex: 1;
        background: var(--bs-dark-bg-subtle);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 4px;
        transition: all 0.2s;
    }

    .text-area-container:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    textarea {
        width: 100%;
        background: transparent;
        border: none;
        padding: 12px 16px;
        font-family: inherit;
        font-size: 16px;
        color: var(--text-dark);
        resize: none;
        outline: none;
        max-height: 120px;
        display: block;
        line-height: 1.5;
    }
    textarea::placeholder { 
        color: var(--text-light); 
    }

    .btn-send {
        width: 48px;
        height: 48px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-send:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* --- MODAL --- */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        transition: opacity 0.3s;
    }

    .modal-box {
        background: var(--bg-body);
        width: 100%;
        max-width: 440px;
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        background: var(--bs-dark-bg-subtle);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        font-size: 14px;
        color: var(--text-dark);
        outline: none;
        transition: all 0.2s;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--bs-dark-border-subtle);
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider { background-color: var(--primary); }
    input:checked + .slider:before { transform: translateX(20px); }

    .note-box {
        background: #f0f9ff;
        color: #0369a1;
        font-size: 13px;
        padding: 14px;
        border-radius: var(--radius-md);
        border: 1px solid #e0f2fe;
        line-height: 1.5;
    }

    .modal-footer {
        padding: 20px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-top: 1px solid var(--border);
    }

    .btn {
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-secondary {
        background: transparent;
        color: var(--text-gray);
        border: 1px solid var(--border);
    }
    .btn-secondary:hover { 
        background: var(--bs-dark-bg-subtle); 
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-primary:hover { 
        background: var(--primary-hover); 
    }

    /* --- TOAST --- */
    .toast {
        position: fixed;
        top: 80px;
        right: 20px;
        background: var(--text-dark);
        color: white;
        padding: 12px 24px;
        border-radius: var(--radius-lg);
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 1060;
        opacity: 0;
        transition: all 0.3s ease-out;
        pointer-events: none;
        transform: translateY(-20px);
    }
    .toast.visible {
        transform: translateY(0);
        opacity: 1;
    }
    .toast.error { background-color: #dc2626; }
    .toast.success { background-color: var(--text-dark); }

    /* --- UTILS --- */
    .hidden { display: none !important; }
    .opacity-0 { opacity: 0; }
    .scale-95 { transform: scale(0.95); }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .chat-app-container {
            height: calc(100vh - 100px);
            border-radius: 0;
            margin: -15px;
        }
        
        .message-container {
            max-width: 90%;
        }
    }

    /* style baru formatted text */
    /* Markdown/Text Formatting */
    .formatted-text {
        line-height: 0.5;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .formatted-text strong {
        font-weight: 600;
    }

    .formatted-text h3, 
    .formatted-text h4 {
        margin: 1em 0 0.5em 0;
        font-weight: 600;
    }

    .formatted-text ul {
        margin: 0.5em 0;
        padding-left: 1.5em;
    }

    .formatted-text li {
        margin-bottom: 0.5em;
        position: relative;
    }

    .formatted-text p {
        margin: 0.5em 0;
    }

    .formatted-text .record-item {
        background: rgba(0, 0, 0, 0.03);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin: 12px 0;
        border-left: 3px solid var(--primary);
    }

    .formatted-text .record-header {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary);
    }

    .formatted-text .record-detail {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 4px 0;
        font-size: 14px;
    }

    .formatted-text .record-detail .emoji {
        font-size: 14px;
        opacity: 0.8;
    }

    /* Very Simple Format */
    .simple-message {
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .simple-message .record {
        margin: 12px 0;
        padding-left: 8px;
        border-left: 3px solid var(--primary);
    }

    .simple-message .record-title {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .simple-message .detail-line {
        margin: 2px 0;
        font-size: 14px;
        color: var(--text-gray);
    }

</style>

<div class="container-fluid p-0">
    <div class="chat-app-container">
        <!-- CHAT HEADER -->
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-logo-icon">
                    <i class="ph ph-chat-circle-dots"></i>
                </div>
                <div class="chat-header-title">
                    <h1><?= $title ?></h1>
                </div>
            </div>
        </div>

        <!-- CHAT AREA -->
        <main id="chat-container">
            <div class="welcome-screen" id="welcome-screen">
                <div class="welcome-icon">
                    <i class="ph ph-chat-circle-text"></i>
                </div>
                <p style="color: var(--text-gray); font-weight:500; font-size:14px; margin-bottom:4px;">Mulai percakapan dengan mengirim pesan</p>
            </div>
        </main>

        <!-- LOADING INDICATOR -->
        <div id="loading" class="loading-container">
            <div class="typing-bubble">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <!-- INPUT AREA -->
        <div class="chat-footer">
            <form id="chat-form" onsubmit="sendMessage(event)" class="input-wrapper">
                <div class="text-area-container">
                    <textarea 
                        id="message-input" 
                        rows="1" 
                        placeholder="Ketik pesan disini ..." 
                        oninput="autoResize(this)"
                        onkeydown="handleEnter(event)"
                    ></textarea>
                </div>
                <button type="submit" id="send-btn" class="btn-send">
                    <i class="ph-bold ph-paper-plane-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>


<!-- TOAST -->
<div id="toast" class="toast">
    <i class="ph-fill ph-check-circle"></i>
    <span id="toast-msg">Pesan</span>
</div>

<script>
    // CONFIG
    const STORAGE_URL_KEY = 'n8n_webhook_url';
    const STORAGE_DEBUG_KEY = 'n8n_debug_mode';
    const STORAGE_SESSION_KEY = 'n8n_session_id';

    let state = {
        webhookUrl: '<?php echo $webhook_url; ?>',
        debugMode: localStorage.getItem(STORAGE_DEBUG_KEY) === 'true',
        sessionId: localStorage.getItem(STORAGE_SESSION_KEY) || generateId(),
        isLoading: false
    };

    if (!localStorage.getItem(STORAGE_SESSION_KEY)) {
        localStorage.setItem(STORAGE_SESSION_KEY, state.sessionId);
    }

    // ELEMENTS
    const chatContainer = document.getElementById('chat-container');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const loadingIndicator = document.getElementById('loading');
    const welcomeScreen = document.getElementById('welcome-screen');
    const settingsModal = document.getElementById('settings-modal');
    const webhookInput = document.getElementById('webhook-url');
    const debugToggle = document.getElementById('debug-mode');
    const modalContent = document.getElementById('modal-content');

    // INIT
    window.addEventListener('DOMContentLoaded', () => {
        if (webhookInput) webhookInput.value = state.webhookUrl;
        if (debugToggle) debugToggle.checked = state.debugMode;

        console.log("--- Chat Loaded ---");
        console.log("Session ID:", state.sessionId);
        
        if (!state.webhookUrl && settingsModal) {
            setTimeout(() => toggleModal(true), 500);
        }
        messageInput.focus();
        
        // Ensure chat container is properly sized
        resizeChatContainer();
        window.addEventListener('resize', resizeChatContainer);
    });

    function resizeChatContainer() {
        // Ensure chat container doesn't overflow parent
        if (chatContainer) {
            const parentHeight = chatContainer.parentElement.offsetHeight;
            const headerHeight = document.querySelector('.chat-header').offsetHeight;
            const footerHeight = document.querySelector('.chat-footer').offsetHeight;
            chatContainer.style.maxHeight = (parentHeight - headerHeight - footerHeight) + 'px';
        }
    }

    // ACTIONS
    async function sendMessage(e) {
        if (e) e.preventDefault();
        
        const text = messageInput.value.trim();
        if (!text) return;
        if (state.isLoading) return;

        if (!state.webhookUrl) {
            showToast('URL Webhook belum diisi!', true);
            if (settingsModal) toggleModal(true);
            return;
        }

        // 1. UI: Add User Message
        addMessage(text, 'user');
        messageInput.value = '';
        autoResize(messageInput);
        setLoading(true);

        // 2. Payload
        const payload = {
            message: text,
            text: text,
            chatInput: text,
            sessionId: state.sessionId,
            timestamp: new Date().toISOString(),
            userid: '<?php echo $userid; ?>',
            username: '<?php echo $username; ?>',
        };

        console.log("Sending:", payload);

        try {
            // 3. Fetch
            const response = await fetch(state.webhookUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }

            const rawData = await response.json();
            console.log("Received:", rawData);

            // 4. Parse & Display
            let parsedText = parseN8nResponse(rawData);
            addMessage(parsedText, 'bot', rawData);

        } catch (error) {
            console.error("Error:", error);
            addMessage(`Error: ${error.message}`, 'system');
        } finally {
            setLoading(false);
            messageInput.focus();
        }
    }

    function parseN8nResponse(data) {
        if (!data) return "Empty response";
        if (typeof data === 'string') return data;

        let item = Array.isArray(data) ? data[0] : data;

        if (item && item.json) item = item.json;
        if (typeof item === 'string') return item;

        const textKeys = ['output', 'text', 'message', 'response', 'answer', 'content', 'result'];
        for (let key of textKeys) {
            if (item && item[key]) {
                return typeof item[key] === 'object' ? JSON.stringify(item[key]) : item[key];
            }
        }

        if (item && typeof item === 'object') {
            const values = Object.values(item);
            const firstString = values.find(v => typeof v === 'string');
            if (firstString) return firstString;
        }

        return JSON.stringify(data, null, 2);
    }

    function addMessage(text, type, rawData = null) {
        if (welcomeScreen) welcomeScreen.style.display = 'none';

        const div = document.createElement('div');
        const isUser = type === 'user';
        const isSystem = type === 'system';
        
        div.className = `message-row ${type}`;

        if (isSystem) {
            div.innerHTML = `<div class="bubble-system">${escapeHtml(text)}</div>`;
        } else {
            const bubbleClass = isUser ? 'bubble-user' : 'bubble-bot';
            const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            // Content Formatting
            // let contentHtml = formatText(text);
            let contentHtml = formatReturDataSimple(text);

            // Debug Info
            let debugInfo = '';
            if (!isUser && state.debugMode && rawData) {
                debugInfo = `
                    <div class="debug-view">
                        <div class="debug-label">Raw JSON:</div>
                        ${escapeHtml(JSON.stringify(rawData, null, 2))}
                    </div>
                `;
            }

            div.innerHTML = `
                <div class="message-container">
                    <div class="bubble ${bubbleClass}">
                        <div>${contentHtml}</div>
                        <span class="msg-time">${time}</span>
                        ${debugInfo}
                    </div>
                </div>
            `;
        }

        chatContainer.appendChild(div);
        scrollToBottom();
    }

    // --- HELPERS ---

    function toggleModal(forceOpen = false) {
        const isHidden = settingsModal.classList.contains('hidden');
        if (isHidden || forceOpen) {
            settingsModal.classList.remove('hidden');
            void settingsModal.offsetWidth;
            settingsModal.classList.remove('opacity-0');
            if (modalContent) modalContent.classList.remove('scale-95');
        } else {
            settingsModal.classList.add('opacity-0');
            if (modalContent) modalContent.classList.add('scale-95');
            setTimeout(() => settingsModal.classList.add('hidden'), 300);
        }
    }

    function saveSettings() {
        if (!webhookInput) return;
        const url = webhookInput.value.trim();
        if (!url) {
            showToast('URL tidak boleh kosong', true);
            return;
        }
        state.webhookUrl = url;
        state.debugMode = debugToggle ? debugToggle.checked : false;
        
        localStorage.setItem(STORAGE_URL_KEY, url);
        localStorage.setItem(STORAGE_DEBUG_KEY, state.debugMode);
        
        toggleModal();
        showToast('Pengaturan disimpan');
    }

    function setLoading(isLoading) {
        state.isLoading = isLoading;
        sendBtn.disabled = isLoading;
        sendBtn.innerHTML = isLoading 
            ? '<i class="ph ph-spinner animate-spin"></i>' 
            : '<i class="ph-bold ph-paper-plane-right"></i>';
        
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
        if (isLoading) scrollToBottom();
    }

    function showToast(msg, isError = false) {
        const toastEl = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-msg');
        if (!toastEl || !toastMsg) return;
        
        toastMsg.innerText = msg;
        
        // Remove old states
        toastEl.classList.remove('success', 'error', 'visible');
        
        // Set new state
        if (isError) {
            toastEl.classList.add('error');
            const icon = toastEl.querySelector('i');
            if (icon) icon.className = 'ph-fill ph-warning-circle';
        } else {
            toastEl.classList.add('success');
            const icon = toastEl.querySelector('i');
            if (icon) icon.className = 'ph-fill ph-check-circle';
        }

        // Animate in
        requestAnimationFrame(() => {
            toastEl.classList.add('visible');
        });

        // Animate out
        setTimeout(() => {
            toastEl.classList.remove('visible');
        }, 3000);
    }

    function handleEnter(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    function scrollToBottom() {
        requestAnimationFrame(() => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    }

    function generateId() {
        return Math.random().toString(36).substr(2, 9);
    }

    function escapeHtml(text) {
        if (!text || typeof text !== 'string') return "";
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
    }

    function formatText(text) {
        if (!text) return "";
        if (typeof text !== 'string') {
            return `<code style="background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 13px;">${escapeHtml(JSON.stringify(text))}</code>`;
        }
        
        // Escape HTML pertama
        let html = escapeHtml(text);
        
        // Preserve the original newlines by converting to <br> first
        html = html.replace(/\n/g, '<br>');
        
        // Convert markdown bold (**text**) to <strong>
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert numbered lists (1., 2., etc.)
        html = html.replace(/(\d+\.)\s+(.*?)(?=<br>|$)/g, '<div class="record-item"><div class="record-header">$1 $2</div>');
        
        // Convert detail lines with emojis
        html = html.replace(/(📍|🏢|🏛️|📊|📅)\s+(.*?)(?=<br>|$)/g, 
            '<div class="record-detail"><span class="emoji">$1</span> $2</div>');
        
        // Convert URLs to links
        html = html.replace(/((http|https):\/\/[^\s<]+)/g, 
            '<a href="$1" target="_blank" class="link-text">$1</a>');
        
        // Wrap in formatted-text container
        return `<div class="formatted-text">${html}</div>`;
    }

    function formatReturDataSimple(text) {
        if (!text || typeof text !== 'string') return escapeHtml(text || '');
        
        let html = text;
        
        // Convert newlines to <br>
        html = html.replace(/\n/g, '<br>');
        
        // Add spacing between records
        html = html.replace(/(\d+\.\s+RTR-[^<]+)/g, '<div class="record"><div class="record-title">$1</div>');
        
        // Close record divs
        html = html.replace(/<br><br>/g, '</div><br>');
        
        // Style detail lines
        html = html.replace(/<br>(📍|🏢|🏛️|📊|📅)\s+/g, '<br><span class="detail-line">$1 ');
        
        // Convert **bold** to <strong>
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        return `<div class="simple-message">${html}</div>`;
    }

</script>