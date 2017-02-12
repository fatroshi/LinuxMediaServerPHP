
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
        $("#download").click(function(event){

            // used for url
            var input = $("#url").val();
            // categoryId
            var categoryId = $("#categoryId").val();
            request = $.post( "ajax/commands.php", { task: "media", command: "download", value: input, category: categoryId} );

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

        $(".tv").click(function(event){

            // Let's select img tags
            var input = $(this).attr('id')
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
            $( ".download" ).toggle( flipDownload++ % 2 === 0 );
        });

        var flipControl = 0;
        $(".control").hide();

        $(".mediaControl").click(function(event){
            $(".control" ).toggle( flipControl++ % 2 === 0 );
        });

        var flipNewCategory = 0;
        $(".addCategory").hide();

        $(".categoryControl").click(function(event){
            $(".addCategory" ).toggle( flipNewCategory++ % 2 === 0 );
        });

        var flipTv = 0;
        $(".tv").hide();

        $(".tvControl").click(function(event){
            $(".tv" ).toggle( flipTv++ % 2 === 0 );
        });

    });
</script>

<nav class="navbar navbar-default navbar-fixed-top">
        <center>
            <span class="h2">

                <a href="index.php"><span class="h1">Atroshi</span></a> <span class="h2">Cloud</span>

                <button type="button" class="btn btn-default downloadControl " aria-label="Left Align" >
                    <span class="glyphicon glyphicon-download" ></span>
                </button>

                <button type="button" class="btn btn-default categoryControl " aria-label="Left Align" >
                    <span class="glyphicon glyphicon-plus" ></span>
                </button>

                <button type="button" class="btn btn-default mediaControl " aria-label="Left Align" >
                    <span class="glyphicon glyphicon-play" ></span>
                </button>

                <button type="button" class="btn btn-default tvControl " aria-label="Left Align" >
                    <span class="glyphicon glyphicon-blackboard" ></span>
                </button>

            </span>

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


        </center>
</nav>

</br>
</br>

<div class="well download">
    <h2>Download</h2>
    <form>
        <div class="form-group">
            <input id="url" name="url" type="text" value="" class="form-control" placeholder="URL..."/>

            <?php
                if(isset($_GET['category']) && is_numeric($_GET['category'])){
                    $id = $_GET['category'];
                }else{
                    $id = 0;
                }

            ?>

            <input id="categoryId" name="categoryId" type="hidden" value="<?php echo $id ?>" />
        </div>
        <div class="btn btn-default" id="download">Download </div>
    </form>
</div>

<?php
$controller = new Controller();

if(!$controller->connectedToDatabase()){
    echo "No Dabase connection";
    exit;
}

if(isset($_POST['newCategory']) && $_POST['name'] != "" && $_FILES['image'] !=""){
    $name = $_POST['name'];
    $image = $_FILES['image'];
    // Save to db
    $controller->addCategory($name, $image);

}
?>

<div class="well addCategory">
    <form enctype="multipart/form-data" method="post" action="">
        <h2>Category name</h2>
        <input type="text" name="name" class="form-control" placeholder="Category name..."><br>
        <input type="file" size="256" name="image" value="">
        <input type="submit" value="Save" name="newCategory" class="btn btn-default">
    </form>
</div>







<div class="result">

</div>






<?php
    //CREATE A CLASS FOR THIS
    if(isset($_GET['category']) && is_numeric($_GET['category'])){
        $categoryId = $_GET['category'];
        echo $controller->getCategoryItems($categoryId);
    }

    echo $controller->getAllCategories();
?>


    <!-- /.container -->
<?php include_once ("layout/footer/footer.php") ?>