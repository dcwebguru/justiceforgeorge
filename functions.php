<?php



function fetchEvents()
{
    global $mysqli;
    $stmt = $mysqli->prepare(
        "SELECT ei.file_full_name,
        e.event_id,
        e.event_name,
        e.event_descr,
        e.event_time,
        e.event_loc,
        e.event_author
        FROM events e
        LEFT JOIN event_images ei ON e.event_id = ei.event_id
        WHERE
        delete_flag=0
        "
    );
    $stmt->execute();
    $stmt->bind_result($image_name,$id,$name,$descr,$time,$location,$author);
    while($stmt->fetch()){
        $row[] = array(
            'image_name' => $image_name,
            'id' => $id,
            'name' => $name,
            'time' => $time,
            'location' => $location,
            'descr' => $descr,
            'author' => $author,

        );
    }
    $stmt->close();
    return ($row);

}




function uploadDetails($name, $time, $location,$details,$author)
{
    $event_id = createString();
    global $mysqli;
    $stmt = $mysqli->prepare(
        "INSERT INTO events (
            event_id,
            event_name,
            event_time,
            event_loc,
            event_descr,
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