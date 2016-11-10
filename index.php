
<?php

include_once("APP/Controller.php");
?>
<?php include_once("layout/header/header.php") ?>
<?php include_once("layout/navigation/navigation.php") ?>


<script>
    $( document ).ready(function() {
        console.log( "ready!" );

        // Variable to hold request
        var request;

        // Bind to the submit event of our form
        $("#download").submit(function(event){

            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Abort any pending request
            if (request) {
                request.abort();
            }
            // setup some local variables
            var $form = $(this);

            // Let's select and cache all the fields
            var $inputs = $form.find("input, select, button, textarea");

            // Serialize the data in the form
            var serializedData = $form.serialize();

            // Let's disable the inputs for the duration of the Ajax request.
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $inputs.prop("disabled", true);

            // Fire off the request to /form.php
            request = $.ajax({
                url: "ajax/commands.php",
                type: "post",
                data: serializedData
            });

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // Log a message to the console
                console.log("Hooray, it worked!");
                $(".result").html(response)
            });

            // Callback handler that will be called on failure
            request.fail(function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });

            // Callback handler that will be called regardless
            // if the request failed or succeeded
            request.always(function () {
                // Reenable the inputs
                $inputs.prop("disabled", false);
            });

        });


        // Bind to the submit event of our form
        $("img").click(function(event){

            // Let's select img tags
            var input = $(this).attr("alt");
                $.post( "ajax/commands.php", { action: "play", filePath: input } );
        });

        $("video").click(function(event){

            // Let's select img tags
            var input = $(this).attr("id");
            $.post( "ajax/commands.php", { action: "play", filePath: input } );
        });

    });
</script>

    <h1>Download</h1>

    <div class="well">
        <form id="download">
            <div class="form-group">
                <input id="url" name="url" type="text" value="" class="form-control" placeholder="URL..."/>
                <input id="action" name="action" type="hidden" value="download" />
            </div>
            <input type="submit" value="Download" class="btn btn-default"/>
        </form>
    </div>

    <div class="result">

    </div>

<?php



    $post = array(
        "media" => array(
            "download" => "url"
        ),
        "player" => array(
            "startPlaying"  => "videoPath",
            "resume"        => true,
            "pause"         => true,
            "quit"          => true,
        )
    );

    echo "<pre>";
    var_dump($post);
    echo "</pre>";


    include_once ("App/TaskHandler.php");

    $taskHandler = new TaskHandler();
    $taskHandler->setPost($post);
    $taskHandler->assignTask();

    $controller = new Controller();
    //$controller->saveItem();
    $player = $controller->getPlayer();


if(isset($_GET['pause'])){
    echo "<br> Pause";

    $player->pause();
}

if(isset($_GET['resume'])){

    $controller->resume();
    echo "resume";

}

echo $controller->getAllItems();

?>

    <!-- /.container -->
<?php include_once ("layout/footer/footer.php") ?>