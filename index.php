<!DOCTYPE html>
<html lang="en">
<?php
require_once("head.php");
require_once("config.php");
require_once("functions.php");
$events = fetchEvents();
?>

<body>
    <?php
    require_once("header.php");
    ?>
    <div class="container">
        <div class="card-deck">
            <?php
            foreach ($events as $event) {


            ?>
                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 my-2">
                    <div class="card">
                        <!-- <img class="card-img-top" src="https://piedmontexedra.com/wp-content/uploads/2020/06/George.Floyd_.Poster.Protest.jpg" alt="Card image cap"> -->
                        <img class="card-img-top" src="uploads/<?php echo $event['image_name'] ?>" alt="Card image cap">

                        <div class="card-body">
                            <h5 class="card-title"><b><?php echo $event['name'] ?></b></h5>
                            <h6 class="card-text"><?php echo $event['time'] ?></h6>
                            <h6 class="card-text"><?php echo $event['location'] ?></h6>
                            <p class="card-text"><?php echo $event['descr'] ?></p>

                        </div>
                        <div class="card-footer">
                            <small class="text-muted"><?php echo $event['author'] ?></small>
                        </div>
                    </div>
                </div>
            <?php
            }

            ?>

        </div>
    </div>

</body>

</html>