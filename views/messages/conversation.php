<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Conversation avec <?= e($interlocuteur['prenom']) ?> — PeerLearn</title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/messages/conversation.css">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-white dark:bg-gray-900 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<script>
    const USER_ID = <?= json_encode(getUserId()) ?>;
    const INTERLOCUTEUR_ID = <?= json_encode((int)$interlocuteur['id']) ?>;
    const APP_URL = <?= json_encode(APP_URL) ?>;
    let LAST_MESSAGE_ID = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
</script>

<main class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900 h-screen overflow-hidden">

    <!-- ==================== HEADER ==================== -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-4 py-3 flex items-center gap-3 flex-shrink-0 shadow-sm">
        <a href="<?= APP_URL ?>/?url=messages" 
           class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        
        <div class="flex items-center gap-3 flex-1">
            <div class="relative">
                <?php if (!empty($interlocuteur['photo'])): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($interlocuteur['photo']) ?>" 
                         class="w-11 h-11 rounded-full object-cover">
                <?php else: ?>
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        <?= strtoupper(substr($interlocuteur['prenom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white text-base">
                    <?= e($interlocuteur['prenom'] . ' ' . $interlocuteur['nom']) ?>
                    <?php if (!empty($interlocuteur['est_mentor'])): ?>
                        <span class="ml-1 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-1.5 py-0.5 rounded-full">Mentor</span>
                    <?php endif; ?>
                </p>
                <p id="online-status" class="text-xs text-green-500 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                    En ligne
                </p>
            </div>
        </div>
        
        <button id="theme-toggle" onclick="toggleTheme()" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition">
            <!-- Icône sera mise à jour par JS -->
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    <!-- ==================== MESSAGES ZONE ==================== -->
    <div id="chat-messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        
        <div id="message-list" class="space-y-3">
            <?php if (empty($messages)): ?>
                <div class="flex flex-col items-center justify-center h-full min-h-[300px]">
                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun message</p>
                    <p class="text-gray-400 text-xs mt-1">Envoyez votre premier message à <?= e($interlocuteur['prenom']) ?> !</p>
                </div>
            <?php else: ?>
                <?php 
                $last_date = '';
                foreach ($messages as $msg):
                    $is_mine = ($msg['envoyeur_id'] == getUserId());
                    $msg_date = date('Y-m-d', strtotime($msg['date_envoi']));
                    $is_system = isset($msg['est_systeme']) && $msg['est_systeme'] == 1;
                    
                    if ($msg_date != $last_date):
                        $last_date = $msg_date;
                        $today = date('Y-m-d');
                        $yesterday = date('Y-m-d', strtotime('-1 day'));
                        
                        if ($msg_date == $today) $display_date = "Aujourd'hui";
                        elseif ($msg_date == $yesterday) $display_date = "Hier";
                        else $display_date = date('d F Y', strtotime($msg_date));
                ?>
                    <div class="date-separator flex justify-center my-4">
                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1 rounded-full shadow-sm"><?= $display_date ?></span>
                    </div>
                <?php 
                    endif;
                    
                    if ($is_system):
                ?>
                    <!-- Message système -->
                    <div class="flex justify-center my-2">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full px-4 py-1.5 max-w-[80%]">
                            <p class="text-xs text-gray-600 dark:text-gray-300 text-center whitespace-pre-line"><?= nl2br(e($msg['contenu'])) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Message normal -->
                    <div class="message-wrapper flex <?= $is_mine ? 'justify-end' : 'justify-start' ?> group relative" data-message-id="<?= $msg['id'] ?>">
                        <?php if (!$is_mine): ?>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mr-2 mt-1 shadow-sm">
                                <?= strtoupper(substr($interlocuteur['prenom'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="message-container <?= $is_mine ? 'items-end' : 'items-start' ?> flex flex-col max-w-[75%] sm:max-w-[65%]">
                            <div class="message-bubble relative group/bubble">
                                <div class="<?= $is_mine 
                                    ? 'bg-purple-600 text-white rounded-2xl rounded-tr-sm' 
                                    : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-2xl rounded-tl-sm shadow-sm' ?> 
                                    px-4 py-2.5 message-bubble">
                                    <p class="text-sm leading-relaxed break-words"><?= nl2br(e($msg['contenu'])) ?></p>
                                </div>
                                
                                <!-- Menu contextuel (trois petits points) -->
                                <div class="absolute <?= $is_mine ? '-left-8' : '-right-8' ?> top-1/2 -translate-y-1/2 opacity-0 group-hover/bubble:opacity-100 transition-opacity">
                                    <button onclick="showContextMenu(event, <?= $msg['id'] ?>, <?= $is_mine ? 'true' : 'false' ?>)" 
                                            class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center transition">
                                        <svg class="w-3 h-3 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1 mt-1 <?= $is_mine ? 'justify-end' : 'justify-start' ?>">
                                <span class="text-xs text-gray-400 dark:text-gray-500"><?= date('H:i', strtotime($msg['date_envoi'])) ?></span>
                                <?php if ($is_mine && isset($msg['lu']) && $msg['lu']): ?>
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                <?php elseif ($is_mine): ?>
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($msg['fichier_joint'])): ?>
                            <div class="mt-1 <?= $is_mine ? 'text-right' : 'text-left' ?>">
                                <a href="<?= APP_URL ?>/uploads/messages/<?= e($msg['fichier_joint']) ?>" target="_blank" 
                                   class="inline-flex items-center gap-1 text-xs text-purple-500 hover:text-purple-600 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    Pièce jointe
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            <?php endif; ?>
        </div>
        <div id="scroll-anchor"></div>
        
        <!-- Indicateur de saisie -->
        <div id="typing-indicator" class="hidden justify-start">
            <div class="bg-white dark:bg-gray-800 rounded-2xl rounded-tl-sm px-4 py-2 shadow-sm">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== INPUT ZONE ==================== -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 px-4 py-3 flex-shrink-0">
        <div class="flex items-end gap-2 max-w-4xl mx-auto">
            <button id="attach-btn" onclick="document.getElementById('file-input').click()" 
                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition flex-shrink-0 group">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </button>
            <input type="file" id="file-input" class="hidden" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
            
            <div class="flex-1 relative">
                <textarea id="message-input" 
                          rows="1"
                          placeholder="Écrire un message..."
                          class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 border-0 rounded-2xl text-sm text-gray-900 dark:text-white resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 message-input"></textarea>
            </div>
            
            <button id="send-btn" onclick="sendMessage()"
                    class="w-10 h-10 rounded-full bg-purple-600 hover:bg-purple-700 flex items-center justify-center transition shadow-md flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
        <div id="file-preview" class="hidden mt-2 px-2">
            <div class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-1.5 text-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <span id="file-name" class="text-gray-600 dark:text-gray-300"></span>
                <button onclick="clearFile()" class="text-red-500 hover:text-red-600 ml-2">✕</button>
            </div>
        </div>
    </div>
</main>

<!-- Menu contextuel flottant -->
<div id="context-menu" class="context-menu hidden"></div>

<script>
// ==================== DOM Elements ====================
const messagesContainer = document.getElementById('chat-messages');
const messageList = document.getElementById('message-list');
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('send-btn');
const fileInput = document.getElementById('file-input');
const filePreview = document.getElementById('file-preview');
const fileName = document.getElementById('file-name');
const typingIndicator = document.getElementById('typing-indicator');

let selectedFile = null;
let scrollTimeout = null;
let typingTimeout = null;

// ==================== Utilitaires ====================
function scrollToBottom() {
    messagesContainer.scrollTo({
        top: messagesContainer.scrollHeight,
        behavior: 'smooth'
    });
}
scrollToBottom();

function autoResizeTextarea() {
    messageInput.style.height = 'auto';
    messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
}

messageInput.addEventListener('input', autoResizeTextarea);
messageInput.addEventListener('input', () => {
    if (!typingTimeout) {
        sendTypingIndicator();
    }
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {}, 1000);
});

// Indicateur de saisie
function sendTypingIndicator() {
    fetch(`${APP_URL}/?url=typing&user_id=${INTERLOCUTEUR_ID}`, { method: 'POST' });
}

// ==================== Envoi de message ====================
async function sendMessage() {
    const content = messageInput.value.trim();
    if (!content && !selectedFile) return;
    
    sendBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('contenu', content);
    formData.append('destinataire_id', INTERLOCUTEUR_ID);
    formData.append('csrf_token', '<?= generateCsrfToken() ?>');
    if (selectedFile) {
        formData.append('fichier', selectedFile);
    }
    
    try {
        const response = await fetch(`${APP_URL}/?url=envoyer-msg`, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.ok) {
            addMessageToUI(data.message, true);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            clearFile();
            scrollToBottom();
            LAST_MESSAGE_ID = data.message.id;
        } else {
            alert(data.erreur || 'Erreur lors de l\'envoi');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur réseau');
    } finally {
        sendBtn.disabled = false;
        messageInput.focus();
    }
}

// ==================== Ajouter un message à l'UI ====================
function addMessageToUI(msg, isMine) {
    const messageDiv = document.createElement('div');
    const isSystem = msg.est_systeme === 1;
    const time = new Date(msg.date_envoi).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    
    if (isSystem) {
        messageDiv.className = 'flex justify-center my-2';
        messageDiv.innerHTML = `
            <div class="bg-gray-200 dark:bg-gray-700 rounded-full px-4 py-1.5 max-w-[80%]">
                <p class="text-xs text-gray-600 dark:text-gray-300 text-center">${escapeHtml(msg.contenu)}</p>
            </div>
        `;
    } else {
        messageDiv.className = `message-wrapper flex ${isMine ? 'justify-end' : 'justify-start'} group relative`;
        messageDiv.setAttribute('data-message-id', msg.id);
        
        const avatarHtml = !isMine ? `
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mr-2 mt-1 shadow-sm">
                ${msg.envoyeur_prenon ? msg.envoyeur_prenom.charAt(0).toUpperCase() : '?'}
            </div>
        ` : '';
        
        messageDiv.innerHTML = `
            ${avatarHtml}
            <div class="message-container ${isMine ? 'items-end' : 'items-start'} flex flex-col max-w-[75%] sm:max-w-[65%]">
                <div class="message-bubble relative group/bubble">
                    <div class="${isMine ? 'bg-purple-600 text-white rounded-2xl rounded-tr-sm' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-2xl rounded-tl-sm shadow-sm'} px-4 py-2.5">
                        <p class="text-sm leading-relaxed break-words">${escapeHtml(msg.contenu)}</p>
                    </div>
                    <div class="absolute ${isMine ? '-left-8' : '-right-8'} top-1/2 -translate-y-1/2 opacity-0 group-hover/bubble:opacity-100 transition-opacity">
                        <button onclick="showContextMenu(event, ${msg.id}, ${isMine})" 
                                class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center transition">
                            <svg class="w-3 h-3 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-1 ${isMine ? 'justify-end' : 'justify-start'}">
                    <span class="text-xs text-gray-400">${time}</span>
                    ${isMine ? '<svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>' : ''}
                </div>
            </div>
        `;
    }
    
    messageList.appendChild(messageDiv);
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}

// ==================== Gestion fichiers ====================
fileInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
        selectedFile = e.target.files[0];
        fileName.textContent = selectedFile.name;
        filePreview.classList.remove('hidden');
    }
});

function clearFile() {
    selectedFile = null;
    fileInput.value = '';
    filePreview.classList.add('hidden');
}

// ==================== Envoi avec Entrée ====================
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// ==================== Polling ====================
let pollingActive = true;

async function pollMessages() {
    if (!pollingActive) return;
    try {
        const response = await fetch(`${APP_URL}/?url=poll&user_id=${INTERLOCUTEUR_ID}&dernier_id=${LAST_MESSAGE_ID}`);
        const data = await response.json();
        
        if (data.messages && data.messages.length > 0) {
            const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 100;
            
            data.messages.forEach(msg => {
                if (!document.querySelector(`[data-message-id="${msg.id}"]`)) {
                    addMessageToUI(msg, msg.envoyeur_id == USER_ID);
                    LAST_MESSAGE_ID = Math.max(LAST_MESSAGE_ID, msg.id);
                }
            });
            
            if (wasAtBottom) scrollToBottom();
            
            if (data.total_non_lus > 0) {
                document.title = `(${data.total_non_lus}) Conversation — PeerLearn`;
            } else {
                document.title = `Conversation avec ${<?= json_encode($interlocuteur['prenom']) ?>} — PeerLearn`;
            }
        }
        
        if (data.typing) {
            typingIndicator.classList.remove('hidden');
            setTimeout(() => typingIndicator.classList.add('hidden'), 3000);
        }
    } catch (error) {
        console.error('Polling error:', error);
    }
}

const pollInterval = setInterval(pollMessages, 3000);

document.addEventListener('visibilitychange', () => {
    pollingActive = !document.hidden;
    if (pollingActive) pollMessages();
});

// ==================== Menu contextuel ====================
let currentMenuMessageId = null;
let currentMenuIsMine = false;

function showContextMenu(event, messageId, isMine) {
    event.stopPropagation();
    currentMenuMessageId = messageId;
    currentMenuIsMine = isMine;
    
    const menu = document.getElementById('context-menu');
    menu.innerHTML = '';
    
    if (!isMine) {
        const reportItem = document.createElement('div');
        reportItem.className = 'context-menu-item danger';
        reportItem.innerHTML = '⚠️ Signaler le message';
        reportItem.onclick = () => reportMessage(messageId);
        menu.appendChild(reportItem);
    }
    
    const deleteItem = document.createElement('div');
    deleteItem.className = 'context-menu-item danger';
    deleteItem.innerHTML = '🗑️ Supprimer le message';
    deleteItem.onclick = () => deleteMessage(messageId, isMine);
    menu.appendChild(deleteItem);
    
    menu.style.display = 'block';
    menu.style.left = event.pageX + 'px';
    menu.style.top = event.pageY + 'px';
    
    setTimeout(() => {
        document.addEventListener('click', closeContextMenu);
    }, 0);
}

function closeContextMenu() {
    const menu = document.getElementById('context-menu');
    menu.style.display = 'none';
    document.removeEventListener('click', closeContextMenu);
}

async function reportMessage(messageId) {
    if (!confirm('Signaler ce message comme inapproprié ?')) return;
    
    try {
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch(`${APP_URL}/?url=signaler-msg`, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.ok) {
            alert('Message signalé avec succès');
            closeContextMenu();
        } else {
            alert('Erreur lors du signalement');
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

async function deleteMessage(messageId, isMine) {
    if (!confirm('Supprimer ce message ?')) return;
    
    try {
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch(`${APP_URL}/?url=supprimer-msg`, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.ok) {
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (messageElement) messageElement.remove();
            closeContextMenu();
        } else {
            alert('Erreur lors de la suppression');
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// ==================== Thème ====================
function toggleTheme() {
    document.body.classList.toggle('dark');
    const isDark = document.body.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeIcon(isDark);
}

function updateThemeIcon(isDark) {
    const moonIcon = `<svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>`;
    
    const sunIcon = `<svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>`;
    
    const themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) {
        themeBtn.innerHTML = isDark ? sunIcon : moonIcon;
    }
}

function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.body.classList.add('dark');
        updateThemeIcon(true);
    } else {
        document.body.classList.remove('dark');
        updateThemeIcon(false);
    }
}

initTheme();

// ==================== Nettoyage ====================
window.addEventListener('beforeunload', () => {
    clearInterval(pollInterval);
});
</script>

</body>
</html>