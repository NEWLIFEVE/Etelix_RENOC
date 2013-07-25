<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
         <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <link href="protected/extensiones/bootstrap/assetscss/bootstrap.min.css" rel="stylesheet" media="screen">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <link href="protected/extensiones/bootstrap/assets/css/bootstrap.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/css/docs.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/js/google-code-prettify/prettify.css" rel="stylesheet"/>
            <link rel="apple-touch-icon-precomposed" sizes="144x144" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" sizes="114x114" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" sizes="72x72" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png"/>
            <link rel="shortcut icon" href="protected/extensions/bootstrap/assets/ico/favicon.png"/> 
            

    </head>
    <body>
        <div class="container" style="padding:-30px;">
            <!--menu-->
            <?php
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse', // null or 'inverse'
                'brand' => 'RENOC',
                'brandUrl' => '#',
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'items' => array(
                            array('label' => 'Leandro', 'url' => '#'),
                        ),

                    ),
                ),
            ));
            ?>
            <!--fin de menu-->
            <?php echo Yii::app()->bootstrap->init(); ?>
            <div id="header">
                <div id="logo"></div>
            </div> 
            <div id="espacio"  class="">
                
            </div>
            <div class="row">
                <div id="barraVerde" class="span4" >
                          
        
                <div  class="span8">
                    <div id="fondo" >   
<?php if (isset($this->breadcrumbs)): ?>
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
    ));
    ?><!-- breadcrumbs -->
                        <?php endif ?>
                        <div  id="three"><ul class="thumbnails">
                                <li class="span1">
                                    <div id="icono"  href="#" class=" ">
                                        <rigth>  <h1> <a href="index.php" class="icon-backward "></a></h1></rigth>
                                    </div>
                                </li>
                            </ul> 
                        </div>
<?php echo $content; ?>
                        <div id="capa">
                            <div  id="one" > 
                                <ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="#" class="">
                                            <h1> RUTINARIOS &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;  &nbsp;
                                                <a href="#" rel="tooltip" 
                                                   title="esta es la consulta basica por hora y fecha" 
                                                   class="tooltip-test"> > </a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul>             
                            </div>
                            <div id="two"><ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="#" class="">
                                            <h1> ESPECIFICOS &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  &nbsp;
                                                <a href="#" rel="tooltip" 
                                                   title="aqui se muestra informacion de data por fecha y hora especifica"
                                                   class="tooltip-test"> > </a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul>  </div>
                            <div  id="three"><ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="#" class="">
                                            <h1> PERSONALIZADOS &nbsp; &nbsp;&nbsp;
                                                <a href="#" rel="tooltip" 
                                                   title="puede realizar una busqueda filtrada de data, por fecha, operadora, entre otras" 
                                                   class="tooltip-test"> > </a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul>  </div>
                            <div id="four"> </div>
                            
                            
                            <div id="segundacapa" ></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div><!-- page -->
         </div><!--container-->
        <div class="clear"></div>

        <div id="footer" style=" background:#151515; height:40px; width:1400px;
                      margin: -57px;        position:absolute; top:600; padding-lefth:-130; padding-top: 0px;"  >
            Copyright &copy; <?php echo date('Y'); ?> ETELIX DEV.<br/>
                        All Rights Reserved.   <br/>

        </div><!-- footer -->
        <script>
            var TRACK = (function() {
                var p = document.createElement('p');
                document.body.appendChild(p);
                return function(str) {
                    p.innerHTML = str;
                }
            }());
            var fade_timer,
                    going_left = true,
                    capa = document.getElementById('capa'),
                    one = document.getElementById('one'),
                    two = document.getElementById('two'),
                    three = document.getElementById('three'),
                    set_opacity = function(elem, o) {
                elem.style.opacity = o;
                elem.style.filter = 'alpha(opacity=' + o * 100 + ')';
            },
                    swap = function(fade_in, fade_out, end_left) {
                var start_time = +new Date(), total_time = 750, end_time = start_time + total_time,
                        start_left = parseFloat(capa.style.left) || 0, total_left = end_left - start_left,
                        in_start_o = parseFloat(fade_in.style.opacity) || 0, in_total_o = 1 - in_start_o,
                        out_start_o = parseFloat(fade_out.style.opacity) || 1, out_total_o = -out_start_o;

                fade_timer = setInterval(function() {
                    var percent = +new Date(),
                            current_time = percent > end_time ? 1 : (percent - start_time) / total_time;
                    percent = (1 - Math.cos(current_time * Math.PI)) / 2;

                    set_opacity(fade_in, in_start_o + percent * in_total_o);
                    set_opacity(fade_out, out_start_o + percent * out_total_o);
                    capa.style.left = (start_left + percent * total_left) + 'px';

                    if (current_time === 1) {
                        clearInterval(fade_timer);
                    }
                }, 40);
            };
            document.getElementById('fondo').onclick = function() {
                clearInterval(fade_timer);
                if (going_left) {
                    going_left = false;
                    swap(three, one, -1480);
                } else {
                    going_left = true;
                    swap(two, 0);
                }
            };

        </script>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/jquery.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-transition.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-alert.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-modal.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-dropdown.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-scrollspy.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tab.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-popover.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-button.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-collapse.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-carousel.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-typeahead.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/bootstrap-affix.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/holder/holder.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/google-code-prettify/prettify.js"/>
        <script src="protected/extensiones/bootstrap/assets/js/application.js"/>
    </body>
</html>
