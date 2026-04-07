<div>
    <div class="session_inner_item">
        <div class="session_header">
            <h1><?php echo htmlspecialchars(ucfirst($row["session_head"])); ?></h1>
            <p>( <?php $date = new DateTime($row["session_date"]);
                echo htmlspecialchars($date->format('F j, Y')); ?> ) <?php $day = strtotime($row["session_date"]);
                echo date("l", $day); ?></p>
        </div>

        <div class="inner_context">
            <img class="leftpic" src="<?php echo getImagePath($row['logo_image'], 'logo'); ?>" alt="Session Logo">
            <div class="right_context">
                <h2><?php echo htmlspecialchars($row["session_title"]); ?></h2>
                <h5><?php $timestamp = strtotime($row["session_time"]);
                    echo date("h:i A", $timestamp); ?> - <?php $timestamps = strtotime($row["sessionend_time"]);
                    echo date("h:i A", $timestamps); ?></h5>
            </div>
        </div>

        <div class="session-button-group">
    <?php
        // Get the current time once for all comparisons
        // The timezone is correctly set to 'Asia/Manila' in your dashboard.php
        $now = new DateTime();
        $session_date_formatted = date('Y-m-d', strtotime($row["session_date"]));
        $today_formatted = $now->format('Y-m-d');
        
        // --- STEP 1: Check if feedback is already submitted for this session ---
        // This check uses your `tblfeedback` columns and is the highest priority.
        $stmt_check = $dbcon->prepare("SELECT feedback_id FROM tblfeedback WHERE session_id = ? AND participant_id = ?");
        // Uses `session_number` from tblptcsession to match `session_id` in tblfeedback
        $stmt_check->bind_param("is", $row['session_number'], $_SESSION['participant_id']);
        $stmt_check->execute();
        $already_submitted = ($stmt_check->get_result()->num_rows > 0);
        $stmt_check->close();

        // --- STEP 2: Calculate the precise feedback window ---

        // A) Determine the DEADLINE. Check for a manual override first.
        if (!empty($row['feedback_override_deadline'])) {
            // If an override exists in the database, USE IT.
            $feedback_deadline = new DateTime($row['feedback_override_deadline']);
        } else {
            // Otherwise, fall back to the default global rule from your 'variables' table.
            $feedback_deadline = new DateTime($row['session_date'] . ' ' . ($settings['closingTimeFeedback'] ?? '23:59'));
        }
        
        // B) Determine the START TIME (using your existing logic).
        $acceptingTimeFeedbackStr = ($row['session_time'] < '12:00:00') ? ($settings['acceptingTimeFeedbackMorning'] ?? '00:00') : ($settings['acceptingTimeFeedbackAfternoon'] ?? '00:00');
        $feedback_start_time = new DateTime($row['session_date'] . ' ' . $acceptingTimeFeedbackStr);

        // --- STEP 3: Render all buttons based on the session's status ---

        // Case 1: Session is in the future
        if ($session_date_formatted > $today_formatted) {
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--primary' disabled><ion-icon name='hourglass-outline'></ion-icon>Attend Zoom Session</button>";
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--danger' disabled>Attendance</button>";
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--warning' disabled>Feedback</button>";
        } 
        // Case 2: Session was in the past
        elseif ($session_date_formatted < $today_formatted) {
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--danger' disabled><ion-icon name='hourglass-outline'></ion-icon>Session Ended</button>";
            
            if (isset($row['hasVideo']) && $row['hasVideo'] == 'true') {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--primary' onclick=\"location.href='watchsession.php?id=" . $row['session_number'] . "'\"><ion-icon name='film-outline'></ion-icon>Watch Recorded Session</button>";
            }

            // Your existing Attendance button for past sessions
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--danger' disabled>Attendance</button>";

            // The new, smart Feedback button for past sessions
            if ($already_submitted) {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--success' disabled>Feedback Submitted</button>";
            } elseif ($now > $feedback_deadline) {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--secondary' disabled>Feedback Closed</button>";
            } else {
                // This case handles when you've REOPENED an old session with an override
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--warning' onclick=\"location.href='feedbackform.php?id=" . $row["session_number"] . "'\">Feedback</button>";
            }
        } 
        // Case 3: Session is TODAY
        else {
            echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--primary' onclick=\"window.open('" . htmlspecialchars($row['sessionlink']) . "')\"><ion-icon name='videocam-outline'></ion-icon>Attend Zoom Session</button>";
            
            // Your existing attendance logic for today
            $acceptingTimeAttendanceStr = ($row['session_time'] < '12:00:00') ? ($settings['acceptingTimeAttendanceMorning'] ?? '00:00') : ($settings['acceptingTimeAttendanceAfternoon'] ?? '00:00');
            $acceptingTimeAttendance = DateTime::createFromFormat('Y-m-d H:i', $today_formatted . ' ' . $acceptingTimeAttendanceStr);
            if ($now >= $acceptingTimeAttendance) {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--danger' onclick=\"location.href='attendanceform.php?id=" . $row["session_number"] . "&sessionHeader=" . urlencode($row["session_head"]) . "'\">Attendance</button>";
            } else {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--danger' data-accept-time='" . $acceptingTimeAttendance->format('h:i a') . "' data-bs-toggle='modal' data-bs-target='#exampleModalatt'>Attendance</button>";
            }

            // The new, smart Feedback button for today
            if ($already_submitted) {
                 echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--success' disabled>Feedback Submitted</button>";
            } elseif ($now < $feedback_start_time) {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--warning' data-accept-time='" . $feedback_start_time->format('h:i a') . "' data-bs-toggle='modal' data-bs-target='#exampleModalfeed'>Feedback</button>";
            } elseif ($now > $feedback_deadline) {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--secondary' disabled>Feedback Closed</button>";
            } else {
                echo "<button type='button' class='btn-ptc btn-ptc--session-card btn-ptc--warning' onclick=\"location.href='feedbackform.php?id=" . $row["session_number"] . "'\">Feedback</button>";
            }
        }
    ?>
</div>
    </div>
</div>