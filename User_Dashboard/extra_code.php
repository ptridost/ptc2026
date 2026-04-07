<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 col_item" id="sess">
      <div class="session_inner_item">
      <div class="session_header">
      <h1 style= "padding-right: 40px;"><?php echo ucfirst ($row["session_head"]);?></h1>
      <p>( <?php $date = DateTime::createFromFormat('Y-m-d',  $row["session_date"]);
      echo htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8" ); ?> ) <?php $day = strtotime($row["session_date"]);
      echo date("l", $day); ?></p>
    </div>
    <?php 
    date_default_timezone_set('Asia/Manila');
   ?>
    <div class="inner_context">
    <img class="leftpic" src="<?php echo '../landingpageimg/'.$row['logo_image']; ?>">
    <div class="right_context">
    <h2><?php echo $row["session_title"]?></h2>
      <h5 style="margin-left: 15px;"><?php $timestamp = strtotime($row["session_time"]);
      echo date("h:i A", $timestamp); ?> - <?php $timestamps = strtotime($row["sessionend_time"]);
      echo date("h:i A", $timestamps); ?></h5>
  </div>
  </div>
  
 <?php
 $closingTimeFeedback = date('20:15');
 // $closingTimeAttendance = new DateTime($row['sessionend_time']);
 // $closingTimeAttendance->modify('+15 minutes');
 // $closingTimeAttendance = $closingTimeAttendance->format('H:i'); 
 $closingTimeAttendanceMorning=date('11:45');
 $closingTimeAttendanceAfternoon=date('16:30');
 $acceptingTimeFeedbackMorning = date($settings['acceptingTimeFeedbackMorning']);
 $acceptingTimeFeedbackAfternoon = date($settings['acceptingTimeFeedbackAfternoon']);
 $acceptingTimeAttendanceMorning = date($settings['acceptingTimeAttendanceMorning']);
 $acceptingTimeAttendanceAfternoon = date($settings['acceptingTimeAttendanceAfternoon']);
 if($row['session_time'] < date('12:00')){
  $acceptingTimeFeedback = new DateTime($acceptingTimeFeedbackMorning);
  $acceptingTimeAttendance = new DateTime($acceptingTimeAttendanceMorning);
  $closingTimeAttendance = $closingTimeAttendanceMorning;
 } elseif ($row['session_time'] >= date('12:00')){
  $acceptingTimeFeedback = new DateTime($acceptingTimeFeedbackAfternoon);
  $acceptingTimeAttendance = new DateTime($acceptingTimeAttendanceAfternoon);
  $closingTimeAttendance = $closingTimeAttendanceAfternoon;
 }  
  
 // $closingTime1 = '16:00';
 // $acceptingTime1 = '8:00';
 // $acceptingTime = '8:00';
 // $closingTime = '20:00';
 // $endTime = '24:00';

  date_default_timezone_set('Asia/Manila');

      // IF SESSION IS UPCOMING
     if ( $row["session_date"] != date("Y-m-d") && $row["session_date"] >= date("Y-m-d")){
      echo '<center><button onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" data-bs-toggle="modal" data-bs-target="#exampleModal1" type="button" id="watchsess" style="background-color: #1e90ff;"><ion-icon name="hourglass-outline"></ion-icon>Attend Zoom Session</button></center>';


      echo "
      <center>
      <button type='button' id='watch' disabled><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button>
      </center>
      ";

      echo '<div class="session_btn">';

         echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModal1">Attendance</button>'; 
          
        echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModal1">Feedback</button>'; 

}
// IF SESSION IS ON SAME DAY BUT UPCOMING
// elseif (date("H:i") < $row["session_time"] && $row["session_date"] == date("Y-m-d")){
  
//   echo '<center><button onclick="getTime(this)" data-time="'.date("h:i A", $timestamp).'" data-bs-toggle="modal" data-bs-target="#exampleModaltime" type="button" id="watchsess" style="background-color: #1e90ff;"><ion-icon name="hourglass-outline"></ion-icon>Attend Zoom Session</button></center>';


//       echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Available Recorded Session</button></center>";

//       echo '<div class="session_btn">';

//          echo '<button  onclick="getTime(this)" data-time="'.date("h:i A", $timestamp).'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModaltime">Attendance</button>'; 
          
//         echo '<button  onclick="getTime(this)" data-time="'.date("h:i A", $timestamp).'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModaltime">Feedback</button>';
         
// }
// IF SESSION DAY IS DONE
elseif ($row["session_date"] < date("Y-m-d")){
  echo '<center><button onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" data-bs-toggle="modal" data-bs-target="#exampleModalend" type="button" id="watchsess" style="background-color: red;"><ion-icon name="hourglass-outline"></ion-icon>Session Ended</button></center>';

      // CHECK IF PAST SESSION HAS VIDEO
      if($row['hasVideo'] == 'true'){
         echo "<center><button onclick=\"location.href='watchsession.php?id=".$row['session_number']."'\" type='button' id='watch'><ion-icon name='film-outline'></ion-icon>Watch Recorded Session</button></center>";
       } else{
          echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button></center>";
       }
     

      echo '<div class="session_btn">';

         echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModalend">Attendance</button>'; 
          
        echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModalend">Feedback</button>';
}


// IF SESSION DAY IS ONGOING AND FEEDBACK TIME HAS PASSED
elseif (date("H:i") > $closingTimeFeedback && $row["session_date"] == date("Y-m-d")){
  echo '<center><button onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" data-bs-toggle="modal" data-bs-target="#exampleModalend" type="button" id="watchsess" style="background-color: red;"><ion-icon name="hourglass-outline"></ion-icon>Session Ended</button></center>';


      // CHECK IF PAST SESSION HAS VIDEO
      if($row['hasVideo'] == 'true'){
         echo "<center><button onclick=\"location.href='watchsession.php?id=".$row['session_number']."'\" type='button' id='watch'><ion-icon name='film-outline'></ion-icon>Watch Recorded Session</button></center>";
       } else{
          echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button></center>";
       }
      

      echo '<div class="session_btn">';

         echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModalend">Attendance</button>'; 
          
        echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModalend">Feedback</button>';
}

// IF SESSION DAY IS ONGOING AND ATTENDANCE TIME HAS PASSED BUT NOT FEEDBACK
elseif (date("H:i") > $closingTimeAttendance && $row["session_date"] == date("Y-m-d") && date("H:i") <= $closingTimeFeedback){

      echo '<center><button onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" data-bs-toggle="modal" data-bs-target="#exampleModalend" type="button" id="watchsess" style="background-color: red;"><ion-icon name="hourglass-outline"></ion-icon>Session Ended</button></center>';


      // CHECK IF PAST SESSION HAS VIDEO
      if($row['hasVideo'] == 'true'){
         echo "<center><button onclick=\"location.href='watchsession.php?id=".$row['session_number']."'\" type='button' id='watch'><ion-icon name='film-outline'></ion-icon>Watch Recorded Session</button></center>";
       } else{
          echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button></center>";
       }
      

      echo '<div class="session_btn">';

      echo '<button  onclick="getDate(this)" data-date="'.htmlspecialchars($date->format('F j, Y'), ENT_QUOTES, "UTF-8").'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModalend">Attendance</button>'; 
          
      echo '<button type="button" id="feedback1" onclick ="document.location.href='."'feedbackform.php?id=".$row["session_number"]."'".'">Feedback</button>';
}
// IF SESSION IS ONGOING
elseif ($row["session_date"] == date("Y-m-d")){

  echo "<center><button onclick=\"window.open('".$row['sessionlink']."')\" type='button' id='watchsess' style='background-color: #1e90ff;'><ion-icon name='hourglass-outline'></ion-icon>Attend Zoom Session</button></center>";
  
  // CHECK IF PAST SESSION HAS VIDEO
  if($row['hasVideo'] == 'true'){
     echo "<center><button onclick=\"location.href='watchsession.php?id=".$row['session_number']."'\" type='button' id='watch'><ion-icon name='film-outline'></ion-icon>Watch Recorded Session</button></center>";
   } else{
      echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button></center>";
   }
      echo '<div class="session_btn">';

  //CHECK IF MORNING SESSION OF ATTENDANCE
      if($row['session_time'] < date('12:00') && $acceptingTimeAttendanceMorning <= date("H:i")){
          echo '<button type="button" id="attendance1" onclick ="document.location.href='."'attendanceform.php?id=".$row["session_number"]."&sessionHeader=".$row["session_head"]."'".'">Attendance</button>'; 
      } elseif($row['session_time'] >= date('12:00') && $acceptingTimeAttendanceAfternoon <= date("H:i")){

        echo '<button type="button" id="attendance1" onclick ="document.location.href='."'attendanceform.php?id=".$row["session_number"]."&sessionHeader=".$row["session_head"]."'".'">Attendance</button>'; 
      } else {
        echo '<button  onclick="getAcceptTimeAttendance(this)" data-accept-time="'.$acceptingTimeAttendance->format('h:i a').'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModalatt">Attendance</button>'; 
      }
       
    // CHECK IF MORNING SESSION OF FEEDBACK
      if($row['session_time'] < date('12:00') && $acceptingTimeFeedbackMorning <= date("H:i")){
          echo '<button type="button" id="feedback1" onclick ="document.location.href='."'feedbackform.php?id=".$row["session_number"]."'".'">Feedback</button>';
      } elseif($row['session_time'] >= date('12:00') && $acceptingTimeFeedbackAfternoon <= date("H:i")){

        echo '<button type="button" id="feedback1" onclick ="document.location.href='."'feedbackform.php?id=".$row["session_number"]."'".'">Feedback</button>';
      } else {
        echo '<button  onclick="getAcceptTimeFeedback(this)" data-accept-time="'.$acceptingTimeFeedback->format('h:i a').'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModalfeed">Feedback</button>'; 
      }
        
}

else {
  echo "<center><button type='button' id='watchsess' style='background-color: #1e90ff;'><ion-icon name='hourglass-outline'></ion-icon>Attend Zoom Session</button></center>";

  echo "<center><button type='button' id='watch'><ion-icon name='film-outline'></ion-icon>No Recorded Session Available</button></center>";
     
  echo '<div class="session_btn">';

  echo '<button  onclick="getAcceptTimeAttendance(this)" data-accept-time="'.$acceptingTimeAttendance->format('h:i a').'" type="button" id="attendance1" data-bs-toggle="modal" data-bs-target="#exampleModalatt">Attendance</button>'; 
      
  echo '<button  onclick="getAcceptTimeFeedback(this)" data-accept-time="'.$acceptingTimeFeedback->format('h:i a').'" type="button" id="feedback1" data-bs-toggle="modal" data-bs-target="#exampleModalfeed">Feedback</button>'; 
      
      
}



        ?>



    </div>
    </div>

