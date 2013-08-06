

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>jQuery UI Effects - Show Demo</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />
<style>
.toggler {position:absolute;  padding-top:-400px;alignment-adjust: central; padding:auto; width: auto; height: auto;  }
#button { padding: .5em 1em; text-decoration: none; }
#effect { border: 10px solid red; width: auto;  height: auto; padding: 0.4em; position: relative; }
#effect h3 { margin: 0; padding: 0.4em; text-align: center; }
</style>
<script>
     $(document).ready(function() {
    $("#button").click(function(event) {
          
        $("#effect").load('#capa');

    }
    );});
$(function() {
// run the currently selected effect

function runEffect() {
// get effect type from
var selectedEffect = $( "bounce" );
// most effect types need no options passed by default
var options = {};
// some effects have required parameters
if ( selectedEffect === "scale" ) {
options = { percent: 400 };
} else if ( selectedEffect === "size" ) {
options = { to: { width: 280, height: 185 } };
}
// run the effect
$( "#effect" ).show( selectedEffect, options, 500, callback );
};
//callback function to bring a hidden box back
function callback() {
setTimeout(function() {
$( "#effect:visible" ).removeAttr( "style" ).fadeOut();
}, 80000 );
};
// set effect from select menu value
$( "#button" ).click(function() {
runEffect();
return false;
});
$( "#effect" ).hide();
});
</script>
</head>
<body>
  
<div class="toggler">
    
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

  <a href="#" id="button" ><</a>
<br>
<br>
<div class="Rotate-90">PERSONALIZADOS</div>

                    <div id="instruccion2" class="span1">
                      EN DESARROLLO!!
                    </div> 
<div id="effect">

</div>
</div>

</body>
</html>


