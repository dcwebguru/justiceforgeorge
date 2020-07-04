<!DOCTYPE html>
<html lang="en">
<?php
require_once("head.php");
require_once("config.php");

require_once("functions.php");

// define variables and set to empty values
$name_err = $time_err = $location_err = "";
$name = $time = $location = "";
if (!empty($_POST)) {
    if (empty($_POST["evenamentName"])) {
        $name_err = "*Name is required";
    } else {
        $name = $_POST["evenamentName"];
    }
    if (empty($_POST["time"])) {
        $time_err = "*Time is required";
    } else {
        $time = $_POST["time"];
    }
    if (empty($_POST["loc"])) {
        $location_err = "*Location is required";
    } else {
        $location = $_POST["loc"];
    }
    $details = $_POST["details"];
    $author = $_POST["author"];

    if ($name_err || $time_err || $location_err == "") {
        $event_id = uploadDetails($name, $time, $location, $details, $author);

        // Image Upload 
        ############ Configuration ##############
        //$destination_folder		= 'G:/path/to/uploads/folder/'; //upload directory ends with / (slash)
        $currentfolder = getcwd();
        //echo $currentfolder;
        $destination_folder = $currentfolder . '/uploads/'; //upload directory ends with / (slash)
        //echo $destination_folder;
        define("UPLOAD_DIR", $destination_folder);

        ##########################################
        if (!isset($_FILES['eventImage']) || !is_uploaded_file($_FILES['eventImage']['tmp_name']) || $_FILES['eventImage']['error'] > 0) {
            header("Location: /upload.php?er=fm"); /* Redirect browser */
            exit();
        } else {
            $file_name = $_FILES['eventImage']['name'];
            $file_size = $_FILES['eventImage']['size'];
            $file_tmp = $_FILES['eventImage']['tmp_name'];
            $file_type = $_FILES['eventImage']['type'];
            $file_error = $_FILES['eventImage']['error'];

            //$file_ext=strtolower(end(explode('.',$file_name)));
            $path_parts = pathinfo($file_name);
            $file_basename =  strtolower($path_parts['basename']);
            $file_name_new = strtolower($path_parts['filename']);
            $file_ext = strtolower($path_parts['extension']);


            $extensions = array("png", "jpeg", "gif", "bmp", "jpg");

            if (in_array($file_ext, $extensions) === false) {
                //$errors[]="extension not allowed, please choose a .";
                header("Location: /upload.php?er=fm1"); /* Redirect browser */
                exit();
            }


            if ($file_error !== UPLOAD_ERR_OK) {
                //$errors[]='an error occurred';
                header("Location: /upload.php?er=fm2"); /* Redirect browser */
                exit();
            }

            $character_array = array_merge(range('a', 'z'), range(0, 99));
            $rand_string = "";
            for ($i = 0; $i < 8; $i++) {
                $rand_string .= $character_array[rand(0, (count($character_array) - 1))];
            }

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            // ensure a safe filename
            $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file_name_new);

            //create a random name for new image (Eg: fileName_293749.jpg) ;
            $new_file_name = $name . '_' . rand(0, 99999999999) . '.' . $file_ext;

            if (empty($errors) == true) {
                //preserve file from temporary directory
                $success = move_uploaded_file($file_tmp, UPLOAD_DIR . $new_file_name);
                // set proper permissions on the new file
                chmod(UPLOAD_DIR . $new_file_name, 0644);
                if (!$success) {
                    //$errors[] = 'unable to save file';
                    header("Location: /upload.php?er=fm3"); /* Redirect browser */
                    exit();
                }
            } else {
                // print_r($errors);
                header("Location: /upload.php?er=fm4"); /* Redirect browser */
                exit();
            }
        }
        $stmt = $mysqli->prepare(
            "INSERT INTO event_images(
              event_id,
              image_id,
              file_full_name,
              file_size,
              file_ext,
              file_name,
              ip_address,
              file_type
          )
          VALUES(
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
          )"
        );
        $stmt->bind_param("ssssssss", $event_id, $rand_string, $new_file_name, $file_size, $file_ext, $file_name_new, $ip, $file_type);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<body>

    <div class="container">
        <div class="d-flex">
            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 my-2 m-auto">
                <div class="card">

                    <form name='uploadEvent' action='<?php $_SERVER["PHP_SELF"]; ?>' method='post' enctype='multipart/form-data'>
                        <div class="card-head m-auto upload-image">
                            <input type='file' name='eventImage'>
                        </div>
                        <div class="card-body">
                            <div class="mb-3"><input type='text' name='evenamentName' placeholder="Event Name" />
                                <div><span class="error">
                                        <?php
                                        if (!empty($name_err)) {
                                            echo $name_err;
                                        }
                                        ?>
                                    </span></div>
                            </div>
                            <div class="mb-3"> <input type='text' name='time' placeholder="Date and Time" />
                                <div><span class="error">
                                        <?php
                                        if (!empty($time_err)) {
                                            echo $time_err;
                                        }
                                        ?>
                                    </span></div>
                            </div>
                            <div class="mb-3"> <input type='text' name='loc' placeholder="Location" />
                                <div><span class="error">
                                        <?php
                                        if (!empty($location_err)) {
                                            echo $location_err;
                                        }
                                        ?>
                                    </span></div>
                            </div>
                            <p> <textarea type='te' name='details' placeholder="Details about the event"></textarea>



                        </div>
                        <div class="card-footer">
                            <p><input type='text' name='author' placeholder="Author" /></p>
                        </div>
                        <div class="card-body">
                            <input class="btn btn-primary" type='submit' name='submit' value='Create Event'></th>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>