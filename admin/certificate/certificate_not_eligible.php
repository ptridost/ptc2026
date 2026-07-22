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
        @font-face {
            font-family: "Poppins";
            src: url("../../fonts/Poppins-Regular.ttf") format("truetype");
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: "Poppins";
            src: url("../../fonts/Poppins-Bold.ttf") format("truetype");
            font-weight: 600 800;
            font-style: normal;
            font-display: swap;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f3f6fb;
            color: #172033;
            font-family: "Poppins", ui-sans-serif, sans-serif;
        }
        .card {
            width: min(620px, 100%);
            padding: 42px;
            border: 1px solid #dfe5ef;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 18px 50px rgba(23, 32, 51, 0.08);
        }
        .icon {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #fff1f2;
            color: #d92d3f;
            font-size: 30px;
            font-weight: 800;
        }
        h1 {
            margin: 0 0 10px;
            font-size: clamp(24px, 5vw, 30px);
            line-height: 1.2;
            letter-spacing: -0.02em;
            text-align: center;
        }
        .intro {
            max-width: 470px;
            margin: 0 auto;
            color: #596579;
            font-size: 16px;
            line-height: 1.55;
            text-align: center;
        }
        .summary {
            display: grid;
            gap: 12px;
            margin: 28px 0 20px;
        }
        .requirement {
            padding: 16px 18px;
            border: 1px solid #e2e7f0;
            border-radius: 12px;
            background: #f8fafc;
        }
        .requirement-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 11px;
        }
        .requirement-label { font-size: 15px; font-weight: 650; }
        .requirement-count { color: #465268; font-size: 14px; white-space: nowrap; }
        .requirement-count strong { color: #172033; font-size: 17px; }
        .progress {
            height: 7px;
            overflow: hidden;
            border-radius: 999px;
            background: #e5eaf2;
        }
        .progress-bar {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: #d92d3f;
        }
        .complete .progress-bar { background: #25855a; }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            color: #b42335;
            font-size: 13px;
            font-weight: 650;
        }
        .complete .status { color: #18724a; }
        .next-step {
            margin: 0 0 24px;
            padding: 14px 16px;
            border-left: 4px solid #e2a100;
            border-radius: 8px;
            background: #fff8e6;
            color: #624600;
            font-size: 15px;
            line-height: 1.5;
        }
        .actions { text-align: center; }
        .back-button {
            display: inline-block;
            min-width: 128px;
            padding: 12px 24px;
            border: 0;
            border-radius: 8px;
            background: #243b78;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color .2s ease, transform .2s ease;
        }
        .back-button:hover { background: #192d63; transform: translateY(-1px); }
        .back-button:focus-visible { outline: 3px solid rgba(36, 59, 120, .28); outline-offset: 3px; }
        @media (max-width: 520px) {
            body { padding: 14px; }
            .card { padding: 30px 20px; border-radius: 16px; }
            .requirement { padding: 15px; }
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="icon" aria-hidden="true">!</div>
        <h1>Not eligible for a <?= htmlspecialchars((string) $year) ?> certificate</h1>
        <p class="intro">This participant still has one or more requirements to complete for the selected year.</p>
        <section class="summary" aria-label="Certificate requirements">
            <div class="requirement <?= $remaining === 0 ? 'complete' : '' ?>">
                <div class="requirement-top">
                    <span class="requirement-label">Session attendance</span>
                    <span class="requirement-count"><strong><?= $attended ?></strong> of <?= $required ?></span>
                </div>
                <div class="progress" role="progressbar" aria-label="Session attendance progress" aria-valuemin="0" aria-valuemax="<?= $required ?>" aria-valuenow="<?= min($attended, $required) ?>">
                    <span class="progress-bar" style="width: <?= min(100, ($attended / $required) * 100) ?>%"></span>
                </div>
                <span class="status"><?= $remaining === 0 ? '&#10003; Requirement met' : $remaining . ' more needed' ?></span>
            </div>
            <?php if ($feedbackRequired > 0): ?>
                <div class="requirement <?= $feedbackRemaining === 0 ? 'complete' : '' ?>">
                    <div class="requirement-top">
                        <span class="requirement-label">Feedback forms</span>
                        <span class="requirement-count"><strong><?= $feedback ?></strong> of <?= $feedbackRequired ?></span>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Feedback form progress" aria-valuemin="0" aria-valuemax="<?= $feedbackRequired ?>" aria-valuenow="<?= min($feedback, $feedbackRequired) ?>">
                        <span class="progress-bar" style="width: <?= min(100, ($feedback / $feedbackRequired) * 100) ?>%"></span>
                    </div>
                    <span class="status"><?= $feedbackRemaining === 0 ? '&#10003; Requirement met' : $feedbackRemaining . ' more needed' ?></span>
                </div>
            <?php endif; ?>
        </section>
        <?php if ($remaining > 0 || $feedbackRemaining > 0): ?>
            <div class="next-step">
                <strong>Still needed:</strong>
                <?php if ($remaining > 0): ?><?= $remaining ?> recorded session<?= $remaining === 1 ? '' : 's' ?><?php endif; ?><?= $remaining > 0 && $feedbackRemaining > 0 ? ' and ' : '' ?><?php if ($feedbackRemaining > 0): ?><?= $feedbackRemaining ?> feedback form<?= $feedbackRemaining === 1 ? '' : 's' ?><?php endif; ?>.
            </div>
        <?php endif; ?>
        <div class="actions">
            <a class="back-button" href="<?= htmlspecialchars($backUrl) ?>">Go back</a>
        </div>
    </main>
</body>
</html>
