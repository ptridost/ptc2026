<?php
$page_event_year = 2025; 
// This MUST be the very first line
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
require("./dbcon.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://th.bing.com/th/id/OIP.gFmJnHNp6W9CRH9d587CJgHaHa?pid=ImgDet&rs=1" />

    <title>Statistics | Philippine Textile Congress</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="./analytics2024.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
</head>

<body>
    <?php include("header&footer/navbar.php"); ?>

    <main class="container-fluid analytics-dashboard">


        <div id="progress">
            <span id="progress-value"><i class="fas fa-arrow-up"></i></span>
        </div>
        <div class="dashboard-control-bar">
            <div class="control-bar__title">
                <h1>Event Analytics</h1>
                <p class="lead">Select a year and session to view detailed participant demographics.</p>
            </div>

            <div class="btn-group year-selector" role="group" aria-label="Year Selection">
                <button type="button" class="btn year-btn btn-year-2023" data-year="2023">2023</button>
                <button type="button" class="btn year-btn btn-year-2024" data-year="2024">2024</button>
                <button type="button" class="btn year-btn btn-year-2025 active" data-year="2025">2025</button>
            </div>

            <div class="session-selector">
                <button class="selector-arrow" id="btnLeft" aria-label="Previous Session"><i
                        class="fas fa-chevron-left"></i></button>
                <div class="dropdown">
                    <button class="selector-button dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Loading...
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="session_list">
                    </ul>
                </div>
                <button class="selector-arrow" id="btnRight" aria-label="Next Session"><i
                        class="fas fa-chevron-right"></i></button>
            </div>

            <!--
            <div class="control-bar__actions">
                <a href="#" id="summaryButton" class="btn btn-download" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i>
                    Generate Summary
                </a>
            </div>
            -->

        </div>
        </div>


        <div class="row g-4 mb-4 kpi-cards-row">
            <div class="col-12 col-md-4">
                <div class="dashboard-card kpi-card h-100 kpi-card--blue">
                    <h2 class="card-title" id="count_graph">Loading...</h2>
                    <p class="card-subtitle" id="count_graph_subtext"></p>
                    <div class="count-display" id="count_graph_value">...</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="dashboard-card kpi-card h-100 kpi-card--red">
                    <h2 class="card-title">Countries Represented</h2>
                    <div class="row align-items-center h-100">
                        <div class="col-5 kpi-card__main-metric">
                            <div class="count-display" id="country_count_value">...</div>
                            <span>Countries</span>
                        </div>
                        <div class="col-7 kpi-card__top-list">
                            <ol id="country_top3_list"></ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="dashboard-card kpi-card h-100 kpi-card--yellow">
                    <h2 class="card-title">Top Profession</h2>
                    <div class="row align-items-center h-100">
                        <div class="col-5 kpi-card__main-metric">
                            <div class="count-display" id="profession_count_value">...</div>
                            <span>Professions</span>
                        </div>
                        <div class="col-7 kpi-card__top-list">
                            <ol id="profession_top3_list"></ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="dashboard-card h-100">
                    <h2 class="card-title">Gender</h2>
                    <div class="chart-container">
                        <canvas id="graphCanvasGender"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="dashboard-card h-100">
                    <h2 class="card-title">Age</h2>
                    <div class="chart-container">
                        <canvas id="graphCanvasAge"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="dashboard-card h-100">
                    <h2 class="card-title">Motivation for Attending</h2>
                    <div class="chart-container">
                        <canvas id="graphCanvasMotivation"></canvas>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="dashboard-card">
                        <h2 class="card-title">Yearly Registrant Trend</h2>
                        <div class="chart-container-bar-large">
                            <canvas id="graphCanvasYearlyTrend"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="dashboard-card">
                    <h2 class="card-title">Professions</h2>
                    <div class="chart-container-bar-large">
                        <canvas id="graphCanvasProfession"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="dashboard-card">
                    <h2 class="card-title">Locations (by Country)</h2>
                    <div class="chart-container-bar-large">
                        <canvas id="graphCanvasLocation"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="dashboard-card">
                    <h2 class="card-title">How Participants Learned about the Philippine Textile Congress</h2>
                    <div class="chart-container-bar-large">
                        <canvas id="graphCanvasHowLearned"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="dashboard-card summary-card">
                    <h2 class="card-title">Data Summary</h2>
                    <p class="summary-text" id="summaryText">Select a year and session to generate a summary.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button class="toggle-tables-btn">Show Tabular Data</button>
        </div>
        <div class="tables-wrapper">
            <div class="dashboard-card">
                <h2 class="card-title" id="data_count_title">Loading...</h2>
                <div class="table-responsive">
                    <table class="table table-bordered count-table">
                        <tbody>
                            <tr>
                                <td id="data_count">...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm demographics-table">
                        <thead>
                            <tr>
                                <th id="age">Age</th>
                                <th id="gender">Gender</th>
                            </tr>
                            <tr id="data_age_gender_title_row"></tr>
                        </thead>
                        <tbody>
                            <tr id="data_age_gender_row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th id="profession" colspan="100%">Profession</th>
                            </tr>
                            <tr id="data_profession_title_row"></tr>
                        </thead>
                        <tbody>
                            <tr id="data_profession_row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th id="location" colspan="100%">Location</th>
                            </tr>
                            <tr id="data_location_title_row"></tr>
                        </thead>
                        <tbody>
                            <tr id="data_location_row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th id="howLearned" colspan="100%">How Participants Learned about the Philippine Textile
                                    Congress</th>
                            </tr>
                            <tr id="data_howLearned_title_row"></tr>
                        </thead>
                        <tbody>
                            <tr id="data_howLearned_row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th id="motivation" colspan="100%">Motivation for Attending</th>
                            </tr>
                            <tr id="data_motivation_title_row"></tr>
                        </thead>
                        <tbody>
                            <tr id="data_motivation_row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <?php include("header&footer/footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
        xintegrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="js/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/countup.js@2.0.7/dist/countUp.umd.js"></script>
    <script src="/app.js"></script>

    <script>
    $(document).ready(function() {


        // --- Global variables for analytics ---
        let chartInstances = {};
        let currentYear = 2025;
        let currentIndex = 0;

        /*$('#userDropdown, #navbarDropdown, #dropdownMenuButton').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(this);
            dropdownInstance.toggle();
        });*/
        // ===================================================================


        // --- Event Listeners for analytics controls ---
        $('.year-btn').click(function() {
            $('.year-btn').removeClass('active');
            $(this).addClass('active');
            currentYear = $(this).data('year');
            updateSessionsDropdown();
        });

        $('#btnLeft').click(function() {
            const $items = $('#session_list .dropdown-item');
            if ($items.length === 0) return;
            currentIndex = (currentIndex === 0) ? $items.length - 1 : currentIndex - 1;
            updateSelection();
            fetchData();
        });

        $('#btnRight').click(function() {
            const $items = $('#session_list .dropdown-item');
            if ($items.length === 0) return;
            currentIndex = (currentIndex === $items.length - 1) ? 0 : currentIndex + 1;
            updateSelection();
            fetchData();
        });



        $(".tables-wrapper").hide();
        $(".toggle-tables-btn").click(function() {
            $(".tables-wrapper").slideToggle("slow");
            $(this).text($(this).text() === 'Show Tabular Data' ? 'Hide Tabular Data' :
                'Show Tabular Data');
        });


        // --- Scroll-to-Top Button Logic (Final Version) ---
        const scrollProgress = document.getElementById("progress");
        if (scrollProgress) {
            scrollProgress.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        let calcScrollValue = () => {
            if (!scrollProgress) return;
            let pos = document.documentElement.scrollTop;
            let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrollValue = Math.round((pos * 100) / calcHeight);
            scrollProgress.style.display = (pos > 100) ? "grid" : "none";
            scrollProgress.style.background =
                `conic-gradient(#0E2369 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
        };
        window.onscroll = calcScrollValue;
        window.onload = calcScrollValue;

        // --- Core Functions ---
        function updateSessionsDropdown() {
            $.ajax({
                url: './graphs_2024/get_sessions.php',
                method: 'POST',
                data: {
                    year: currentYear
                },
                dataType: 'json',
                success: function(sessions) {
                    const $sessionList = $('#session_list');
                    $sessionList.empty();
                    $sessionList.append(
                        `<li class="dropdown-item" data-session-number="all">${currentYear} Registrants</li>`
                    );
                    sessions.forEach(session => {
                        $sessionList.append(
                            `<li class="dropdown-item" data-session-number="${session.session_number}">${session.session_head}</li>`
                        );
                    });

                    $('#session_list .dropdown-item').click(function() {
                        currentIndex = $('#session_list .dropdown-item').index(this);
                        updateSelection();
                        fetchData();
                    });

                    currentIndex = 0;
                    updateSelection();
                    fetchData();
                }
            });
        }

        function updateSelection() {
            const $items = $('#session_list .dropdown-item');
            if ($items.length === 0) {
                $('#dropdownMenuButton').text(`No Sessions for ${currentYear}`);
                return;
            }
            if (currentIndex >= $items.length) {
                currentIndex = 0;
            }
            const selectedItem = $items.eq(currentIndex);
            $('#dropdownMenuButton').text(selectedItem.text());
        }

        function fetchData() {
            const $items = $('#session_list .dropdown-item');
            if ($items.length === 0) return;
            const selectedItem = $items.eq(currentIndex);
            const selectedValue = selectedItem.data('session-number');

            $.ajax({
                url: './graphs_2024/get_analytics_data.php',
                method: 'POST',
                data: {
                    sessionId: selectedValue,
                    year: currentYear
                },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        updateDashboard(data, selectedValue);
                    } else {
                        Swal.fire('Error', 'Invalid data received from server.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    Swal.fire('AJAX Error', 'Failed to fetch data from the server.', 'error');
                }
            });
        }

        function groupProfessions(professionData) {
            // Define major professions to show separately
            const majorProfessions = [
                'Chemical Technician',
                'Chemical Engineer',
                'Chemist',
                'Mechanical Engineer',
                'Agricultural & Biosystems Engineer'
            ];

            const groupedData = {};
            let othersTotal = 0;

            // First, add all major professions with their counts (or 0 if not present)
            majorProfessions.forEach(profession => {
                if (professionData[profession]) {
                    groupedData[profession] = professionData[profession];
                }
            });

            // Then, sum up all other professions into "Others"
            Object.keys(professionData).forEach(profession => {
                if (profession !== 'total' && !majorProfessions.includes(profession)) {
                    othersTotal += professionData[profession];
                }
            });

            // Add Others if there are any
            if (othersTotal > 0) {
                groupedData['Others'] = othersTotal;
            }

            // Add total if it exists
            if (professionData.total) {
                groupedData.total = professionData.total;
            }

            return groupedData;
        }



        function updateDashboard(data, selectedValue) {


            console.log('Full data received:', data);
            console.log('howLearned data:', data.howLearned);
            console.log('motivation data:', data.motivation);
            const isAllRegistrants = (selectedValue === 'all');
            const duration = 1.5; // Animation duration in seconds


            // --- Update Main Count Card & Table ---
            $('#data_count_title').text(isAllRegistrants ? `${currentYear} Registrants` :
                'Participants Attended');
            $('#count_graph').text(isAllRegistrants ? 'Registrants' : 'Attendees');
            $('#count_graph_subtext').text(isAllRegistrants ? `for ${currentYear}` :
                `for "${$('#dropdownMenuButton').text().trim()}"`);
            $('td#data_count').text(data.count);

            // CORRECTED: Call the function with a lowercase 'c'
            new countUp.CountUp('count_graph_value', data.count, {
                duration: duration
            }).start();

            // --- Animate Countries KPI ---
            if (data.location) {
                const locationEntries = Object.entries(data.location).filter(([key]) => key !== 'total');
                // CORRECTED: Call the function with a lowercase 'c'
                new countUp.CountUp('country_count_value', locationEntries.length, {
                    duration: duration
                }).start();

                locationEntries.sort((a, b) => b[1] - a[1]);
                const top3Locations = locationEntries.slice(0, 3);
                let locationListHtml = '';
                top3Locations.forEach(item => {
                    locationListHtml += `<li><span>${item[0]}</span> <strong>${item[1]}</strong></li>`;
                });
                $('#country_top3_list').html(locationListHtml);
            }

            // --- Animate Professions KPI ---
            if (data.profession) {
                const groupedProfessions = groupProfessions(data.profession);
                const professionEntries = Object.entries(data.profession).filter(([key]) => key !== 'total');
                // CORRECTED: Call the function with a lowercase 'c'
                new countUp.CountUp('profession_count_value', professionEntries.length, {
                    duration: duration
                }).start();

                professionEntries.sort((a, b) => b[1] - a[1]);
                const top3Professions = professionEntries.slice(0, 3);
                let professionListHtml = '';
                top3Professions.forEach(item => {
                    professionListHtml +=
                        `<li><span>${item[0]}</span> <strong>${item[1]}</strong></li>`;
                });
                $('#profession_top3_list').html(professionListHtml);
            }

            // --- Update tables and render charts (this part is unchanged) ---
            if (data.age) {
                const ageColspan = Object.keys(data.age).filter(k => k !== 'total').length;
                $('#age').attr('colspan', ageColspan > 0 ? ageColspan : 1);
            }
            if (data.gender) {
                const genderColspan = Object.keys(data.gender).filter(k => k !== 'total').length;
                $('#gender').attr('colspan', genderColspan > 0 ? genderColspan : 1);
            }

            const downloadUrl =
                `./graphs_2024/printable_report.php?year=${currentYear}&sessionId=${selectedValue}`;
            $('#summaryButton').attr('href', downloadUrl);

            // Update all tables with data
            updateTable('age_gender', data.age, data.gender);
            updateTable('profession', groupProfessions(data.profession));
            updateTable('location', data.location);
            updateTable('howLearned', data.howLearned);
            updateTable('motivation', data.motivation);

            // Render all charts
            renderChart('gender', data.gender);
            renderChart('age', data.age);
            renderChart('profession', groupProfessions(data.profession));
            renderChart('location', data.location);
            renderChart('howLearned', data.howLearned);
            renderChart('motivation', data.motivation);

            // Generate and display the text summary
            const summary = generateSummaryText(data, selectedValue);
            $('#summaryText').html(summary);
        }


        function generateSummaryText(data, selectedValue) {
            if (!data || data.count === 0) {
                return "Not enough data to generate a summary.";
            }

            const isAllRegistrants = (selectedValue === 'all');
            const subject = isAllRegistrants ? `the <strong>${currentYear} Registrants</strong>` :
                `attendees of the selected session`;

            // Helper function to find the top category in a dataset
            const findTopCategory = (dataset) => {
                if (!dataset || Object.keys(dataset).length === 0) return 'N/A';
                return Object.keys(dataset).reduce((a, b) => dataset[a] > dataset[b] ? a : b);
            };

            const totalCount = data.count;
            const countryCount = data.location ? Object.keys(data.location).length : 0;

            const topGender = findTopCategory(data.gender);
            const topAge = findTopCategory(data.age);
            const topProfession = findTopCategory(data.profession);
            const topMotivation = findTopCategory(data.motivation);

            let summary = `For ${subject}, there are a total of <strong>${totalCount}</strong> participants`;
            if (countryCount > 0) {
                summary += ` from <strong>${countryCount}</strong> different countries. `;
            } else {
                summary += `. `;
            }

            summary +=
                `The most represented gender is <strong>${topGender}</strong>, and the largest age group is <strong>${topAge}</strong>. `;
            summary += `The most common profession is <strong>${topProfession}</strong>, `;
            summary +=
                `and the primary motivation for attending appears to be <strong>"${topMotivation}"</strong>.`;

            return summary;
        }

        function updateTable(type, ...datasets) {
            let titleHtml = '';
            let dataHtml = '';
            datasets.forEach(dataset => {
                if (dataset) {
                    for (const [key, value] of Object.entries(dataset)) {
                        if (key !== 'total') {
                            titleHtml += `<td>${key}</td>`;
                            dataHtml += `<td>${value}</td>`;
                        }
                    }
                }
            });
            $(`#data_${type}_title_row`).html(titleHtml);
            $(`#data_${type}_row`).html(dataHtml);
        }

        // --- FINAL, UPGRADED RENDER CHART FUNCTION ---
        function renderChart(type, data) {
            if (!data || Object.keys(data).filter(k => k !== 'total').length === 0) {
                if (chartInstances[type]) {
                    chartInstances[type].destroy();
                }
                return;
            }

            let labels = Object.keys(data).filter(k => k !== 'total');
            let values = labels.map(label => data[label]);

            if (type === 'profession') {
                const majorProfessions = [
                    'Chemical Technician',
                    'Chemical Engineer',
                    'Chemist',
                    'Mechanical Engineer',
                    'Agricultural & Biosystems Engineer'
                ];

                const orderedData = [];
                majorProfessions.forEach(profession => {
                    if (data[profession]) {
                        orderedData.push({
                            label: profession,
                            value: data[profession]
                        });
                    }
                });

                if (data['Others']) {
                    orderedData.push({
                        label: 'Others',
                        value: data['Others']
                    });
                }

                labels = orderedData.map(item => item.label);
                values = orderedData.map(item => item.value);
            } else {
                // Combine and sort for all bar charts
                if (['location', 'howLearned', 'motivation'].includes(type)) {
                    let combined = labels.map((label, i) => ({
                        label: label,
                        value: values[i]
                    }));
                    combined.sort((a, b) => b.value - a.value);

                    // Slice for Top 10 + Others ONLY for Location
                    if (type === 'location' && combined.length > 10) {
                        const top10 = combined.slice(0, 10);
                        const othersValue = combined.slice(10).reduce((sum, item) => sum + item.value, 0);
                        labels = top10.map(item => item.label);
                        values = top10.map(item => item.value);
                        if (othersValue > 0) {
                            labels.push('Others');
                            values.push(othersValue);
                        }
                    } else {
                        // For How Learned & Motivation, or if less than 10 items, show all
                        labels = combined.map(item => item.label);
                        values = combined.map(item => item.value);
                    }
                }
            }
            const canvasId = `graphCanvas${type.charAt(0).toUpperCase() + type.slice(1)}`;
            const context = document.getElementById(canvasId).getContext('2d');
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    labels: {
                        fontColor: '#495057',
                        fontFamily: "'MelodyRegular', sans-serif"
                    }
                },
                tooltips: {
                    backgroundColor: '#0E2369',
                    titleFontFamily: "'MelodyBold', sans-serif",
                    bodyFontFamily: "'MelodyRegular', sans-serif",
                    cornerRadius: 3,
                    displayColors: false
                }
            };

            let chartConfig = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        hoverOffset: 4
                    }]
                },
                options: defaultOptions
            };

            if (type === 'gender') {
                chartConfig.type = 'pie';
                chartConfig.data.datasets[0].backgroundColor = ['#0E2369', '#C7000D', '#6c757d'];
                chartConfig.options.legend.display = true;
            } else if (type === 'age') {
                chartConfig.type = 'doughnut';
                chartConfig.data.datasets[0].backgroundColor = ['#ffc107', '#fd7e14', '#FDD835', '#C7000D',
                    '#0E2369', '#7E9FFF'
                ];
                chartConfig.options.legend.display = true;
            } else { // This styles ALL other charts as multi-colored horizontal bar charts
                chartConfig.type = 'horizontalBar';
                chartConfig.options.legend = {
                    display: false
                };
                chartConfig.options.scales = {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#6c757d'
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: '#6c757d'
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                };

                const themeColors = [
                    '#0E2369', '#C7000D', '#507AFF', '#DAA520', '#1A3A99',
                    '#E53935', '#7E9FFF', '#FDD835', '#2F54CD', '#B71C1C'
                ];
                chartConfig.data.datasets[0].backgroundColor = themeColors;
            }

            if (chartInstances[type]) {
                chartInstances[type].destroy();
            }
            chartInstances[type] = new Chart(context, chartConfig);
        }


        function loadYearlyTrendChart() {
            $.ajax({
                url: './graphs_2024/get_yearly_trend_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const canvasId = 'graphCanvasYearlyTrend';
                    const context = document.getElementById(canvasId).getContext('2d');

                    const labels = Object.keys(data);
                    const values = Object.values(data);

                    // --- NEW, COMPATIBLE STYLING ---
                    // 1. Create the gradient background (this part is compatible with all versions)
                    const gradient = context.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0,
                        'rgba(14, 35, 105, 0.5)'); // Semi-transparent dark blue
                    gradient.addColorStop(1, 'rgba(14, 35, 105, 0)'); // Fades to transparent

                    // 2. Create a color map for the points
                    const pointColors = labels.map(year => {
                        if (year == '2023') return '#0E2369'; // Primary Blue
                        if (year == '2024') return '#FED300'; // Primary Yellow
                        if (year == '2025') return '#C7000D'; // Primary Red
                        return '#0E2369'; // Default color
                    });

                    const chartConfig = {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Registrants',
                                data: values,
                                backgroundColor: gradient, // The gradient for the area fill
                                borderColor: '#0E2369', // A solid blue for the line itself
                                borderWidth: 3,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                pointBackgroundColor: pointColors, // Use our array of point colors
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: '#6c757d'
                                    },
                                    gridLines: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                }],
                                xAxes: [{
                                    ticks: {
                                        fontColor: '#6c757d'
                                    },
                                    gridLines: {
                                        display: false
                                    }
                                }]
                            }
                        }
                    };

                    if (chartInstances['yearlyTrend']) {
                        chartInstances['yearlyTrend'].destroy();
                    }
                    chartInstances['yearlyTrend'] = new Chart(context, chartConfig);
                }
            });
        }



        // --- Initial Load ---
        updateSessionsDropdown();
        loadYearlyTrendChart();
    });
    </script>
</body>

</html>