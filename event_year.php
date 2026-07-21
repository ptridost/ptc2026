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
