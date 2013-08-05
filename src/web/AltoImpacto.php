<?php //
/************************ FUNCIONES - COMIENZO ***********************/

function format_decimal($num){
    $english_format_number2 = number_format($num, 10, ',', '.');
    $numtext=strval($english_format_number2);
    $position = strpos($numtext, ',');
    $numsub = substr($numtext,0,$position+3); 
    return $numsub;
}
function formatearFecha($fecha, $tipo=NULL) {

        if($tipo==NULL){
            
            $arrayFecha = explode("/", $fecha);

            if (strlen($arrayFecha[0]) == 1) {
                $arrayFecha[0] = "0" . $arrayFecha[0];
            }
            if (strlen($arrayFecha[1]) == 1) {
                $arrayFecha[1] = "0" . $arrayFecha[1];
            }

            $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[0] . "-" . $arrayFecha[1];
            return $fechaFinal;
        }
        
        if($tipo=='etelixPeru'){
            
            $arrayFecha = explode(" ", $fecha);
            return $arrayFecha[0];
            
        }
        
    }

if (isset($_GET['fecha'])){

$fecha_mod = formatearFecha($_GET['fecha']);
echo $fecha_mod;
}
/*----------------------- FUNCIONES - FIN ---------------------------*/

/************************ PARAMETROS BASE DE DATOS - COMIENZO ***********************/

//    $server     = "192.168.1.239:5432";
//    $username   = "edmaku";
//    $password   = "123";
//    $dataBase   = "sori";

/*----------------------- PARAMETROS BASE DE DATOS - FIN ---------------------------*/



/************************ CONECCION BASE DE DATOS - COMIENZO ************************/

    //$conection = mysql_connect($server, $username, $password);
    $conection = pg_connect("host=67.215.160.89 port=5432 dbname=sori user=postgres password=Nsusfd8263");
    //$conection = pg_connect("host=192.168.1.239 port=5432 dbname=sori user=postgres password=123");
    
    //mysql_select_db($dataBase, $conection);

/*----------------------- CONECCION BASE DE DATOS - FIN  ---------------------------*/



/************************ SENTENCIAS SQL - COMIENZO *********************************/

$sqlClientes = "SELECT x.CLIENTE as Cliente, x.TOTALCALLS as TotalCalls, x.CALLS as CompleteCalls, x.MINUTOS as Minutos,x.PDD as Pdd, x.COST as Cost, x.REVENUE as Revenue, x.MARGEN as Margin
        FROM   (SELECT c.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS, sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, carrier c
                WHERE b.date_balance = '$fecha_mod' AND b.type= 1 AND b.id_destination_int is not NULL AND b.id_carrier = c.id 
                GROUP BY c.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10
        ORDER BY x.MARGEN DESC;
        ";
$sqlClientesTotal = "SELECT 'TOTAL', sum(x.TOTALCALLS) as TotalCalls, sum(x.CALLS) as CompleteCalls, sum(x.MINUTOS) as Minutos,sum(x.PDD) as Pdd, sum(x.COST) as Cost, sum(x.REVENUE) as Revenue, sum(x.MARGEN) as Margin
        FROM   (SELECT c.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS, sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, carrier c
                WHERE b.date_balance = '$fecha_mod' AND b.type= 1 AND b.id_destination_int is not NULL AND b.id_carrier = c.id 
                GROUP BY c.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10;
        ";
$sqlClientesTotalCompleto = "SELECT 'TOTAL', sum(complete_calls+incomplete_calls) as TotalCalls, sum(complete_calls) as CompleteCalls, sum(minutes) as Minutos,sum(PDD) as Pdd, sum(COST) as Cost, 
sum(REVENUE) as Revenue, sum(margin) as Margin
        FROM balance
        WHERE date_balance ='$fecha_mod' AND type= 1 AND id_destination_int is not NULL;";

$sqlProveedores = "SELECT x.CLIENTE as Cliente, x.TOTALCALLS as TotalCalls, x.CALLS as CompleteCalls, x.MINUTOS as Minutos,x.PDD as Pdd, x.COST as Cost, x.REVENUE as Revenue, x.MARGEN as Margin
        FROM   (SELECT c.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS,sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, carrier c
                WHERE b.date_balance = '$fecha_mod' AND b.type= 0 AND b.id_destination_int is not NULL AND b.id_carrier = c.id 
                GROUP BY c.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10
        ORDER BY x.MARGEN DESC;
        ";
$sqlProveedoresTotal = "SELECT 'TOTAL', sum(x.TOTALCALLS) as TotalCalls, sum(x.CALLS) as CompleteCalls, sum(x.MINUTOS) as Minutos,sum(x.PDD) as Pdd, sum(x.COST) as Cost, sum(x.REVENUE) as Revenue, sum(x.MARGEN) as Margin
        FROM   (SELECT c.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS,sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, carrier c
                WHERE b.date_balance = '$fecha_mod' AND b.type= 0 AND b.id_destination_int is not NULL AND b.id_carrier = c.id 
                GROUP BY c.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10;
        ";
$sqlProveedoresTotalCompleto = "SELECT 'TOTAL', sum(complete_calls+incomplete_calls) as TotalCalls, sum(complete_calls) as CompleteCalls, sum(minutes) as Minutos,sum(PDD) as Pdd, sum(COST) as Cost, 
sum(REVENUE) as Revenue, sum(margin) as Margin
        FROM balance
        WHERE date_balance ='$fecha_mod' AND type= 0 AND id_destination_int is not NULL;";
$sqlDestinos = "SELECT x.CLIENTE as Cliente, x.TOTALCALLS as TotalCalls, x.CALLS as CompleteCalls, x.MINUTOS as Minutos,x.PDD as Pdd, x.COST as Cost, x.REVENUE as Revenue, x.MARGEN as Margin
        FROM   (SELECT d.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS,sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, destination d
                WHERE b.date_balance = '$fecha_mod' AND b.type=1 AND b.id_destination is not NULL AND b.id_destination = d.id 
                GROUP BY d.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10
        ORDER BY x.MARGEN DESC";
$sqlDestinosTotal = "SELECT 'TOTAL', sum(x.TOTALCALLS) as TotalCalls, sum(x.CALLS) as CompleteCalls, sum(x.MINUTOS) as Minutos,sum(x.PDD) as Pdd, sum(x.COST) as Cost, sum(x.REVENUE) as Revenue, sum(x.MARGEN) as Margin
        FROM   (SELECT d.name as CLIENTE,sum(b.complete_calls) as CALLS,sum(b.complete_calls+b.incomplete_calls) as TOTALCALLS, sum(b.minutes) as MINUTOS,sum(b.pdd_calls) as PDD, sum(b.cost) as COST, sum(b.revenue) as REVENUE, CASE  WHEN sum(b.margin)>10 THEN sum(b.margin) ELSE 0 END as MARGEN
                FROM balance b, destination d
                WHERE b.date_balance = '$fecha_mod' AND b.type=1 AND b.id_destination is not NULL AND b.id_destination = d.id 
                GROUP BY d.name
                ORDER BY MARGEN DESC) x
        WHERE x.MARGEN > 10";
$sqlDestinosTotalCompleto = "SELECT 'TOTAL', sum(complete_calls+incomplete_calls) as TotalCalls, sum(complete_calls) as CompleteCalls, sum(minutes) as Minutos,sum(PDD) as Pdd, sum(COST) as Cost, 
sum(REVENUE) as Revenue, sum(margin) as Margin
        FROM balance
        WHERE date_balance ='$fecha_mod' AND type= 1 AND id_destination_int is not NULL;";
/*----------------------- SENTENCIAS SQL - FIN  ------------------------------------*/



/************************ OBTENER DIA DE LA SEMANA - COMIENZO ***********************/

    function diaSemana($mes,$dia,$anio){
        // 0->domingo	 | 6->sabado
        $numeroDia= date("w",mktime(0, 0, 0, $mes, $dia, $anio));
        return $numeroDia;
    }

    $fechaActualVenezuela = date("Y-m-d", time());
    list($year, $mon, $day) = explode('-', $fechaActualVenezuela);

    $dia = array(   0 => "Domingo",
                    1 => "Lunes",
                    2 => "Martes",
                    3 => "Miercoles",
                    4 => "Jueves",
                    5 => "Viernes",
                    6 => "Sabado"   );
    
    $diaSemanaVenezuela = $dia[diaSemana($mon, $day-1, $year)];

/*----------------------- OBTENER DIA DE LA SEMANA - FIN ---------------------------*/



/************************ GENERACION CODIGO HTML - COMIENZO *************************/

$email = "
<div>
    <h1 style='color:#615E5E; border: 0 none; font:150% Arial,Helvetica,sans-serif; margin: 0;
        padding-left: 550;margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline;
        background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
        Alto Impacto (+10$)
    </h1>
    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif;
        margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline;
        background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>Por Clientes (Ventas)</h2>
    <br/>
    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
        <tr>
            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                Client
            </th>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";

//$resultado = mysql_query($sql,$conection);
$clientes = pg_query($sqlClientes);
$par=1;
$pos=1;

while ($fila = pg_fetch_row($clientes)){
$margin=($fila[6]*100)/$fila[5];
        $margin=$margin-100;
    if($par%2!=0){
        if($par==1){
            $email .= "<tr style='background-color:#FFC8AE; color:#584E4E;'>";
        }else{
            $email .= "<tr style='background-color:#AFD699; color:#584E4E;'>";
        }
    
    
    $email .="
            <td style='text-align: left;' class='fecha'>
                $fila[0]
             </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";       
        $email .= "
        </tr>";
    }
    elseif($par%2==0){
        
        if ($par==4){
            $email .= "<tr style='background-color:#F8B6C9; color:#584E4E;'>";
            $par=0;
        }else{
            $email .= "<tr style='background-color:#B3A5CF; color:#584E4E;'>";      
        }
    $email .= "
            <td style='text-align: left;' class='fecha'>
                $fila[0]
            </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";
        $email .= "
        </tr>";
    }
    
    $par++;
    $pos++;
}

$email .="<tr>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Client
            </td>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";

$clientesTotal = pg_query($sqlClientesTotal);

while ($fila = pg_fetch_row($clientesTotal)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;

$totalcallsTotal=$fila[1];
$completecallsTotal=$fila[2];
$minutesTotal=$fila[3];
$costTotal=$fila[5];
$revenueTotal=$fila[6];
$marginTotal=$fila[7];

    $email .="
        <tr style='background-color:#999999; color:#FFFFFF;'>

    <td style='text-align: center;' class='fecha'>
                $fila[0]
            </td>          
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}
$clientesTotalCompleto = pg_query($sqlClientesTotalCompleto);

while ($fila = pg_fetch_row($clientesTotalCompleto)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;

$totalcallsTotalCompleto=$fila[1];
$completecallsTotalCompleto=$fila[2];
$minutesTotalCompleto=$fila[3];
$costTotalCompleto=$fila[5];
$revenueTotalCompleto=$fila[6];
$marginTotalCompleto=$fila[7];

    $email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

    <td style='text-align: center;' class='fecha'>
                $fila[0]
            </td>          
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}
$email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

        <td style='text-align: left; background-color:#f8f8f8' class='fecha'>
                
            </td>          
            <td style='text-align: right;' class='totalVentas'>".
                 format_decimal(($totalcallsTotal/$totalcallsTotalCompleto)*(100))."%
            </td>
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($completecallsTotal/$completecallsTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($minutesTotal/$minutesTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
                
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($costTotal/$costTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($revenueTotal/$revenueTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($marginTotal/$marginTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>          
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            </tr>"; 
$email .="
    </table>
    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0;
         background-color: #f8f8f8; vertical-align: baseline;
        background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'
    >Por Proveedores (Compras)</h2>
    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
        <tr>
            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                Supplier
            </th>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";


$proveedores = pg_query($sqlProveedores);
$par=1;
$pos=1;

while ($fila = pg_fetch_row($proveedores)){
$margin=($fila[6]*100)/$fila[5];
        $margin=$margin-100;
    if($par%2!=0){
        if($par==1){
            $email .= "<tr style='background-color:#FFC8AE; color:#584E4E;'>";
        }else{
            $email .= "<tr style='background-color:#AFD699; color:#584E4E;'>";
        }
    $email .= "
            <td style='text-align: left;' class='fecha'>
                $fila[0]
             </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";        
        $email .= "
        </tr>";
    }
    elseif($par%2==0){
         if ($par==4){
            $email .= "<tr style='background-color:#F8B6C9; color:#584E4E;'>";
            $par=0;
        }else{
            $email .= "<tr style='background-color:#B3A5CF; color:#584E4E;'>";      
        }

    $email .= "
            <td style='text-align: left;' class='fecha'>
                $fila[0]
             </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";  
        $email .= "
        </tr>";
    }
    $par++;
    $pos++;
}
$email .="
<tr>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Supplier
            </td>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";
$proveedoresTotal = pg_query($sqlProveedoresTotal);

while ($fila = pg_fetch_row($proveedoresTotal)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;

$totalcallsTotal=$fila[1];
$completecallsTotal=$fila[2];
$minutesTotal=$fila[3];
$costTotal=$fila[5];
$revenueTotal=$fila[6];
$marginTotal=$fila[7];
    $email .="
        <tr style='background-color:#999999; color:#FFFFFF;'>

    <td style='text-align: center;' class='fecha'>
                $fila[0]
            </td>          
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}
$proveedoresTotalCompleto = pg_query($sqlProveedoresTotalCompleto);

while ($fila = pg_fetch_row($proveedoresTotalCompleto)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;

$totalcallsTotalCompleto=$fila[1];
$completecallsTotalCompleto=$fila[2];
$minutesTotalCompleto=$fila[3];
$costTotalCompleto=$fila[5];
$revenueTotalCompleto=$fila[6];
$marginTotalCompleto=$fila[7];
    $email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

   <td style='text-align: center;' class='fecha'>
                $fila[0]
            </td>          
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."%
            </td>  
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}

$email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

       <td style='text-align: left; background-color:#f8f8f8' class='fecha'>
                
            </td>          
            <td style='text-align: right;' class='totalVentas'>".
                 format_decimal(($totalcallsTotal/$totalcallsTotalCompleto)*(100))."%
            </td>
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($completecallsTotal/$completecallsTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($minutesTotal/$minutesTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
                
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($costTotal/$costTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($revenueTotal/$revenueTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($marginTotal/$marginTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>          
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            </tr>"; 
$email .="
    </table>
    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0;
         background-color: #f8f8f8; vertical-align: baseline;
        background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'
    >Por Destinos</h2>
    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
        <tr>
            <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                Destination
            </th>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Rate/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";


$destinos = pg_query($sqlDestinos);
$par=1;
$pos=1;

while ($fila = pg_fetch_row($destinos)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;
$costmin=($fila[5]/$fila[3])*(100);
$ratemin=($fila[6]/$fila[3])*(100);
$marginmin=$ratemin-$costmin;

    if($par%2!=0){
         if (substr_count($fila[0],'USA')>=1 ||
            substr_count($fila[0],'CANADA')>=1){
             
            $email .= "<tr style='background-color:#F3F3F3; color:#584E4E;'>";
            
     }elseif (substr_count($fila[0],'SPAIN')>=1 || 
                substr_count($fila[0],'ROMANIA')>=1 || 
                substr_count($fila[0],'BELGIUM')>=1 || 
                substr_count($fila[0],'PAKISTAN')>=1 || 
                substr_count($fila[0],'ANTIGUA')>=1 || 
                substr_count($fila[0],'UGANDA')>=1 || 
                substr_count($fila[0],'NETHERLANDS')>=1 || 
                substr_count($fila[0],'THAILAND')>=1 || 
                substr_count($fila[0],'CHINA')>=1 || 
                substr_count($fila[0],'DENMARK')>=1 || 
                substr_count($fila[0],'RUSSIA')>=1 || 
                substr_count($fila[0],'AUSTRIA')>=1 || 
                substr_count($fila[0],'NORWAY')>=1 || 
                substr_count($fila[0],'MAURITANIA')>=1 || 
                substr_count($fila[0],'FINLAND')>=1 || 
                substr_count($fila[0],'UNITED KINGDOM')>=1 || 
                substr_count($fila[0],'ITALY')>=1 || 
                substr_count($fila[0],'SWITZERLAND ')>=1 || 
                substr_count($fila[0],'VIETNAM')>=1 || 
                substr_count($fila[0],'SATELLITE')>=1 || 
                substr_count($fila[0],'JAPAN ')>=1 || 
                substr_count($fila[0],'IRELAND')>=1 || 
                substr_count($fila[0],'ISRAEL ')>=1 || 
                substr_count($fila[0],'AUSTRALIA')>=1){
            
            $email .= "<tr style='background-color:#8BA0AC; color:#584E4E;'>";
            
        }elseif (substr_count($fila[0],'PERU')>=1 || 
                substr_count($fila[0],'CHILE')>=1 || 
                substr_count($fila[0],'ECUADOR')>=1 || 
                substr_count($fila[0],'PARAGUAY')>=1 || 
                substr_count($fila[0],'BRAZIL')>=1 || 
                substr_count($fila[0],'BOLIVIA')>=1 || 
                substr_count($fila[0],'ARGENTINA')>=1 || 
                substr_count($fila[0],'URUGUAY')>=1 ) {
            
            $email .= "<tr style='background-color:#AED7F3; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'COLOMBIA')>=1 ) {
            
            $email .= "<tr style='background-color:#BEE2C1; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'VENEZUELA')>=1 ) {
            
            $email .= "<tr style='background-color:#F0D0AE; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'MEXICO')>=1 || 
                substr_count($fila[0],'PANAMA')>=1 || 
                substr_count($fila[0],'CUBA')>=1 || 
                substr_count($fila[0],'BARBADOS')>=1 || 
                substr_count($fila[0],'ARUBA')>=1 || 
                substr_count($fila[0],'DOMINICAN REPUBLIC ')>=1 || 
                substr_count($fila[0],'HONDURAS')>=1 || 
                substr_count($fila[0],'HAITI')>=1 || 
                substr_count($fila[0],'SALVADOR')>=1 ) {
            
            $email .= "<tr style='background-color:#EDF0AE; color:#584E4E;'>";
        }
        
    $email .= "
            <td style='text-align: left;' class='fecha'>
                $fila[0]
            </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."
            </td>  
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($costmin)."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($ratemin)."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($marginmin)."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";       
        $email .= "
        </tr>";
    }
    elseif($par%2==0){
 if (substr_count($fila[0],'USA')>=1 ||
            substr_count($fila[0],'CANADA')>=1){
             
            $email .= "<tr style='background-color:#F3F3F3; color:#584E4E;'>";
            
        }elseif (substr_count($fila[0],'SPAIN')>=1 || 
                substr_count($fila[0],'ROMANIA')>=1 || 
                substr_count($fila[0],'BELGIUM')>=1 || 
                substr_count($fila[0],'PAKISTAN')>=1 || 
                substr_count($fila[0],'ANTIGUA')>=1 || 
                substr_count($fila[0],'UGANDA')>=1 || 
                substr_count($fila[0],'NETHERLANDS')>=1 || 
                substr_count($fila[0],'THAILAND')>=1 || 
                substr_count($fila[0],'CHINA')>=1 || 
                substr_count($fila[0],'DENMARK')>=1 || 
                substr_count($fila[0],'RUSSIA')>=1 || 
                substr_count($fila[0],'AUSTRIA')>=1 || 
                substr_count($fila[0],'NORWAY')>=1 || 
                substr_count($fila[0],'MAURITANIA')>=1 || 
                substr_count($fila[0],'FINLAND')>=1 || 
                substr_count($fila[0],'UNITED KINGDOM')>=1 || 
                substr_count($fila[0],'ITALY')>=1 || 
                substr_count($fila[0],'SWITZERLAND ')>=1 || 
                substr_count($fila[0],'VIETNAM')>=1 || 
                substr_count($fila[0],'SATELLITE')>=1 || 
                substr_count($fila[0],'JAPAN ')>=1 || 
                substr_count($fila[0],'IRELAND')>=1 || 
                substr_count($fila[0],'ISRAEL ')>=1 || 
                substr_count($fila[0],'AUSTRALIA')>=1){
            
            $email .= "<tr style='background-color:#8BA0AC; color:#584E4E;'>";
            
        }elseif (substr_count($fila[0],'PERU')>=1 || 
                substr_count($fila[0],'CHILE')>=1 || 
                substr_count($fila[0],'ECUADOR')>=1 || 
                substr_count($fila[0],'PARAGUAY')>=1 || 
                substr_count($fila[0],'BRAZIL')>=1 || 
                substr_count($fila[0],'BOLIVIA')>=1 || 
                substr_count($fila[0],'ARGENTINA')>=1 || 
                substr_count($fila[0],'URUGUAY')>=1 ) {
            
            $email .= "<tr style='background-color:#AED7F3; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'COLOMBIA')>=1 ) {
            
            $email .= "<tr style='background-color:#BEE2C1; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'VENEZUELA')>=1 ) {
            
            $email .= "<tr style='background-color:#F0D0AE; color:#584E4E;'>";
        
        }elseif (substr_count($fila[0],'MEXICO')>=1 || 
                substr_count($fila[0],'PANAMA')>=1 || 
                substr_count($fila[0],'CUBA')>=1 || 
                substr_count($fila[0],'BARBADOS')>=1 || 
                substr_count($fila[0],'ARUBA')>=1 || 
                substr_count($fila[0],'DOMINICAN REPUBLIC ')>=1 || 
                substr_count($fila[0],'HONDURAS')>=1 || 
                substr_count($fila[0],'HAITI')>=1 || 
                substr_count($fila[0],'SALVADOR')>=1 ) {
            
            $email .= "<tr style='background-color:#EDF0AE; color:#584E4E;'>";
        }

    $email .= "
            <td style='text-align: left;' class='fecha'>
                $fila[0]
            </td>
            <td style='text-align: left;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($margin)."
            </td>  
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($costmin)."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($ratemin)."
            </td>
            <td style='text-align: left;' class='diferencialBancario'>".
                format_decimal($marginmin)."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>
                $pos
            </td>";
        $email .= "
        </tr>";
    }
    $par++;
    $pos++;
}

$email .="
    <tr>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Destination
            </td>     
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                TotalCalls
            </th> 
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                CompleteCalls
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Minutes
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ASR
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                ACD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                PDD
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Revenue
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin%
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Cost/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Rate/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Margin/Min
            </th>
            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                Position
            </th>
        </tr>";
$destinosTotal = pg_query($sqlDestinosTotal);

while ($fila = pg_fetch_row($destinosTotal)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;
$costmin=($fila[5]/$fila[3])*(100);
$ratemin=($fila[6]/$fila[3])*(100);
$marginmin=$ratemin-$costmin;

$totalcallsTotal=$fila[1];
$completecallsTotal=$fila[2];
$minutesTotal=$fila[3];
$costTotal=$fila[5];
$revenueTotal=$fila[6];
$marginTotal=$fila[7];

    $email .="
        <tr style='background-color:#999999; color:#FFFFFF;'>

    <td style='text-align: center;' class='fecha'>
                $fila[0]
            </td>
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."
            </td>  
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($costmin)."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($ratemin)."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($marginmin)."
            </td>
           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}
$destinosTotalCompleto = pg_query($sqlDestinosTotalCompleto);

while ($fila = pg_fetch_row($destinosTotalCompleto)){
$margin=($fila[6]*100)/$fila[5];
$margin=$margin-100;

$totalcallsTotalCompleto=$fila[1];
$completecallsTotalCompleto=$fila[2];
$minutesTotalCompleto=$fila[3];
$costTotalCompleto=$fila[5];
$revenueTotalCompleto=$fila[6];
$marginTotalCompleto=$fila[7];

    $email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

        <td style='text-align: center;' class='fecha'>
                $fila[0]
                        </td>
            <td style='text-align: center;' class='totalVentas'>".
                format_decimal($fila[1])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[2])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[3])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[2]*100)/$fila[1])."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal(($fila[3]/$fila[2]))."
            </td>           
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[4])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[5])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[6])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($fila[7])."
            </td>
            <td style='text-align: center;' class='diferencialBancario'>".
                format_decimal($margin)."
            </td>  
  
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>                
            </td> 
            </tr>";        
}

$email .="
        <tr style='background-color:#615E5E; color:#FFFFFF;'>

        <td style='text-align: left; background-color:#f8f8f8' class='fecha'>
                
            </td>          
            <td style='text-align: right;' class='totalVentas'>".
                 format_decimal(($totalcallsTotal/$totalcallsTotalCompleto)*(100))."%
            </td>
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($completecallsTotal/$completecallsTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($minutesTotal/$minutesTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
                
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($costTotal/$costTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($revenueTotal/$revenueTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: right;' class='diferencialBancario'>".
                 format_decimal(($marginTotal/$marginTotalCompleto)*(100))."%
            </td>           
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>
            
            </td>          
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            <td style='text-align: left; background-color:#f8f8f8' class='diferencialBancario'>   
            
            </td> 
            </tr>"; 
$email .= "
    </table>
</div>";

/*----------------------- GENERACION CODIGO HTML - FIN -----------------------------*/



/************************ ENVIO DE CORREO ELECTRONICO - COMIENZO ********************/

//    require_once('class.phpmailer.php');
//    $mailer = new PHPMailer(TRUE);
//    $mailer->IsSMTP();
//    try{
//
//        $mailer->Host     = 'smtp.gmail.com';
//        $mailer->Port     = '587';
//        //$mailer->SMTPDebug = 2;
//        $mailer->SMTPSecure = 'tls';
//        $mailer->Username = 'sinca.test@gmail.com';
//        $mailer->SMTPAuth = true;
//        $mailer->Password ="sincatest";
//        $mailer->IsHTML(true);
//        //$mailer->SetFrom('noresponder@sinca.com', 'Sistema S I N C A');
//        $mailer->From     = 'sinca.test@gmail.com';
//        $mailer->AddReplyTo('sinca.test@gmail.com');
//        $mailer->FromName = 'SINCA';
//        $mailer->CharSet  = 'UTF-8';
//        $mailer->Subject  = "Status Ciclo de Ingresos \"$diaSemanaVenezuela\" ".date("h",time()).':00 '.date("A",time()).' (Ayer)';
//        $mailer->Body     = $email;
//        $mailer->ClearAddresses();
//        $mailer->AddAddress('eduardo@newlifeve.com');
//        $mailer->Send();
//        $mailer->ClearAddresses();
//        $mailer->AddAddress('cabinasperu@etelix.com');
//        $mailer->Send();
//
//    }
//    catch(phpmailerException $e){
//        echo $e->errorMessage(); //Pretty error messages from PHPMailer
//    }
//    catch(Exception $e){
//        echo $e->getMessage(); //Boring error messages from anything else!
//    }
echo $email;

/*----------------------- ENVIO DE CORREO ELECTRONICO - FIN ------------------------*/
?>