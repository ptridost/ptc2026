<?php
// Shared presentation-only table row for admin session listings.
$sessionPayload = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
$sessionHead = htmlspecialchars($row['session_head']);
$sessionTitle = htmlspecialchars($row['session_title']);
$sessionNumber = (int) $row['session_number'];
?>
<tr class="session-row"<?php echo isset($session_year) ? ' data-session-year="' . htmlspecialchars($session_year) . '"' : ''; ?>>
    <td>
        <div class="session-table__identity">
            <img src="<?php echo getImagePath($row['logo_image'], 'logo'); ?>" alt="" class="session-table__logo">
            <div><strong><?php echo $sessionHead; ?></strong><span><?php echo $sessionTitle; ?></span></div>
        </div>
    </td>
    <td data-label="Date">
        <strong><?php echo date('M j, Y', strtotime($row['session_date'])); ?></strong>
        <span class="session-table__muted"><?php echo date('l', strtotime($row['session_date'])); ?></span>
    </td>
    <td data-label="Time">
        <?php echo date('h:i A', strtotime($row['session_time'])); ?><br>
        <span class="session-table__muted">to <?php echo date('h:i A', strtotime($row['sessionend_time'])); ?></span>
    </td>
    <td class="text-end" data-label="Actions">
        <div class="dropdown session-actions">
            <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                data-bs-boundary="viewport" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" type="button" onclick='showmodal(<?php echo $sessionPayload; ?>)'><i class="fa-solid fa-pen"></i>Edit information</button></li>
                <li><a class="dropdown-item" href="editSpeakers.php?id=<?php echo $sessionNumber; ?>"><i class="fa-solid fa-user-tie"></i>Edit speakers</a></li>
                <li><button class="dropdown-item" type="button" onclick='showDeadlineModal(<?php echo $sessionPayload; ?>)'><i class="fa-regular fa-clock"></i>Feedback deadline</button></li>
                <li><a class="dropdown-item" href="attendancelist.php?id=<?php echo $sessionNumber; ?>"><i class="fa-solid fa-clipboard-user"></i>Attendance list</a></li>
                <li><a class="dropdown-item" href="viewfeedback.php?id=<?php echo $sessionNumber; ?>"><i class="fa-regular fa-comments"></i>View feedback</a></li>
                <li><a class="dropdown-item" href="summaryreport.php?id=<?php echo $sessionNumber; ?>"><i class="fa-regular fa-file-lines"></i>Summary report</a></li>
                <li><a class="dropdown-item" href="recordedsession.php?id=<?php echo $sessionNumber; ?>"><i class="fa-solid fa-video"></i>Recorded session</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><button class="dropdown-item text-danger" type="button" onclick="prepareDelete(<?php echo $sessionNumber; ?>, '<?php echo addslashes($sessionHead); ?>')"><i class="fa-regular fa-trash-can"></i>Delete session</button></li>
            </ul>
        </div>
    </td>
</tr>
