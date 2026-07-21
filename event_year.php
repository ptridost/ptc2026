<?php

/**
 * Read one application setting from the existing variables table.
 */
function getEventSetting(mysqli $dbcon, string $name, ?string $default = null): ?string
{
    $stmt = $dbcon->prepare('SELECT value FROM variables WHERE name = ? LIMIT 1');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row['value'] ?? $default;
}

/**
 * Return every year represented by a session, newest first.
 */
function getAvailableEventYears(mysqli $dbcon): array
{
    $years = [];
    $result = $dbcon->query(
        'SELECT DISTINCT YEAR(session_date) AS event_year
         FROM tblptcsession
         WHERE session_date IS NOT NULL
         ORDER BY event_year DESC'
    );

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['event_year'])) {
                $years[] = (int) $row['event_year'];
            }
        }
    }

    return $years;
}

/**
 * Read the configured current year, falling back to the latest session year.
 */
function getCurrentEventYear(mysqli $dbcon, string $congressType = 'PTC'): int
{
    $settingName = strtoupper($congressType) === 'GTC'
        ? 'current_gtc_year'
        : 'current_ptc_year';
    $configuredYear = getEventSetting($dbcon, $settingName);

    if ($configuredYear !== null && preg_match('/^\d{4}$/', $configuredYear)) {
        return (int) $configuredYear;
    }

    $availableYears = getAvailableEventYears($dbcon);
    return $availableYears[0] ?? (int) date('Y');
}

/**
 * Accept a requested year only when it exists in the session data.
 */
function resolveEventYear(mysqli $dbcon, mixed $requestedYear, string $congressType = 'PTC'): int
{
    $availableYears = getAvailableEventYears($dbcon);
    $requestedYear = filter_var($requestedYear, FILTER_VALIDATE_INT);

    if ($requestedYear !== false && in_array($requestedYear, $availableYears, true)) {
        return $requestedYear;
    }

    $currentYear = getCurrentEventYear($dbcon, $congressType);
    if (in_array($currentYear, $availableYears, true)) {
        return $currentYear;
    }

    return $availableYears[0] ?? $currentYear;
}

function getCertificateEligibilityRules(mysqli $dbcon, int $year): array
{
    $stmt = $dbcon->prepare(
        "SELECT COUNT(DISTINCT session_number) AS total
         FROM tblptcsession
         WHERE YEAR(session_date) = ?"
    );
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $totalSessions = (int) ($row['total'] ?? 0);
    $minimumAttendance = max(1, (int) getEventSetting(
        $dbcon,
        'certificate_min_sessions_' . $year,
        '6'
    ));

    $legacyFeedbackRequired = filter_var(
        getEventSetting($dbcon, 'certificate_feedback_required_' . $year, $year === 2025 ? '1' : '0'),
        FILTER_VALIDATE_BOOLEAN
    );
    $minimumFeedback = max(0, (int) getEventSetting(
        $dbcon,
        'certificate_min_feedback_' . $year,
        $legacyFeedbackRequired ? '1' : '0'
    ));

    return [
        'total_sessions' => $totalSessions,
        'attendance_mode' => 'fixed',
        'minimum_attendance' => $minimumAttendance,
        'feedback_mode' => $minimumFeedback > 0 ? 'fixed' : 'none',
        'minimum_feedback' => $minimumFeedback,
    ];
}

function getParticipantCertificateEligibility(mysqli $dbcon, int $participantId, int $year): array
{
    $rules = getCertificateEligibilityRules($dbcon, $year);

    $stmt = $dbcon->prepare(
        "SELECT
            COUNT(DISTINCT a.session_id) AS attended_count,
            COUNT(DISTINCT f.session_id) AS feedback_count
         FROM tblattendance a
         INNER JOIN tblptcsession s ON a.session_id = s.session_number
         LEFT JOIN tblfeedback f
            ON f.participant_id = a.participant_id
            AND f.session_id = a.session_id
         WHERE a.participant_id = ?
            AND YEAR(s.session_date) = ?"
    );
    $stmt->bind_param("ii", $participantId, $year);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $attendedCount = (int) ($row['attended_count'] ?? 0);
    $feedbackCount = (int) ($row['feedback_count'] ?? 0);
    $reasons = [];
    if ($attendedCount < $rules['minimum_attendance']) {
        $reasons[] = $attendedCount === 0
            ? "No session attendance record found for {$year}."
            : "You attended {$attendedCount} of the required {$rules['minimum_attendance']} sessions for {$year}.";
    }
    if ($feedbackCount < $rules['minimum_feedback']) {
        $reasons[] = $rules['minimum_feedback'] === 1
            ? "Feedback is required for the {$year} certificate."
            : "You submitted {$feedbackCount} of the required {$rules['minimum_feedback']} feedback forms for {$year}.";
    }
    return [
        'eligible' => empty($reasons),
        'reasons' => $reasons,
        'attended_count' => $attendedCount,
        'feedback_count' => $feedbackCount,
        'rules' => $rules,
    ];
}
