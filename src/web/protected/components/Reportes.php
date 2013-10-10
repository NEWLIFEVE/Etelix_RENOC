<?php
/**
* @package components
*/
class reportes extends CApplicationComponent
{
    public $tipo;
    protected $fecha;
    /**
    * Init method for the application component mode.
    */
    public function init() 
    {
        
    }
    /**
    * @access  public
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function AltoImpactoVendedor($fecha)
    {
      $variable=AltoImpactoVendedor::Vendedor($fecha);
      return $variable;
    }
    /**
    * @access  public
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function Perdidas($fecha)
    {
      $variable=Perdidas::reporte($fecha);
      return $variable;
    }
    /**
    * @access  public
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function AltoImpacto($fecha)
    {
        $variable=AltoImpacto::reporte($fecha);
        return $variable;
    }
    /**
    * @access  public
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function AltoImpactoRetail($fecha)
    {
        $variable=AltoImpactoRetail::reporte($fecha);
        return $variable;
    }


    /**
    * Encargado de generar el cuerpo del reporte de posicion neta
    * @access  public
    * @param $fecha date es la fecha que se necesita el reporte
    * @return $variable string con el cuerpo del reporte
    */
    public function PosicionNeta($fecha)
    {
        $variable=PosicionNeta::reporte($fecha);
        return $variable;
    }
    /**
    * Encargado de generar el cuerpo del reporte de posicion neta por vendedor
    * @access  public
    * @param $fecha date es la fecha que se necesita el reporte
    * @return $variable string con el cuerpo del reporte
    */
    public function PosicionNetaVendedor($fecha)
    {
        $reporte=new PosicionNetaVendedor($fecha);
        $variable=$reporte->reporte();
        return $variable;
    }
    /**
    * Metodo encargado de generar el reporte de distribucion comercial
    * @access  public
    * @param $fecha date la fecha que se quiere consultar
    * @return $variable string con el cuerpo del reporte
    */
    public function DistComercialVendedor($fecha)
    {
        $variable=DistComercialVendedor::reporte($fecha);
        return $variable;
    }
    public function DistComercialTerminoPago($fecha)
    {
        $variable=DistComercialTerminoPago::reporte($fecha);
        return $variable;
    }
    public function DistComercialMonetizable($fecha)
    {
        $variable=DistComercialMonetizable::reporte($fecha);
        return $variable;
    }
    public function DistComercialCompany($fecha)
    {
        $variable=DistComercialCompany::reporte($fecha);
        return $variable;
    }
    public function DistComercialCarrier($fecha)
    {
        $variable=DistComercialCarrier::reporte($fecha);
        return $variable;
    }
    /**
    * Metodo encargado de generar el reporte de Ranking Compra Venta
    * @access  public
    * @param $fecha date lafecha que se quiere consultar
    * @return $variable string con el cuerpo del reporte
    */
    public function RankingCompraVenta($fecha)
    {
        $variable=RankingCompraVenta::reporte($fecha);
        return $variable;
    }
    /**
    * Metodo encargado de generar el reporte de Arbol de Trafico
    * @access  public
    * @param $fecha date lafecha que se quiere consultar
    * @param $tipo bollean el tipo de reporte internal o external, true=external default, false=internal
    * @return $variable string con el cuerpo del reporte
    */
    public function ArbolDestino($fecha,$tipo=true)
    {
        ini_set('max_execution_time', 60);
        $reporte=new ArbolDestino($fecha,$tipo);
        $variable=$reporte->reporte();
        return $variable;
    }
    /**
     * Metodo encargado de generar el reporte de Arbol de trafico por clientes y proveedores
     * @access  public
     * @param $fecha date la fecha que se quiere consultar
     * @param $tipo boolean el tipo de reporte clientes o proveedores, true=clientes default, false=proveedores
     * @return $variable string con el cuerpo del reporte
     */
    public function ArbolTrafico($fecha,$tipo=true)
    {
        ini_set('max_execution_time', 60);
        $reporte=new ArbolTrafico($fecha,$tipo);
        $variable=$reporte->reporte();
        return $variable;
    }

    /**
     * genera el reporte de evolucion
     * @access  public 
     * @param $fecha date la fecha que sera consultada
     */
    public function Evolucion($fecha,$nombre)
    {
        $reporte=new Evolucion($fecha);
        $reporte->genExcel($nombre);
        return "Revisar archivo adjunto";
    }

    /**
    * Metodo encargado de pintar las filas de los reportes
    * @access  public
    * @param int $pos es un numero indicando que color debe regresar
    */
    public static function color($pos)
    {
        $color=null;
        $j=0;
        for($i=1;$i<=$pos;$i++)
        { 
            if($j>=4)
            {
                $j=1;
            }
            else
            {
                $j=$j+1;
            }

        }
        switch($j)
        {
            case 1:
                $color="<tr style='background-color:#FFC8AE; color:#584E4E; border: 1px solid rgb(121, 115, 115);'>";
                break;
            case 2:
                $color="<tr style='background-color:#B3A5CF; color:#584E4E; border: 1px solid rgb(121, 115, 115);'>";
                break;
            case 3:
                $color="<tr style='background-color:#AFD699; color:#584E4E; border: 1px solid rgb(121, 115, 115);'>";
                break;
            case 4:
                $color="<tr style='background-color:#F8B6C9; color:#584E4E; border: 1px solid rgb(121, 115, 115);'>";
                break;
        }
        return $color;
    }
    public static function colorEstilo($pos)
    {
        $color=null;
        $j=0;
        for($i=1;$i<=$pos;$i++)
        { 
            if($j>=4)
            {
                $j=1;
            }
            else
            {
                $j=$j+1;
            }
        }
        switch($j)
        {
            case 1:
                $color="background-color:#FFC8AE; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 2:
                $color="background-color:#B3A5CF; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 3:
                $color="background-color:#AFD699; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 4: 
                $color="background-color:#F8B6C9; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
        }
        return $color;
    }
    public static function colorRankingCV($tipo){
        switch($tipo)
        {
            case 1:
                $color="background-color:#AED7F3; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 2:
                $color="background-color:#FFC8AE; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 3:
                $color="background-color:#AFD699; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
        }
        return $color;
    }
    public static function colorVendedor($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0)){
            $color="color:white;";
        }else{
            $color="color:#584E4E;";
        }
        
        if(substr_count($var, 'Iglesias') >= 1)
        {
            $color.="background-color:#fe6500; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Lopez Silva') >= 1)
        {
            $color.="background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Olivar') >= 1)
        {
            $color.="background-color:#DDCBCB; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Robayo') >= 1)
        {
            $color.="background-color:#3BA7DA; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Laguna') >= 1)
        {
            $color.="background-color:#ffcc99; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Pinango') >= 1)
        {
            $color.="background-color:#cc99ff; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Cardenas') >= 1)
        {
            $color.="background-color:rgb(104, 173, 104); border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Barbaran') >= 1)
        {
            $color.="background-color:#ff8080; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Van Der Biest') >= 1)
        {
            $color.="background-color:#c0504d; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Solarte') >= 1)
        {
            $color.="background-color:#ff9900; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Da Rocha') >= 1)
        {
            $color.="background-color:#c0c0c0; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Mirakyan') >= 1)
        {
            $color.="background-color:#00b0f0; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Sin Asignar') >= 1)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Vacante') >= 1)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }
        
        return $color;
    }
    public static function colorTP($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0)){
            $color="color:white;";
        }else{
            $color="color:#584E4E;";
        }
        
        if(substr_count($var, 'P-Mensuales') >= 1)
        {
            $color.="background-color:#fe6500; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'P-Semanales') >= 1)
        {
            $color.="background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '7/3') >= 1)
        {
            $color.="background-color:#DDCBCB; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '7/5') >= 1)
        {
            $color.="background-color:#3BA7DA; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '7/7') >= 1)
        {
            $color.="background-color:#ffcc99; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '15/5') >= 1)
        {
            $color.="background-color:#cc99ff; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '15/7') >= 1)
        {
            $color.="background-color:rgb(104, 173, 104); border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '15/15') >= 1)
        {
            $color.="background-color:#ff8080; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '30/7') >= 1)
        {
            $color.="background-color:#c0504d; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '30/30') >= 1)
        {
            $color.="background-color:#ff9900; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Sin Asignar') >= 1)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }    
        return $color;
    }
    public static function colorM($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0)){
            $color="color:white;";
        }else{
            $color="color:#584E4E;";
        }        
        if(substr_count($var, '100%') >= 1)
        {
            $color.="background-color:#fe6500; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '50%') >= 1)
        {
            $color.="background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, '0%') >= 1)
        {
            $color.="background-color:#DDCBCB; border: 1px solid rgb(121, 115, 115)";
        }      
        elseif(substr_count($var, 'Sin Asignar') >= 1)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }  
        return $color;
    }
    public static function colorCom($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0)){
            $color="color:white;";
        }else{
            $color="color:#584E4E;";
        }        
        if(substr_count($var, 'Etelix NET') >= 1)
        {
            $color.="background-color:#fe6500; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Etelix.com USA') >= 1)
        {
            $color.="background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Etelix.com UK') >= 1)
        {
            $color.="background-color:#DDCBCB; border: 1px solid rgb(121, 115, 115)";
        }       
        elseif(substr_count($var, 'Etelix.com Peru') >= 1)
        {
            $color.="background-color:#ff9900; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Sin Asignar') >= 1)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }  
        return $color;
    }
    public static function colorCarrier($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0)){
            $color="color:white; background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }else{
            $color="color:#584E4E; background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }        
  
        return $color;
    }
    
     function mitad($pos, $posicionNeta) {
        $mitad = ($posicionNeta / 2) + 1;
        if ($pos < $mitad) {
            return $pos;
        } else {
            $diferencia = $pos - $mitad;
            $pos = ($mitad - $diferencia) - 1;
            return "-" . $pos;
        }
    }
    /**
    * @param $var string a identificar
    * @return string con la fila coloreada
    */
    public static function colorDestino($var)
    {
        if(substr_count($var, 'USA') >= 1 || substr_count($var, 'CANADA') >= 1)
        {
            $color="<tr style='background-color:#F3F3F3; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        elseif(substr_count($var, 'SPAIN') >= 1 ||
                substr_count($var, 'ROMANIA') >= 1 ||
                substr_count($var, 'MOROCCO') >= 1 ||
                substr_count($var, 'PHILIPPINES') >= 1 ||
                substr_count($var, 'BELGIUM') >= 1 ||
                substr_count($var, 'CONGO') >= 1 ||
                substr_count($var, 'PAKISTAN') >= 1 ||
                substr_count($var, 'ANTIGUA') >= 1 ||
                substr_count($var, 'UGANDA') >= 1 ||
                substr_count($var, 'NETHERLANDS') >= 1 ||
                substr_count($var, 'THAILAND') >= 1 ||
                substr_count($var, 'CHINA') >= 1 ||
                substr_count($var, 'DENMARK') >= 1 ||
                substr_count($var, 'RUSSIA') >= 1 ||
                substr_count($var, 'AUSTRIA') >= 1 ||
                substr_count($var, 'NORWAY') >= 1 ||
                substr_count($var, 'MAURITANIA') >= 1 ||
                substr_count($var, 'FINLAND') >= 1 ||
                substr_count($var, 'UNITED KINGDOM') >= 1 ||
                substr_count($var, 'ITALY') >= 1 ||
                substr_count($var, 'SWITZERLAND ') >= 1 ||
                substr_count($var, 'VIETNAM') >= 1 ||
                substr_count($var, 'SATELLITE') >= 1 ||
                substr_count($var, 'JAPAN ') >= 1 ||
                substr_count($var, 'IRELAND') >= 1 ||
                substr_count($var, 'ISRAEL ') >= 1 ||
                substr_count($var, 'AUSTRALIA') >= 1)
        {
            $color="<tr style='background-color:#8BA0AC; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        elseif(substr_count($var, 'PERU') >= 1 ||
                substr_count($var, 'CHILE') >= 1 ||
                substr_count($var, 'ECUADOR') >= 1 ||
                substr_count($var, 'PARAGUAY') >= 1 ||
                substr_count($var, 'BRAZIL') >= 1 ||
                substr_count($var, 'BOLIVIA') >= 1 ||
                substr_count($var, 'ARGENTINA') >= 1 ||
                substr_count($var, 'URUGUAY') >= 1)
        {
            $color="<tr style='background-color:#AED7F3; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        elseif(substr_count($var, 'COLOMBIA') >= 1)
        {
            $color="<tr style='background-color:#BEE2C1; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        elseif(substr_count($var, 'VENEZUELA') >= 1)
        {
            $color="<tr style='background-color:#F0D0AE; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        elseif(substr_count($var, 'MEXICO') >= 1 ||
                substr_count($var, 'PANAMA') >= 1 ||
                substr_count($var, 'GUATEMALA') >= 1 ||
                substr_count($var, 'CUBA') >= 1 ||
                substr_count($var, 'PUERTO RICO') >= 1 ||
                substr_count($var, 'BARBADOS') >= 1 ||
                substr_count($var, 'ARUBA') >= 1 ||
                substr_count($var, 'DOMINICAN REPUBLIC ') >= 1 ||
                substr_count($var, 'HONDURAS') >= 1 ||
                substr_count($var, 'HAITI') >= 1 ||
                substr_count($var, 'SALVADOR') >= 1)
        {
            $color="<tr style='background-color:#EDF0AE; color:#584E4E; border: 1px solid rgb(121, 115, 115)'>";
        }
        else
        {
            $color="<tr>";
        }
        return $color;
    }
    /**
    * Se encarga de crear una fila con los datos pasados
    * @param $etiquetas array lista de etiquetas para lacabeceras
    * @param $estilos string con los estilos para la fila
    * @return string con la fila construida
    */
    public static function cabecera($etiquetas,$estilos)
    {
        if(count($etiquetas)>1)
        {
            $cabecera="<tr>";
            if(count($estilos)>1)
            {
                foreach($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos[$key]."'>".$value."</th>";
                }
            }
            else
            {
                foreach ($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos."'>".$value."</th>";
                }
            }
            $cabecera.="</tr>";
        }
        return $cabecera;
    }

    /**
    * Metodo encargado de realizar los rankings
    * @param $posicion int valor a rankear
    * @param $max int valor a dividir
    * @return $valor int
    */
    public static function ranking($pos, $max, $margin = NULL,$marginText=NULL) 
    {
        if (is_null($margin)) {
            if ($max > 10) {
                $mitad = ($max / 2) + 1;
                if ($pos < $mitad) {
                    return $pos;
                } else {
                    $diferencia = $pos - $mitad;
                    $valor = ($mitad - $diferencia) - 1;
                    return "-" . $valor;
                }
            } else {
                return $pos;
            }
        } else {

            if($margin>0){
                return $pos;
            }
            if($marginText=="0,00"){
                return 0;
            }
            if($margin<0){
                $negativos=$max-$pos;
                return "-".$negativos;
            }
        }
    }

    /** 
     * Retorna la cantidad de dias de un mes
     * @access protected
     * @param date $fecha la fecha que se dira la cantidad de dias que tiene el mes
     * @return int 
     */
    protected static function howManyDays($fecha=null)
    {
        if(strpos($fecha,'-'))
        {
            $arrayFecha=explode('-',$fecha);
        }
        if(is_callable('cal_days_in_month'))
        {
            return cal_days_in_month(CAL_GREGORIAN, $arrayFecha[1], $arrayFecha[0]);
        }
        else
        {
            return date('d',mktime(0,0,0,$arrayFecha[1]+1,0,$arrayFecha[0]));
        }
    }

    /**
     * Retorna la cantidad de meses entre dos fechas
     * @access protected
     * @param date $inicio la fecha menor
     * @param date $fin la fecha final
     * @return int el numero de meses
     */
    protected static function howManyMonths($inicio,$fin)
    {
        if(strpos($inicio,'-'))
        {
            $arrayInicio=explode('-', $inicio);
        }
        if(strpos($fin,'-'))
        {
            $arrayFin=explode('-', $fin);
        }
        return $arrayFin[1]-$arrayInicio[1]+1;
    }

    /**
     * Retorna el nombre del mes de una fecha dada
     * @access protected
     * @param date $fecha es la fecha que se quiere consultar
     * @return string el nombre del mes
     */
    protected static function getNameMonth($fecha)
    {
        $mes=array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
        if(strpos($fecha,'-'))
        {
            $arrayFecha=explode('-',$fecha);
        }
        return $mes[$arrayFecha[1]];
    }
}
?>
