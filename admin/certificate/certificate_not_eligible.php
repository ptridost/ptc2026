<?php
$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);
$attended = filter_input(INPUT_GET, 'attended', FILTER_VALIDATE_INT);
$required = filter_input(INPUT_GET, 'required', FILTER_VALIDATE_INT);
$feedback = filter_input(INPUT_GET, 'feedback', FILTER_VALIDATE_INT);
$feedbackRequired = filter_input(INPUT_GET, 'feedback_required', FILTER_VALIDATE_INT);
$participantId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$source = filter_input(INPUT_GET, 'source');

$year = $year ?: (int) date('Y');
$attended = ($attended !== false && $attended !== null) ? max(0, $attended) : 0;
$required = ($required !== false && $required !== null) ? max(1, $required) : 6;
$feedback = ($feedback !== false && $feedback !== null) ? max(0, $feedback) : 0;
$feedbackRequired = ($feedbackRequired !== false && $feedbackRequired !== null) ? max(0, $feedbackRequired) : 0;
$remaining = max(0, $required - $attended);
$feedbackRemaining = max(0, $feedbackRequired - $feedback);
$backUrl = '../../User_dashboard/dashboard.php';
if ($source === 'admin' && $participantId) {
    $backUrl = '../participant_profile.php?id=' . urlencode((string) $participantId)
        . '&attendance_year=' . urlencode((string) $year);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate Not Available</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f5f7fb;
            color: #172033;
            font-family: Arial, Helvetica, sans-serif;
        }
        .card {
            width: min(560px, 100%);
            padding: 40px;
            border: 1px solid #e1e6ef;
            border-radius: 16px;
            background: #fff;
            text-align: center;
            box-shadow: 0 12px 35px rgba(23, 32, 51, 0.09);
        }
        .icon {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #fff0f1;
            color: #dc3545;
            font-size: 34px;
            font-weight: bold;
        }
        h1 { margin: 0 0 12px; font-size: 28px; }
        p { margin: 8px 0; color: #586174; line-height: 1.55; }
        .summary {
            margin: 24px 0;
            padding: 18px;
            border-radius: 10px;
            background: #f7f9fc;
            font-size: 17px;
        }
        .summary strong { color: #172033; }
        .back-button {
            display: inline-block;
            padding: 11px 22px;
            border: 0;
            border-radius: 8px;
            background: #273c75;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
        }
        .back-button:hover { background: #1e2f5d; }
    </style>
</head>
<body>
    <main class="card">
        <div class="icon" aria-hidden="true">!</div>
        <h1>Not eligible for a <?= htmlspecialchars((string) $year) ?> certificate</h1>
        <p>This participant has not completed all certificate requirements for the selected year.</p>
        <div class="summary">
            Attended sessions: <strong><?= $attended ?></strong><br>
            Required sessions: <strong><?= $required ?></strong>
            <?php if ($feedbackRequired > 0): ?>
                <br>Feedback submitted: <strong><?= $feedback ?></strong><br>
                Required feedback: <strong><?= $feedbackRequired ?></strong>
            <?php endif; ?>
        </div>
        <?php if ($remaining > 0): ?>
            <p><?= $remaining ?> more recorded session<?= $remaining === 1 ? '' : 's' ?> would be required.</p>
        <?php endif; ?>
        <?php if ($feedbackRemaining > 0): ?>
            <p><?= $feedbackRemaining ?> more feedback form<?= $feedbackRemaining === 1 ? '' : 's' ?> must be submitted.</p>
        <?php endif; ?>
        <a class="back-button" href="<?= htmlspecialchars($backUrl) ?>">Go back</a>
    </main>
</body>
</html>
