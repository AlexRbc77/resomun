<?php include('database.php'); session_start();?>
<?php

$image = $_POST['image'];

?>
<script>
function imageToDataUri(img, width, height) {

    // create an off-screen canvas
    var canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d');

    // set its dimension to target size
    canvas.width = width;
    canvas.height = height;

    // draw source image into the off-screen canvas:
    ctx.drawImage(img, 0, 0, width, height);

    // encode image to data-uri with base64 version of compressed image
    return canvas.toDataURL();
}
var data = "<?php echo $image?>";
document.write("<img id='image'>");
document.getElementById("image").src = data;
var img = document.getElementById("image");
var result = imageToDataUri(img, 400, 400);
img.hidden = 'true';
document.write(result);
</script>