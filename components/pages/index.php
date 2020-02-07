<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge; chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Actuele status Laadpalen Gebouw 5 - Achmeatoren</title>

    <meta name="description" content="Bekijk de status van de Laadpalen op P5">
    <meta property="og:image" content="http://example.com/image.jpg">
    <meta property="og:site_name" content="Laadpalen P5">
    <meta property="og:title" content="Laadpalen P5">
    <meta property="og:description"content="ekijk de status van de Laadpalen op P5">
    <meta property="og:url" content="http://kanikladen.nl/index.html"> 
    <meta property="og:locale" content="nl_NL">
    
    <!-- FAVICONS -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="img/site.webmanifest">
    <link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,700&display=swap" rel="stylesheet">
</head>
<body>
    <noscript>
        <strong>We're sorry but laadpalen-leeuwarden doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app" class="container">
        
        <div class="row">
            <div class="col-xs-12 center-xs">
                <div class="section-intro">
                    <h1>Kan ik opladen?</h1>
                    <p>Hieronder is de actuele bezetting van de laadpunten onder gebouw 5 te zien. <br> De gegevens worden elke 60 seconden ververst.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="chargingspots">
                    <div class="chargingspot" v-for="chargingspot in results" :class="getClass(chargingspot)" >
                        <span class="chargingspot__identifier">{{ chargingspot.ocpi_evse_id }}</span>
                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="charging-station" class="svg-inline--fa fa-charging-station fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M120.57 224h42.39l-8.78 54.77c-1.28 4.74 2.86 9.23 8.34 9.23 2.98 0 5.85-1.37 7.42-3.74l66.93-99.28c3.3-4.99-.82-11.26-7.42-11.26h-41.22l8.28-36.28c1.45-4.76-2.66-9.43-8.28-9.43h-48.57c-4.3 0-7.93 2.78-8.5 6.51l-19.1 81c-.67 4.49 3.33 8.48 8.51 8.48zM560 128h-16V80c0-8.84-7.16-16-16-16s-16 7.16-16 16v48h-32V80c0-8.84-7.16-16-16-16s-16 7.16-16 16v48h-16c-8.84 0-16 7.16-16 16v48c0 35.76 23.62 65.69 56 75.93V372c0 15.44-12.56 28-28 28s-28-12.56-28-28v-28c0-48.53-39.47-88-88-88h-8V48c0-26.51-21.49-48-48-48H80C53.49 0 32 21.49 32 48v416H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h336c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8h-24V304h8c22.06 0 40 17.94 40 40v28c0 41.91 34.09 76 76 76s76-34.09 76-76V267.93c32.38-10.24 56-40.17 56-75.93v-48c0-8.84-7.16-16-16-16zM272 464H80V48h192v416zm256-272c0 17.64-14.36 32-32 32s-32-14.36-32-32v-16h64v16z"></path></svg>
                        <span class="chargingspot__status" v-if="isAvailable(chargingspot)">Available</span>
                        <span class="chargingspot__status" v-else>Charging</span>
                        <span class="chargingspot__duration">Huidige sessie: Coming soon...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <h2>Historische gegevens</h2>
                <div class="history-table">
                    <div class="flex-row flex-row--header">
                        <div class="flex-cell">Laadpaal</div>
                        <div class="flex-cell">Start van sessie</div>
                        <div class="flex-cell">Duratie</div>
                    </div>
                    <?php 
                        class Laadpaal {
                            public $id;
                            public $status;
                            public $timestamp;

                            public function __construct($id, $status, $timestamp) {
                                $this->id = $id;
                                $this->status = $status;
                                $this->timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
                            }
                        }

                        // ADD DB CREDENTIALS
                    
                        // Create connection
                        $conn = new mysqli($host, $user, $passwd, $database);
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM laadsessie ORDER BY unique_id ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $current = new Laadpaal($row['id'], $row['status'], $row['timestamp']); 

                            $laadpalen = array();

                            //$prevLaadpaal = $laadpalen[$current->id];
                            //$startSession = $current->timestamp;
                            echo $row['timestamp'].'<br/>';

                            do {
                                $current = new Laadpaal($row['id'], $row['status'], $row['timestamp']);
                                $prevLaadpaal = $laadpalen[$current->id];

                                if ($prevLaadpaal == NULL) {
                                    $laadpalen[$current->id] = $current;
                                    $prevLaadpaal = $current;
                                }

                                echo $prevLaadpaal->id.'<br/>';
                                if ($prevLaadpaal->status == 'Available' && $current->status == 'Occupied') {
                                    //$startSession = $current->timestamp;
                                    $laadpalen[$current->id] = $current->id;

                                    echo $row['timestamp'].'<br/>';
                                }
                                else if ($prevLaadpaal->status == 'Occupied' && $current->status == 'Available' ) {
                                    $startSession = $prevLaadpaal->timestamp;
                                    $endSession = $current->timestamp;
                                    //echo $startSession->format('Y-m-d H:i:s').'<br/>';
                                    //echo $endSession->format('Y-m-d H:i:s').'<br/>';
                                    $sessionTime = $endSession->diff($startSession);

                                    echo "<div class='flex-row'><div class='flex-cell'>".$current->id."</div><div class='flex-cell'>".$sessionTime->format('%H:%I:%S')."</div><div class='flex-cell'>" .$current->status."</div></div>";
                                }

                            } while ($row = $result->fetch_assoc());

                        } else {
                            echo "0 results";
                        }

                        $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    @@include('../utilities/utility-scripts.html')
</body>
</html>