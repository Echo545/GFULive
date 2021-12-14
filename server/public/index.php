<?php
include_once("api/rest.php");
include_once("api/lib.php");

// - - - CONSTANTS - - -
$DB = connect_to_db();
$ID_LIBRARY = 1;
$ID_EHS = 2;
$ID_CAFE = 3;
$ID_DEN = 4;

$LIBRARY_OFFSET = 0;
$EHS_OFFSET = 19;
$CAFE_OFFSET = 2;
$DEN_OFFSET = 0;

$LOCATION_MAP = [$ID_LIBRARY => "Library", $ID_EHS => "EHS", $ID_CAFE => "Cafe", $ID_DEN => "Den"];

// - - - Calculate times for queries - - -
date_default_timezone_set('America/Los_Angeles');

$NOW = new DateTime();

// Beginning of day today
$todayStart = $NOW;
$todayStart -> setTime(0, 0);
$todayStart = date_format($todayStart, "m-d-Y");

// 24 hours ago
$YESTERDAY = new DateTime();
$YESTERDAY->sub(date_interval_create_from_date_string('1 day'));
$timeYesterday = date_format($YESTERDAY, "m-d-Y");

// Date time format:
// 2021-11-27 00:10:58


// - - - - Functions - - - - - - - - - -

function liveFromID($db, $id) {
    $sql = 'select device_count from counts where date_time = (select max(date_time) from counts where source_pi_id = ?)';
    return sql_request_all($db, $sql, $id);
}

// CAFE LIVE
$live_count_temp = liveFromID($DB, $ID_CAFE)[0];
if (is_array($live_count_temp)) {

    $live_cafe = $live_count_temp["device_count"];

    // Handle offset
    if ($live_cafe >= $CAFE_OFFSET) {
        $live_cafe -= $CAFE_OFFSET;
    }
}
else {
    // Default error case
    $live_cafe = -1;
}

// DEN LIVE
$live_count_temp = liveFromID($DB, $ID_DEN)[0];
if (is_array($live_count_temp)) {
    $live_den = $live_count_temp["device_count"];

    // Handle offset
    if ($live_den >= $DEN_OFFSET) {
        $live_den -= $DEN_OFFSET;
    }
}
else {
    // Default error case
    $live_den = -1;
}

// LIBRARY LIVE
$live_count_temp = liveFromID($DB, $ID_LIBRARY)[0];
if (is_array($live_count_temp)) {
    $live_library = $live_count_temp["device_count"];

    // Handle offset
    if ($live_library >= $LIBRARY_OFFSET) {
        $live_library -= $LIBRARY_OFFSET;
    }

}
else {
    // Default error case
    $live_library = -1;
}

// EHS LIVE
$live_count_temp = liveFromID($DB, $ID_EHS)[0];
if (is_array($live_count_temp)) {
    $live_ehs = $live_count_temp["device_count"];

    // Handle offset
    if ($live_ehs >= $EHS_OFFSET) {
        $live_ehs -= $EHS_OFFSET;
    }
}
else {
    // Default error case
    $live_ehs = -1;
}

$live_all_campus = $live_cafe + $live_den + $live_ehs + $live_library;

$most_busy_now = max($live_library, $live_den, $live_ehs, $live_cafe);
$least_busy_now = min($live_library, $live_den, $live_ehs, $live_cafe);
$most_busy_all_time = "";
$least_busy_all_time = "";

// PERCENTAGES
$library_percentage = (int) (($live_library / $live_all_campus) * 100);
$ehs_percentage = (int) (($live_ehs / $live_all_campus) * 100);
$cafe_percentage = (int) (($live_cafe / $live_all_campus) * 100);
$den_percentage = (int) (($live_den / $live_all_campus) * 100);

// Largest & Smallest of all time
$avgs = average_with_offsets($DB, $LIBRARY_OFFSET, $EHS_OFFSET, $CAFE_OFFSET, $DEN_OFFSET);
$largest_all_time = array_search(max($avgs), $avgs);
$smallest_all_time = array_search(min($avgs), $avgs);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>GFULive</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="icon" href="assets/icon.ico">
</head>

<body id="page-top">
    <div id="wrapper">

        <!-- NAVBAR -->
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fa fa-clock-o"></i></div>
                    <div class="sidebar-brand-text mx-3"><span>GFULive</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>

        <!-- BODY -->
        <div class="d-flex flex-column" id="content-wrapper">

            <!-- CONTENT -->
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search"></form>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">GFULive Dashboard</h3>
                    </div>

                    <!-- TEXT CARDS -->
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>MOST BUSY RIGHT NOW</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span id="most-busy-now-title"><?php echo get_string_value($most_busy_now, $live_library, $live_ehs, $live_cafe, $live_den); ?></span></div>
                                        </div>
                                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-graph-up fa-2x text-gray-300">
                                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"></path>
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>LEAST BUSY RIGHT NOW&nbsp;</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span id="least-busy-now-title"><?php echo get_string_value($least_busy_now, $live_library, $live_ehs, $live_cafe, $live_den); ?></span></div>
                                        </div>
                                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-graph-down fa-2x text-gray-300">
                                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 11.887a.5.5 0 0 0 .07-.704l-4.5-5.5a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61 4.15 5.073a.5.5 0 0 0 .704.07Z"></path>
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-info py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-info fw-bold text-xs mb-1"><span>MOST BUSY OF ALL TIME</span></div>
                                            <div class="text-dark fw-bold h5 mb-0" id="most-busy-all-title"><span><?php echo $LOCATION_MAP[$largest_all_time]; ?></span></div>
                                        </div>
                                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-graph-up fa-2x text-gray-300">
                                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"></path>
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-warning py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>LEAST BUSY ALL TIME</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span id="least-busy-all-title"><?php echo $LOCATION_MAP[$smallest_all_time]; ?></span></div>
                                        </div>
                                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-graph-down fa-2x text-gray-300">
                                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 11.887a.5.5 0 0 0 .07-.704l-4.5-5.5a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61 4.15 5.073a.5.5 0 0 0 .704.07Z"></path>
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GRAPHS -->
                    <div class="row">

                        <div class="col-lg-7 col-xl-8">
                            <div class="card shadow mb-4">

                            <!-- Line graph Today -->
                            <div class="graph-today animated--fade-in">
                                <div class="graph-today card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">
                                        <span id="selected-location-name">All Campus</span> Today <span style="font-size: 80%;">(<?php echo $todayStart; ?>)</span> </h6>
                                    <!-- DROPDOWN  -->
                                    <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-blue-400"></i></button>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                            <a class="dropdown-item" href="#" id='switch-day-today'>&nbsp;Switch to Yesterday</a>
                                        </div>
                                    </div>
                            </div>
                                </div>
                                <div class="card-body graph-today animated--fade-in">
                                    <div class="chart-area">
                                        <!-- CHART DATA: -->

                                        <?php
                                        $chart_data = get_chart_data($DB);
                                        $HOUR_LABELS = ['"12am"','"1am"','"2am"','"3am"','"4am"','"5am"','"6am"','"7am"','"8am"','"9am"','"10am"','"11am"','"12pm"','"1pm"','"2pm"','"3pm"','"4pm"','"5pm"','"6pm"','"7pm"','"8pm"','"9pm"','"10pm"','"11pm"','"11:59pm"']
                                        ?>

                                        <canvas data-bss-chart='{"type":"line",
                                            "data":{"labels":[<?php for($i = 0; $i < sizeof($chart_data); $i++){echo $HOUR_LABELS[$i]; if ($i < sizeof($chart_data) - 1){echo ",";}} ?>],
                                            "datasets":[{"label":"Count","fill":true,
                                            "data":[
                                            <?php
                                                foreach($chart_data as $d) {
                                                    echo '"' . (int)$d . '"';
                                                    if ($d != end($chart_data)) {
                                                        echo ',';
                                                    }
                                                }
                                            ?>],
                                            "backgroundColor":"rgba(78, 115, 223, 0.05)",
                                            "borderColor":"rgba(78, 115, 223, 1)"}]},
                                            "options":{"maintainAspectRatio":false,
                                            "legend":{"display":false,"labels":{"fontStyle":"normal"}},
                                            "title":{"fontStyle":"normal"},
                                            "scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)",
                                            "zeroLineColor":"rgb(234, 236, 244)",
                                            "drawBorder":false,
                                            "drawTicks":false,
                                            "borderDash":["2"],
                                            "zeroLineBorderDash":["2"],
                                            "drawOnChartArea":false},
                                            "ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],
                                            "yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)",
                                            "zeroLineColor":"rgb(234, 236, 244)",
                                            "drawBorder":false,
                                            "drawTicks":false,
                                            "borderDash":["2"],
                                            "zeroLineBorderDash":["2"]},
                                            "ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
                                        </div>
                                </div>

                            <!-- Line graph yesterday -->
                            <div class="graph-yesterday animated--fade-in">
                                <div class="graph-yesterday card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">
                                        <span id="selected-location-name">All Campus</span> Yesterday <span style="font-size: 80%;">(<?php echo $timeYesterday; ?>)</h6>
                                    <!-- DROPDOWN  -->
                                    <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-blue-400"></i></button>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in";>
                                            <a class="dropdown-item" href="#" id='switch-day-yesterday'>&nbsp;Switch to Today</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <div class="card-body graph-yesterday animated--fade-in">
                                    <div class="chart-area">
                                        <!-- CHART DATA: -->

                                        <?php
                                        $chart_data = get_yesterday_chart_data($DB);
                                        $HOUR_LABELS = ['"12am"','"1am"','"2am"','"3am"','"4am"','"5am"','"6am"','"7am"','"8am"','"9am"','"10am"','"11am"','"12pm"','"1pm"','"2pm"','"3pm"','"4pm"','"5pm"','"6pm"','"7pm"','"8pm"','"9pm"','"10pm"','"11pm"','"11:59pm"']
                                        ?>

                                        <canvas data-bss-chart='{"type":"line",
                                            "data":{"labels":[<?php for($i = 0; $i < sizeof($chart_data); $i++){echo $HOUR_LABELS[$i]; if ($i < sizeof($chart_data) - 1){echo ",";}} ?>],
                                            "datasets":[{"label":"Count","fill":true,
                                            "data":[
                                            <?php
                                                foreach($chart_data as $d) {
                                                    echo '"' . (int)$d . '"';
                                                    if ($d != end($chart_data)) {
                                                        echo ',';
                                                    }
                                                }
                                            ?>],
                                            "backgroundColor":"rgba(78, 115, 223, 0.05)",
                                            "borderColor":"rgba(78, 115, 223, 1)"}]},
                                            "options":{"maintainAspectRatio":false,
                                            "legend":{"display":false,"labels":{"fontStyle":"normal"}},
                                            "title":{"fontStyle":"normal"},
                                            "scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)",
                                            "zeroLineColor":"rgb(234, 236, 244)",
                                            "drawBorder":false,
                                            "drawTicks":false,
                                            "borderDash":["2"],
                                            "zeroLineBorderDash":["2"],
                                            "drawOnChartArea":false},
                                            "ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],
                                            "yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)",
                                            "zeroLineColor":"rgb(234, 236, 244)",
                                            "drawBorder":false,
                                            "drawTicks":false,
                                            "borderDash":["2"],
                                            "zeroLineBorderDash":["2"]},
                                            "ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
                                        </div>
                                </div>



                            </div>
                        </div>

                        <!-- PIE GRAPH -->
                        <div class="col-lg-5 col-xl-4">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">Live Busyness</h6>
                                    <div class="dropdown no-arrow">
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <!-- CHART DATA: -->
                                        <canvas data-bss-chart='{"type":"doughnut","data":{"labels":["Den","Cafe","Library","EHS"],"datasets":[{"label":"Live","backgroundColor":["#4e73df","#1cc88a","#f5c23e","#e74a3b"],"borderColor":["#ffffff","#ffffff","#ffffff","#ffffff"],"data":
                                            ["<?php echo $live_den; ?>","<?php echo $live_cafe; ?>","<?php echo $live_library; ?>","<?php echo $live_ehs; ?>"]}]},"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"}}}'></canvas></div>
                                    <div class="text-center small mt-4"><span class="me-2"><i class="fas fa-circle text-primary"></i>&nbsp;Den</span><span class="me-2"><i class="fas fa-circle text-success"></i>&nbsp;Cafe</span><span class="me-2"><i class="fas fa-circle text-info" style="color: rgb(245,194,62) !important;"></i>&nbsp;Library</span><span class="me-2"><i class="fas fa-circle text-primary" style="color: rgb(231,74,59) !important;"></i>&nbsp;EHS</span></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- PERCENTAGE LINE GRAPHS -->
                    <div class="row">
                        <!-- Bar graphs -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary fw-bold m-0">Live Percentage of Campus</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small fw-bold">EHS<span class="float-end" id="ehs-percentage-live"><?php echo $ehs_percentage; ?>%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" aria-valuenow=20% aria-valuemin="0" aria-valuemax="100" style=<?php echo "'width: "; echo $ehs_percentage; echo "%;'";?>></div>
                                    </div>
                                    <h4 class="small fw-bold">Library<span class="float-end" id="library_percentage-live"><?php echo $library_percentage; ?>%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style=<?php echo "'width: "; echo $library_percentage; echo "%;'";?>></div>
                                    </div>
                                    <h4 class="small fw-bold">Den<span class="float-end" id="den-percentage-live"><?php echo $den_percentage; ?>%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style=<?php echo "'width: "; echo $den_percentage; echo "%;'";?>></div>
                                    </div>
                                    <h4 class="small fw-bold">Cafe<span class="float-end" id="cafe-percentage-live"><?php echo $cafe_percentage; ?>%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-success" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100" style=<?php echo "'width: "; echo $cafe_percentage; echo "%;'";?>></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4"></div>
                        </div>
                        <!-- Number Cards -->
                        <div class="col">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-primary text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">All Campus</p>
                                            <p class="text-white-50 small m-0" id="total-count-num"><?php echo $live_all_campus; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-success text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">Cafe</p>
                                            <p class="text-white-50 small m-0" id="cafe-count-num"><?php echo $live_cafe; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-info text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">Den</p>
                                            <p class="text-white-50 small m-0" id="den-count-num"><?php echo $live_den; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-warning text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">Library</p>
                                            <p class="text-white-50 small m-0" id="library-count-num"><?php echo $live_library; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-danger text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">EHS</p>
                                            <p class="text-white-50 small m-0" id="ehs-count-num"><?php echo $live_ehs; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card textwhite bg-secondary text-white shadow">
                                        <div class="card-body">
                                            <p class="m-0">Coming Soon</p>
                                            <p class="text-white-50 small m-0">&nbsp;</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>GFULive 2021</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/live.js"></script>
</body>

</html>