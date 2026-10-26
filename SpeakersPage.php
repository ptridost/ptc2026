<?php 
    $page_title = 'Philippine Textile Congress 2024 - Speakers';
    include 'header_includes.php'; 
?>
    <div id="progress">
        <span id="progress-value">&#x1F815;</span>
    </div>
    
    <div class="container-fluid speakers-hero">
        <div class="wrapper">
            <div class="heading"> 
                <span data-heading="2">2</span><span data-heading="0">0</span><span data-heading="2">2</span><span data-heading="4">4</span> 
            </div>
        </div>
        <h1 class="speakers-hero__title">Featured Expert Speakers</h1>
    </div>

    <section class="speaker-grid">
        <div class="container-lg">
            <div class="speakers-intro">
                <h1>Say hello to our speakers</h1>
                <h2>We couldn't be more excited about our lineup. Which talks will you be checking out?</h2>
            </div>
            <div class="row g-4 justify-content-center">
                <?php
                    $sql_rc = mysqli_query($dbcon, "SELECT DISTINCT speaker_Img FROM tblspeakerses WHERE year = '2024' AND speaker_Img NOT IN ('placeholder.jpg','placeholder1.jpg') ORDER BY session_id, speaker_id");
                    while($sql_rc_row = mysqli_fetch_array($sql_rc)){
                        
                        // First, we build the full path to the image file
                        $image_path = './User_Dashboard/2024/' . $sql_rc_row['speaker_Img'];

                        // Then, we check if that file actually exists before showing anything
                        if (file_exists($image_path)) {
                ?>
                <div class="col-lg-4 col-md-6 hidden">
                    <a href="#" class="speaker-item">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" class="speaker-item__image" alt="Speaker Portrait" >
                    </a>
                </div>
                <?php  }
                } ?>
            </div>
        </div>
    </section>

    <?php include("header-footer/navbar.php" );?>
    <?php include("header-footer/footer.php" );?>
    <script>
    let calcScrollValue = () => {
        let scrollProgress = document.getElementById("progress");
        let progressValue = document.getElementById("progress-value");
        let pos = document.documentElement.scrollTop;
        let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        
        let scrollValue = Math.round((pos * 100) / calcHeight);
        
        if (pos > 100) {
            scrollProgress.style.display = "grid";
        } else {
            scrollProgress.style.display = "none";
        }
        
        scrollProgress.addEventListener("click", () => {
            document.documentElement.scrollTop = 0;
            document.body.scrollTop = 0; // For Safari support
        });
        
        // This line applies the gradient that the CSS will use
        scrollProgress.style.background = `conic-gradient(#163180 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
    };

    window.onscroll = calcScrollValue;
    window.onload = calcScrollValue;
</script>
  </body>
</html>