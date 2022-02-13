<?php

if (!is_dir('cities')) {
    mkdir('cities', 0777);
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "world";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name FROM countries";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()):

        $filename = str_pad($row['id'], 3, "0", STR_PAD_LEFT) . '.sql';

        $fileHandler = fopen('cities/' . $filename, "a+");

        $city_sql = 'SELECT `id`, `name`, `state_id`, `country_id`, `type`, `native`, `latitude`,
       `longitude`, `enabled`, NULL AS `created_by`, NULL AS `updated_by`,
       NULL AS `deleted_by`, `created_at`, `updated_at`, NULL AS `deleted_at` 
        FROM `cities` WHERE `country_id` = ' . $row['id'] . ';';

        $city_result = $conn->query($city_sql);

        if ($city_result->num_rows > 0) :

            $cities = $city_result->fetch_all(MYSQLI_ASSOC);

            $cities = array_chunk($cities, 1000);

            foreach ($cities as $blockIndex => $cityBlock):
                fwrite($fileHandler, "\n-- BLOCK : " . ($blockIndex + 1) . "\n");

                fwrite($fileHandler, "INSERT INTO `cities`(`id`, `name`, `state_id`, `country_id`, `type`, `native`, `latitude`,`longitude`, `enabled`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES \n");

                $data = "";

                $cityCount = count($cityBlock);

                foreach ($cityBlock as $index => $city):

                    $line = "({$city['id']}, '" . addslashes($city['name']) . "', {$city['state_id']}, {$city['country_id']}, ";
                    $line .= ($city['type'] == null) ? "NULL, " : (addslashes($city['type']) . ", ");
                    $line .= "'" . addslashes($city['native']) . "', '{$city['latitude']}', '{$city['longitude']}', '{$city['enabled']}', ";
                    $line .= "NULL, NULL, NULL, '{$city['created_at']}', '{$city['updated_at']}', NULL)";
                    $line .= ($index < ($cityCount - 1)) ? ",\n" : ";\n";

                    fwrite($fileHandler, $line);
                endforeach;

            endforeach;

            $city_result->free_result();
        endif;

    endwhile;
} else {
    echo "0 results";
}
$conn->close();