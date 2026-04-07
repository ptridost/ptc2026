<?php
// File: admin/adminSessionCards.php
?>
<div class="session_inner_item h-100 d-flex flex-column">
    <div class="session_header">
        <h1><?php echo htmlspecialchars($row["session_head"]);?></h1>
        <p>(<?php echo date('F j, Y', strtotime($row["session_date"])); ?>)
            <?php echo date("l", strtotime($row["session_date"])); ?></p>
    </div>
    <div class="inner_context">
        <img class="leftpic" src="<?php echo getImagePath($row['logo_image'], 'logo'); ?>" alt="Session Logo">
        <div class="right_context">
            <h2><?php echo htmlspecialchars($row["session_title"]); ?></h2>
            <h5><?php echo date("h:i A", strtotime($row["session_time"])); ?> -
                <?php echo date("h:i A", strtotime($row["sessionend_time"])); ?></h5>
        </div>
    </div>
    <div class="mt-auto p-3 text-center d-flex flex-column gap-2">
        <button class="btn-admin-action btn-admin-blue" onclick='showmodal(<?php echo json_encode($row); ?>)'>
            <ion-icon name="create-outline"></ion-icon> Edit Info
        </button>
        <a href="editSpeakers.php?id=<?php echo $row["session_number"]; ?>" class="btn-admin-action btn-admin-darkblue">
            <ion-icon name="people-outline"></ion-icon> Edit Speakers
        </a>
        <button type="button" class="btn-admin-action btn-admin-green" onclick='showDeadlineModal(<?php echo json_encode($row); ?>)'>
            <ion-icon name="timer-outline"></ion-icon> Edit Feedback Deadline
        </button>
        <a href="attendancelist.php?id=<?php echo $row["session_number"]; ?>" class="btn-admin-action btn-admin-red">
            <ion-icon name="list-outline"></ion-icon> Attendance List
        </a>
        <a href="viewfeedback.php?id=<?php echo $row["session_number"]; ?>" class="btn-admin-action btn-admin-yellow">
            <ion-icon name="chatbox-ellipses-outline"></ion-icon> View Feedback
        </a>
        <a href="summaryreport.php?id=<?php echo $row["session_number"]; ?>"
            class="btn-admin-action btn-admin-darkgray">
            <ion-icon name="document-text-outline"></ion-icon> Summary Report
        </a>
        <a href="recordedsession.php?id=<?php echo $row["session_number"]; ?>"
            class="btn-admin-action btn-admin-darkgray">
            <ion-icon name="cloud-upload-outline"></ion-icon> Recorded Session
        </a>
        <button type="button" class="btn-admin-action btn-admin-red"
            onclick="prepareDelete(<?php echo $row['session_number']; ?>, '<?php echo addslashes(htmlspecialchars($row['session_head'])); ?>')">
            <ion-icon name="trash-outline"></ion-icon> Delete Session
        </button>
    </div>
</div>