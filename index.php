
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
        $("#EXAMPLE").submit(function(event){

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
        $("#download").click(function(event){

            // Let's select img tags
            var input = $("#url").val();
            request = $.post( "ajax/commands.php", { task: "media", command: "download", value: input } );

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // Log a message to the console
                console.log("Hooray, it worked!");
                $(".result").html(response)
            });
        });

        // Bind to the submit event of our form
        $("img").click(function(event){

            // Let's select img tags
            var input = $(this).attr("alt");
            request = $.post( "ajax/commands.php", { action: "play", filePath: input } );

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // Log a message to the console
                console.log("Hooray, it worked!");
                $(".result").html(response)
            });
        });

        $("video").click(function(event){

            // Let's select img tags
            var input = $(this).attr("id");
            request = $.post( "ajax/commands.php", { task: "player", command: "play", value: input });

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // Log a message to the console
                console.log("Hooray, it worked!");
                $(".result").html(response)
            });
        });

        $("#quit").click(function(event){
            request = $.post( "ajax/commands.php", { task: "player", command: "quit", value: "" });
        });

        $("#pause").click(function(event){
            request = $.post( "ajax/commands.php", { task: "player", command: "pause", value: "" });
        });

        $("#resume").click(function(event){
            request = $.post( "ajax/commands.php", { task: "player", command: "resume", value: "" });
        });

        $("#volymUp").click(function(event){
            request = $.post( "ajax/commands.php", { task: "player", command: "volym", value: "up" });
        });

        $("#volymDown").click(function(event){
            request = $.post( "ajax/commands.php", { task: "player", command: "volym", value: "down" });
        });

        $(".seek").click(function(event){
            var input = $(this).attr("id");
            request = $.post( "ajax/commands.php", { task: "player", command: "seek", value: input });
            //alert(input);
        });


        var flipDownload = 0;
        $(".download").hide();

        $(".downloadControl").click(function(event){
            var input = $(this).attr("id");
            $( ".download" ).toggle( flipControl++ % 2 === 0 );
        });

        var flipControl = 0;
        $(".control").hide();

        $(".mediaControl").click(function(event){
            var input = $(this).attr("id");
            $( ".control" ).toggle( flipControl++ % 2 === 0 );
        });

    });
</script>


<div class="page-header">
    <h1>Atroshi <small>Cloud</small></h1>
</div>

<div class="menu">
    <button type="button" class="btn btn-default downloadControl " aria-label="Left Align" >
        <span class="glyphicon glyphicon-download" ></span>
    </button>

    <button type="button" class="btn btn-default mediaControl " aria-label="Left Align" >
        <span class="glyphicon glyphicon-play" ></span>
    </button>
</div>

</br>

<div class="well download">
    <h2>Download</h2>
    <form>
        <div class="form-group">
            <input id="url" name="url" type="text" value="" class="form-control" placeholder="URL..."/>
        </div>
        <div class="btn btn-default" id="download">Download </div>
    </form>
</div>

<div class="result">

</div>


<div class="control alert alert-info">

    <button type="button" class="btn btn-default  " aria-label="Left Align" id="volymUp">
        <span class="glyphicon glyphicon-volume-down" ></span>
    </button>

    <button type="button" class="btn btn-default " aria-label="Left Align" id="volymDown">
        <span class="glyphicon glyphicon-volume-up" ></span>
    </button>

    <button type="button" class="btn btn-default " aria-label="Left Align" id="quit">
        <span class="glyphicon glyphicon-glyphicon glyphicon-off" ></span>
    </button>

    <hr>

    <button type="button" class="btn btn-default seek" aria-label="Left Align" id="-20">
        <span class="glyphicon glyphicon-fast-backward " ></span>
    </button>

    <button type="button" class="btn btn-default seek" aria-label="Left Align" id="-5">
        <span class="glyphicon glyphicon-step-backward " ></span>
    </button>

    <button type="button" class="btn btn-default " aria-label="Left Align" id="resume">
        <span class="glyphicon glyphicon-play" > </span>
    </button>

    <button type="button" class="btn btn-default " aria-label="Left Align" id="pause">
        <span class="glyphicon glyphicon-pause" > </span>
    </button>

    <button type="button" class="btn btn-default seek" aria-label="Left Align" id="5">
        <span class="glyphicon glyphicon-step-forward " ></span>
    </button>

    <button type="button" class="btn btn-default seek" aria-label="Left Align" id="20">
        <span class="glyphicon glyphicon-fast-forward " ></span>
    </button>

</div>

    <?php
        $controller = new Controller();
        echo $controller->getAllItems();
    ?>
    <!-- /.container -->
<?php include_once ("layout/footer/footer.php") ?>