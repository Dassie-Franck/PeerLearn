<?php
// ============================================================
//  views/layouts/notifications.php
//  Composant de notification flottant en bas à droite
// ============================================================

// S'assurer que les variables existent
if (!isset($nb_notifications_non_lues)) {
    $nb_notifications_non_lues = 0;
}
if (!isset($notifications)) {
    $notifications = [];
}
?>

<style>
/* Notification flottante en bas à droite */
.notification-floating {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 999999 !important;
}

.notification-btn {
    width: 56px;
    height: 56px;
    border-radius: 28px;
    background: linear-gradient(135deg, #5B4FE8, #7C3AED);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(91,79,232,0.3);
    transition: all 0.3s ease;
    position: relative;
}

.notification-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 28px rgba(91,79,232,0.4);
}

.notification-btn svg {
    width: 24px;
    height: 24px;
    color: white;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #EF4444;
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
    min-width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.notification-badge.hidden {
    display: none;
}

/* Dropdown notifications */
.notification-dropdown {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 380px;
    max-width: calc(100vw - 32px);
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border: 1px solid #E2E8F0;
    overflow: hidden;
    z-index: 999999 !important;
    display: none;
    opacity: 0;
    transform: scale(0.95);
    transform-origin: bottom right;
    transition: all 0.2s ease;
}

.notification-dropdown.show {
    display: block !important;
    opacity: 1 !important;
    transform: scale(1) !important;
}

.notification-header {
    padding: 16px 20px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
}

.notification-header p {
    font-size: 11px;
    color: #94A3B8;
    margin-top: 2px;
}

.notification-list {
    max-height: 450px;
    overflow-y: auto;
}

.notification-item {
    padding: 14px 18px;
    border-bottom: 1px solid #F1F5F9;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    gap: 12px;
}

.notification-item:hover {
    background: #F8FAFC;
}

.notification-item.unread {
    background: #FAF5FF;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-size: 13px;
    font-weight: 600;
    color: #0F172A;
    margin-bottom: 4px;
}

.notification-text {
    font-size: 12px;
    color: #64748B;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-time {
    font-size: 10px;
    color: #94A3B8;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.notification-dot {
    width: 8px;
    height: 8px;
    background: #5B4FE8;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 4px;
}

.empty-notifications {
    text-align: center;
    padding: 48px 20px;
}

.empty-icon {
    width: 56px;
    height: 56px;
    background: #F1F5F9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}

.empty-icon svg {
    width: 24px;
    height: 24px;
    color: #94A3B8;
}

.mark-all-btn {
    font-size: 11px;
    color: #5B4FE8;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

.mark-all-btn:hover {
    text-decoration: underline;
}

/* Animation d'entrée */
@keyframes floatIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-floating {
    animation: floatIn 0.3s ease-out;
}
</style>

<div class="notification-floating">
    <button class="notification-btn" id="notif-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
        </svg>
        
        <span id="notif-badge" class="notification-badge <?= $nb_notifications_non_lues > 0 ? '' : 'hidden' ?>">
            <?= min($nb_notifications_non_lues, 99) ?>
        </span>
    </button>

    <!-- Dropdown notifications -->
    <div id="notif-dropdown" class="notification-dropdown">
        <div class="notification-header">
            <div>
                <h3>Notifications</h3>
                <p>Restez informé de vos activités</p>
            </div>
            <?php if ($nb_notifications_non_lues > 0): ?>
            <button onclick="marquerToutesLues()" class="mark-all-btn">
                Tout marquer
            </button>
            <?php endif; ?>
        </div>

        <div class="notification-list">
            <?php if (empty($notifications)): ?>
            <div class="empty-notifications">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500">Aucune notification</p>
                <p class="text-xs text-gray-400 mt-1">Vous serez alerté des nouvelles activités</p>
            </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif):
                    $icon = match($notif['type']) {
                        'nouvelle_reservation' => ['bg' => 'bg-blue-100', 'icon' => 'fa-calendar-plus', 'color' => 'text-blue-600'],
                        'reservation_confirmee' => ['bg' => 'bg-green-100', 'icon' => 'fa-check-circle', 'color' => 'text-green-600'],
                        'reservation_annulee' => ['bg' => 'bg-red-100', 'icon' => 'fa-ban', 'color' => 'text-red-600'],
                        'nouveau_message' => ['bg' => 'bg-purple-100', 'icon' => 'fa-message', 'color' => 'text-purple-600'],
                        'nouvelle_evaluation' => ['bg' => 'bg-amber-100', 'icon' => 'fa-star', 'color' => 'text-amber-600'],
                        'profil_mentor_valide' => ['bg' => 'bg-teal-100', 'icon' => 'fa-check-circle', 'color' => 'text-teal-600'],
                        'profil_mentor_rejete' => ['bg' => 'bg-red-100', 'icon' => 'fa-times-circle', 'color' => 'text-red-600'],
                        'profil_suspendu' => ['bg' => 'bg-orange-100', 'icon' => 'fa-shield', 'color' => 'text-orange-600'],
                        'rappel_session' => ['bg' => 'bg-yellow-100', 'icon' => 'fa-bell', 'color' => 'text-yellow-600'],
                        default => ['bg' => 'bg-gray-100', 'icon' => 'fa-bell', 'color' => 'text-gray-600'],
                    };
                ?>
                <div class="notification-item <?= !$notif['lu'] ? 'unread' : '' ?>"
                     data-id="<?= $notif['id'] ?>"
                     data-lien="<?= e($notif['lien'] ?? '#') ?>">
                    <div class="notification-icon <?= $icon['bg'] ?>">
                        <i class="fa-regular <?= $icon['icon'] ?> <?= $icon['color'] ?> text-lg"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title"><?= e($notif['titre']) ?></p>
                        <p class="notification-text"><?= e($notif['contenu']) ?></p>
                        <div class="notification-time">
                            <i class="fa-regular fa-clock text-xs"></i>
                            <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                        </div>
                    </div>
                    <?php if (!$notif['lu']): ?>
                    <div class="notification-dot"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($notifications)): ?>
        <div class="border-t border-gray-100 p-3 text-center">
            <span class="text-xs text-gray-400">
                Fin des notifications
            </span>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Toggle dropdown
const notifBtn = document.getElementById('notif-btn');
const notifDropdown = document.getElementById('notif-dropdown');

if (notifBtn) {
    notifBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notifDropdown.classList.toggle('show');
    });
}

// Fermer en cliquant ailleurs
document.addEventListener('click', (e) => {
    if (notifDropdown && notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
        notifDropdown.classList.remove('show');
    }
});

// Marquer une notification comme lue au clic
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('click', function() {
        const id = this.dataset.id;
        const lien = this.dataset.lien;
        
        if (id) {
            fetch('<?= APP_URL ?>/?url=notification/marquer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + id
            });
        }
        
        if (lien && lien !== '#') {
            window.location.href = lien;
        }
    });
});

// Marquer toutes comme lues
function marquerToutesLues() {
    fetch('<?= APP_URL ?>/?url=notification/marquer-toutes', { method: 'POST' })
        .then(() => {
            location.reload();
        });
}

// Polling pour les nouvelles notifications (toutes les 15 secondes)
let lastNotifCount = <?= $nb_notifications_non_lues ?>;

setInterval(() => {
    fetch('<?= APP_URL ?>/?url=notification/poll?last_count=' + lastNotifCount)
        .then(r => r.json())
        .then(data => {
            if (data.nb_non_lus !== undefined && data.nb_non_lus !== lastNotifCount) {
                lastNotifCount = data.nb_non_lus;
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.nb_non_lus > 0) {
                        badge.textContent = data.nb_non_lus > 99 ? '99+' : data.nb_non_lus;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                
                if (data.has_new) {
                    setTimeout(() => location.reload(), 3000);
                }
            }
        })
        .catch(() => {});
}, 15000);
</script>