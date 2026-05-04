<?php
// ============================================================
//  message_controller.php
//  Chemin : controllers/message_controller.php
// ============================================================
require_once BASE_PATH . '/models/message_model.php';
require_once BASE_PATH . '/models/user_model.php';
require_once BASE_PATH . '/models/notification_model.php';
require_once BASE_PATH . '/includes/auth_check.php';

$parts  = explode('/', trim($_GET['url'] ?? '', '/'));
$action = $parts[1] ?? 'inbox';

switch ($action) {

    case 'inbox':
        $user_id       = $_SESSION['user_id'];
        $conversations = get_conversations($user_id);
        $nb_non_lus    = compter_messages_non_lus($user_id);
        require_once BASE_PATH . '/views/messages/inbox.php';
        break;

    case 'conversation':
        $user_id          = $_SESSION['user_id'];
        $interlocuteur_id = (int)($_GET['user_id'] ?? 0);
        if (!$interlocuteur_id) redirect('/?url=messages/inbox');

        $interlocuteur = trouver_utilisateur_par_id($interlocuteur_id);
        if (!$interlocuteur) { $_SESSION['erreur'] = "Utilisateur introuvable."; redirect('/?url=messages/inbox'); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) redirect('/?url=message/conversation&user_id=' . $interlocuteur_id);

            $contenu       = trim($_POST['contenu'] ?? '');
            $fichier_joint = null;

            if (!empty($_FILES['fichier']['name'])) {
                $ext_ok = ['pdf','jpg','jpeg','png','webp','doc','docx'];
                $ext    = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $ext_ok) && $_FILES['fichier']['size'] <= 5 * 1024 * 1024) {
                    $nom = 'pj_' . time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['fichier']['tmp_name'], UPLOAD_DIR . 'pieces_jointes/' . $nom)) {
                        $fichier_joint = $nom;
                    }
                }
            }

            if ($contenu || $fichier_joint) {
                envoyer_message($user_id, $interlocuteur_id, $contenu ?: '📎 Fichier joint', $fichier_joint);
                creer_notification($interlocuteur_id, 'nouveau_message', 'Nouveau message',
                    $_SESSION['nom'] . ' vous a envoye un message.',
                    '/?url=message/conversation&user_id=' . $user_id);
            }
            redirect('/?url=messages/conversation&user_id=' . $interlocuteur_id);
        }

        marquer_messages_lus($user_id, $interlocuteur_id);
        $messages = get_conversation($user_id, $interlocuteur_id);
        require_once BASE_PATH . '/views/messages/conversation.php';
        break;

    case 'poll':
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['erreur'=>'non_connecte']); exit; }

        $user_id          = $_SESSION['user_id'];
        $interlocuteur_id = (int)($_GET['user_id']    ?? 0);
        $dernier_id       = (int)($_GET['dernier_id'] ?? 0);

        if (!$interlocuteur_id) {
            echo json_encode(['nb_non_lus' => compter_messages_non_lus($user_id)]);
            exit;
        }

        marquer_messages_lus($user_id, $interlocuteur_id);
        $nouveaux = get_nouveaux_messages($user_id, $interlocuteur_id, $dernier_id);
        $html     = '';

        foreach ($nouveaux as $msg) {
            $est_moi  = ($msg['envoyeur_id'] == $user_id);
            $contenu_e = e($msg['contenu']);
            $heure    = date('H:i', strtotime($msg['date_envoi']));
            $initiale = strtoupper(substr($msg['envoyeur_prenom'], 0, 1));

            if ($est_moi) {
                $html .= "<div class='flex justify-end mb-3' data-msg-id='{$msg['id']}'><div class='max-w-xs lg:max-w-md'><div class='bg-violet text-white px-4 py-2.5 rounded-2xl rounded-tr-sm text-sm'>{$contenu_e}</div><p class='text-xs text-gray-400 mt-1 text-right'>{$heure}</p></div></div>";
            } else {
                $html .= "<div class='flex gap-3 mb-3' data-msg-id='{$msg['id']}'><div class='w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0'>{$initiale}</div><div class='max-w-xs lg:max-w-md'><div class='bg-white border border-gray-100 px-4 py-2.5 rounded-2xl rounded-tl-sm text-sm text-gray-800'>{$contenu_e}</div><p class='text-xs text-gray-400 mt-1'>{$heure}</p></div></div>";
            }
            if (!empty($msg['fichier_joint'])) {
                $url_f = APP_URL . '/uploads/pieces_jointes/' . e($msg['fichier_joint']);
                $align = $est_moi ? 'justify-end' : '';
                $html .= "<div class='flex {$align} mb-1'><a href='{$url_f}' target='_blank' class='text-xs text-violet underline'>📎 Piece jointe</a></div>";
            }
        }

        echo json_encode([
            'html'       => $html,
            'nb_msgs'    => count($nouveaux),
            'dernier_id' => !empty($nouveaux) ? end($nouveaux)['id'] : $dernier_id,
            'nb_non_lus' => compter_messages_non_lus($user_id),
        ]);
        exit;

    case 'signaler':
        if (!csrf_verify()) redirect('/?url=messages/inbox');
        $message_id = (int)($_POST['message_id'] ?? 0);
        $motif      = trim($_POST['motif'] ?? 'Contenu abusif');
        if ($message_id) { signaler_message($message_id, $_SESSION['user_id'], $motif); }
        $_SESSION['succes'] = "Message signale.";
        redirect('/?url=messages/inbox');
        break;

    default:
        require_once BASE_PATH . '/views/errors/404.php';
}