<?php
// ============================================================
//  views/messages/conversation.php
//  Interface de messagerie
// ============================================================

$page_active = 'messages';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Conversation avec <?= e($interlocuteur['prenom']) ?> — <?= APP_NAME ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>

    <style>
        /* ... tous tes styles CSS ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F2F5;
            min-height: 100vh;
        }

        .chat-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #F0F2F5;
            height: 100vh;
            overflow: hidden;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            -webkit-overflow-scrolling: touch;
        }

        .chat-header {
            background: #fff;
            border-bottom: 1px solid #E8ECEF;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .btn-back {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #F0F2F5;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #374151;
            flex-shrink: 0;
            cursor: pointer;
        }

        .avatar-header {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .avatar-header-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5B4FE8, #8B5CF6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
        }

        .message-row {
            display: flex;
            width: 100%;
        }
        .message-row.mine {
            justify-content: flex-end;
        }
        .message-row.theirs {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 75%;
            display: flex;
            flex-direction: column;
        }

        .bubble {
            padding: 8px 14px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .bubble.mine {
            background: #5B4FE8;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .bubble.theirs {
            background: #fff;
            color: #111827;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 10px;
            color: #9CA3AF;
            margin-top: 4px;
            margin-left: 4px;
            margin-right: 4px;
        }
        .message-row.mine .message-time {
            text-align: right;
        }

        .date-sep {
            text-align: center;
            margin: 16px 0;
        }
        .date-sep span {
            font-size: 11px;
            color: #6B7280;
            background: #E8ECEF;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .input-area {
            background: #fff;
            border-top: 1px solid #E8ECEF;
            padding: 10px 16px;
            padding-bottom: max(10px, env(safe-area-inset-bottom));
            flex-shrink: 0;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F0F2F5;
            border-radius: 25px;
            padding: 4px 8px;
        }

        #message-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 8px;
            font-size: 15px;
            font-family: inherit;
            resize: none;
            outline: none;
            max-height: 100px;
        }

        #message-input::placeholder {
            color: #9CA3AF;
        }

        .send-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #5B4FE8;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }

        .send-btn:active {
            transform: scale(0.95);
        }

        .send-btn:disabled {
            opacity: 0.5;
        }

        .messages-container::-webkit-scrollbar {
            width: 4px;
        }
        .messages-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .messages-container::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .chat-header {
                position: sticky;
                top: 0;
            }
            .message-bubble {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<div class="chat-page">
    <div class="chat-content">

        <!-- Header conversation -->
        <div class="chat-header">
            <a href="<?= APP_URL ?>/messages" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <?php if (!empty($interlocuteur['photo'])): ?>
                <img src="<?= APP_URL ?>/uploads/avatars/<?= e($interlocuteur['photo']) ?>" class="avatar-header" alt="">
            <?php else: ?>
                <div class="avatar-header-placeholder">
                    <?= strtoupper(substr($interlocuteur['prenom'], 0, 1)) ?>
                </div>
            <?php endif; ?>

            <div class="flex-1">
                <p class="font-semibold text-gray-900">
                    <?= e($interlocuteur['prenom'] . ' ' . $interlocuteur['nom']) ?>
                </p>
                <p class="text-xs text-green-500">
                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                    En ligne
                </p>
            </div>
        </div>

        <!-- Messages -->
        <div class="messages-container" id="messagesContainer">
            <div id="messageList">
                <?php if (empty($messages)): ?>
                    <div class="flex flex-col items-center justify-center py-20">
                        <i class="fa-regular fa-comment text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-400">Aucun message</p>
                    </div>
                <?php endif; ?>
            </div>
            <div id="scrollAnchor"></div>
        </div>

        <!-- Zone de saisie -->
        <div class="input-area">
            <div class="input-wrapper">
                <textarea id="message-input" rows="1" placeholder="Écrire un message..."></textarea>
                <button class="send-btn" id="sendBtn">
                    <i class="fa-solid fa-paper-plane text-white text-sm"></i>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
// Variables
const userId = <?= json_encode(getUserId()) ?>;
const interlocuteurId = <?= json_encode((int)$interlocuteur['id']) ?>;
const appUrl = "<?= APP_URL ?>";
const csrfToken = "<?= csrf_generate() ?>";
let lastMessageId = 0;

// Set pour éviter les doublons
const displayedMessageIds = new Set();

// Format heure
function formatTime(dateString) {
    if (!dateString) return '';
    const parts = String(dateString).split(' ');
    if (parts.length >= 2) {
        return parts[1].substring(0, 5);
    }
    return '';
}

// DOM
const messagesContainer = document.getElementById('messagesContainer');
const messageList = document.getElementById('messageList');
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('sendBtn');
const scrollAnchor = document.getElementById('scrollAnchor');

// Fonctions d'ajout
function addDateSeparator(date) {
    const div = document.createElement('div');
    div.className = 'date-sep';
    div.innerHTML = `<span>${escapeHtml(date)}</span>`;
    messageList.appendChild(div);
}

function addSystemMessage(text) {
    const div = document.createElement('div');
    div.className = 'date-sep';
    div.innerHTML = `<span>${escapeHtml(text)}</span>`;
    messageList.appendChild(div);
}

function addMessage(msg, isMine) {
    if (displayedMessageIds.has(msg.id)) {
        return;
    }

    displayedMessageIds.add(msg.id);

    const time = formatTime(msg.date_envoi);
    const content = escapeHtml(msg.contenu).replace(/\n/g, '<br>');

    const row = document.createElement('div');
    row.className = `message-row ${isMine ? 'mine' : 'theirs'}`;
    row.setAttribute('data-id', msg.id);

    row.innerHTML = `
        <div class="message-bubble">
            <div class="bubble ${isMine ? 'mine' : 'theirs'}">
                ${content}
            </div>
            <div class="message-time">${time}</div>
        </div>
    `;

    messageList.appendChild(row);
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function scrollToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// 🔥 Fonction d'envoi de message (déclarée globalement)
async function sendMessage() {
    const content = messageInput.value.trim();
    if (!content) return;

    sendBtn.disabled = true;

    const tempId = 'temp-' + Date.now();
    const now = new Date();
    const currentTime = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

    const tempMsg = {
        id: tempId,
        contenu: content,
        date_envoi: `2026-01-01 ${currentTime}:00`,
        envoyeur_id: userId
    };

    addMessage(tempMsg, true);

    messageInput.value = '';
    messageInput.style.height = 'auto';

    try {
        const response = await fetch(`${appUrl}/?url=envoyer-msg`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                csrf_token: csrfToken,
                destinataire_id: interlocuteurId,
                contenu: content
            })
        });
        const data = await response.json();

        if (data.ok && data.message) {
            const tempRow = document.querySelector(`[data-id="${tempId}"]`);
            if (tempRow) {
                tempRow.setAttribute('data-id', data.message.id);
                displayedMessageIds.add(data.message.id);
                if (data.message.id > lastMessageId) {
                    lastMessageId = data.message.id;
                }
            }
        } else {
            const tempRow = document.querySelector(`[data-id="${tempId}"]`);
            if (tempRow) tempRow.remove();
            displayedMessageIds.delete(tempId);
            alert(data.erreur || "Erreur d'envoi");
        }
    } catch (error) {
        const tempRow = document.querySelector(`[data-id="${tempId}"]`);
        if (tempRow) tempRow.remove();
        displayedMessageIds.delete(tempId);
        alert('Erreur réseau');
    } finally {
        sendBtn.disabled = false;
        messageInput.focus();
    }
}

// 🔥 Attacher l'événement au bouton
sendBtn.addEventListener('click', sendMessage);

// Auto-resize
messageInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});

// Envoi avec Entrée
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// 🔥 Chargement des messages PHP existants
<?php if (!empty($messages)): ?>
    <?php
    $last_date = '';
    foreach ($messages as $msg):
        $is_mine = ($msg['envoyeur_id'] == getUserId());
        $msg_date = date('Y-m-d', strtotime($msg['date_envoi']));
        $is_system = isset($msg['est_systeme']) && $msg['est_systeme'] == 1;

        if ($msg_date != $last_date && !$is_system):
            $last_date = $msg_date;
            $today = date('Y-m-d');
            if ($msg_date == $today) $display_date = "Aujourd'hui";
            elseif ($msg_date == date('Y-m-d', strtotime('-1 day'))) $display_date = "Hier";
            else $display_date = date('d/m/Y', strtotime($msg_date));
    ?>
    addDateSeparator("<?= addslashes($display_date) ?>");
    <?php
        endif;
        if ($is_system):
    ?>
    addSystemMessage("<?= addslashes($msg['contenu']) ?>");
    <?php else:
    ?>
    if (<?= $msg['id'] ?> > lastMessageId) lastMessageId = <?= $msg['id'] ?>;
    addMessage({
        id: <?= $msg['id'] ?>,
        contenu: "<?= addslashes($msg['contenu']) ?>",
        date_envoi: "<?= $msg['date_envoi'] ?>",
        envoyeur_id: <?= $msg['envoyeur_id'] ?>
    }, <?= $is_mine ? 'true' : 'false' ?>);
    <?php
        endif;
    endforeach;
    ?>
<?php endif; ?>

// Polling
let pollingInterval = setInterval(async () => {
    try {
        const response = await fetch(`${appUrl}/?url=poll&avec=${interlocuteurId}&last_id=${lastMessageId}`);
        const data = await response.json();

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(msg => {
                const existingMsg = document.querySelector(`[data-id="${msg.id}"]`);
                if (!existingMsg && !displayedMessageIds.has(msg.id)) {
                    const isMine = msg.envoyeur_id == userId;
                    addMessage(msg, isMine);
                    if (msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                }
            });
        }
    } catch(e) {
        console.error('Polling error:', e);
    }
}, 3000);

window.addEventListener('beforeunload', () => {
    if (pollingInterval) clearInterval(pollingInterval);
});

setTimeout(scrollToBottom, 100);
</script>

</body>
</html>
