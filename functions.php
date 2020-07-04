<?php

function uploadDetails($name, $time, $location,$details,$author)
{
    $event_id = createString();
    global $mysqli;
    $stmt = $mysqli->prepare(
        "INSERT INTO events (
            event_id,
            event_name,
            event_descr,
            event_time,
            event_loc,
            event_author,
            delete_flag

        )
        Values (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            0

        )"  
    
    );
    $stmt->bind_param("ssssss", $event_id, $name, $time, $location, $details, $author);
    $result = $stmt->execute();
    $stmt->close();
    return $event_id;

}
function createString(){
    $character_array = array_merge(range('A', 'Z'), range(0, 9));
    $rand_string = "";
    for ($i = 0; $i < 4; $i++) {
        $rand_string .= $character_array[rand(
            0,
            (count($character_array) - 1)
        )];
    }
    return $rand_string;
}


?>