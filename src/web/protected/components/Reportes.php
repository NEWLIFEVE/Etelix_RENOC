<?php
/**
 * @package components
 * @version 11.1.0
 */
class Reportes extends CApplicationComponent
{
    /**
     * @access public
     */
    public $tipo;
    /**
     * @access protected
     * @var boolean
     */
    protected $type;
    /**
     * Un array conn data sobre los estilos para el reporte generado
     * @var array
     */
    protected $_head;

    /**
     * @access protected
     * @var date
     */
    protected $fecha;
    /**
     * @access public
     * @var boolean
     */
    public $equal;
    /**
     * array que almacena datos ordenados
     * @access protected
     * @var array
     */
    protected $sorted=array();
    /**
     * @var int
     */
    protected $days;
    /**
     * Atributo encargado de almacenar la data traida de base de datos
     * @var array
     */
    protected $_objetos=array();

    /**
     * Init method for the application component mode.
     */
    public function init() 
    {

    }

    /**
     * @access public
     * @param $fecha date fecha para ser consuldada
     * @return $variable string cuerpo de reporte
     */
    public function AltoImpactoVendedor($fecha)
    {
      $variable=AltoImpactoVendedor::Vendedor($fecha);
      return $variable;
    }

    /**
     * @access public
     * @param $fecha date fecha para ser consuldada
     * @return $variable string cuerpo de reporte
     */
    public function Perdidas($fecha)
    {
      $variable=Perdidas::reporte($fecha);
      return $variable;
    }

    /**
     * @access public
     * @param $fecha date fecha para ser consuldada
     * @return $variable string cuerpo de reporte
     */
    public function AltoImpacto($starDate,$endingDate,$type)
    {
        $reporte=new AltoImpacto();
        return $reporte->reporte($starDate,$endingDate,$type);
    }

    /**
     * @access public
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
     * @access public
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
     * @access public
     * @param $fecha date es la fecha que se necesita el reporte
     * @return $variable string con el cuerpo del reporte
     */
    public function PosicionNetaVendedor($fecha)
    {
        $reporte=new PosicionNetaVendedor($fecha);
        $variable=$reporte->reporte();
        return $variable;
    }

    public function DistribucionComercial($nombre)
    {
        $reporte=new DistribucionComercial();
        $reporte->genExcel($nombre);
        return "Revisar Archivo Adjunto";
    }

    /**
     *
     */
    public function Calidad($inicio,$fin,$carrier,$type)
    {
        return Calidad::getHtmlDestinations($inicio,$fin,$carrier,$type);
    }

    /**
     * Metodo encargado de generar el reporte de Ranking Compra Venta
     * @access public
     * @param date $inicio la fecha menor a ser consultada.
     * @param date $fin la fecha mayor a ser consultada, en caso de ser nula la fecha inicio sera la fecha final
     * @return $variable string con el cuerpo del reporte
     */
    public function RankingCompraVenta($starDate,$endingDate)
    {
        $reporte=new RankingCompraVenta();
        return $reporte->reporte($starDate,$endingDate);
    }

    /**
    * Metodo encargado de generar el reporte de Arbol de Trafico
    * @access public
    * @param date $fecha date lafecha que se quiere consultar
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
     * @access public
     * @param date $fecha la fecha que se quiere consultar
     * @param boolean $tipo el tipo de reporte clientes o proveedores, true=clientes default, false=proveedores
     * @param boolean $destino determina el tipo de destino, si es internal o external
     * @return $variable string con el cuerpo del reporte
     */
    public function ArbolTrafico($fecha,$tipo=true,$destino=true)
    {
        ini_set('max_execution_time', 60);
        $reporte=new ArbolTrafico($fecha,$tipo,$destino);
        $variable=$reporte->reporte();
        return $variable;
    }

    /**
     * Genera el reporte de evolucion
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
     * @access public
     * @static
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

    /**
     * @access public
     * @static
     * @param int $pos
     * @return string $color 
     */
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

    /**
     * @access public
     * @static
     * @param int $tipo
     * @return string $color
     */
    public static function colorRankingCV($tipo)
    {
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
            case 4:
                $color="background-color:#0092F4; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 5:
                $color="background-color:#FF5100; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
            case 6:
                $color="background-color:#51DA02; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
                break;
        }
        return $color;
    }

    /**
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
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

    /**
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
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

    /**
     * @access public 
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
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

    /**
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
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

    /**
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
    public static function colorCarrier($var,$alarmaStr=NULL,$alarmaInt=NULL)
    {
        $color=null;
        
        if((isset($alarmaStr) && substr_count($alarmaStr, 'Sin Asignar') >= 1) || (isset($alarmaInt) && $alarmaInt < 0))
        {
            $color="color:white; background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        else
        {
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
     * @access public
     * @static
     * @param $var string a identificar
     * @return string con la fila coloreada
     */
    public static function colorDestino($var)
    {
        if(substr_count($var, 'USA') >= 1 || substr_count($var, 'CANADA') >= 1)
        {
            $color="background-color:#F3F3F3; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
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
            $color="background-color:#8BA0AC; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
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
            $color="background-color:#AED7F3; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'COLOMBIA') >= 1)
        {
            $color="background-color:#BEE2C1; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'VENEZUELA') >= 1)
        {
            $color="background-color:#F0D0AE; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
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
            $color="background-color:#EDF0AE; color:#584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        else
        {
            $color="border:1px solid rgb(121, 115, 115)";
        }
        return $color;
    }

    /**
     * Se encarga de crear una fila con los datos pasados
     * @access public
     * @static
     * @param $etiquetas array lista de etiquetas para lacabeceras
     * @param $estilos string con los estilos para la fila
     * @return string con la fila construida
     */
    public static function cabecera($etiquetas,$estilos)
    {
        $cabecera="<tr>";
        if(count($etiquetas)>1)
        {
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
        }
        else
        {
            $cabecera.="<th style='".$estilos."'>".$etiquetas[0]."</th>";
        }
        $cabecera.="</tr>";
        return $cabecera;
    }

    /**
     * Metodo encargado de realizar los rankings
     * @access public
     * @static
     * @param int $pos valor a rankear
     * @param int $max valor a dividir
     * @param $margin
     * @param $marginText
     * @return $valor int
     */
    public static function ranking($pos, $max, $margin = NULL,$marginText=NULL) 
    {
        if(is_null($margin))
        {
            if($max>10)
            {
                $mitad=($max/2)+1;
                if($pos<$mitad)
                {
                    return $pos;
                }
                else
                {
                    $diferencia=$pos-$mitad;
                    $valor=($mitad-$diferencia)-1;
                    return "-".$valor;
                }
            }
            else
            {
                return $pos;
            }
        }
        else
        {
            if($margin>0)
            {
                return $pos;
            }
            if($marginText=="0,00")
            {
                return 0;
            }
            if($margin<0)
            {
                $negativos=$max-$pos;
                return "-".$negativos;
            }
        }
    }

    /** 
     * Retorna la cantidad de dias de un mes
     * @access protected
     * @static
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
     * @static
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
     * @static
     * @param date $fecha es la fecha que se quiere consultar
     * @param booleam $tipo si es true devuelve un string, si es false devuelve un int
     * @return string el nombre del mes
     * @return int el numero del mes
     */
    protected static function getNameMonth($fecha,$tipo=true)
    {
        $mes=array('January'=>'Enero','February'=>'Febrero','March'=>'Marzo','April'=>'Abril','May'=>'Mayo','June'=>'Junio','July'=>'Julio','August'=>'Agosto','September'=>'Septiembre','October'=>'Octubre','November'=>'Noviembre','December'=>'Diciembre');
        if(strpos($fecha,'-'))
        {
            $arrayFecha=explode('-',$fecha);
        }
        if($tipo==true)
        {
            return $mes[strftime("%B",strtotime($fecha))];
        }
        else
        {
            return $arrayFecha[1];
        }
    }

    /**
     * Retorna la cantidad de dias que existe de una fecha a otra
     * @access protected
     * @static
     * @param date $inicio la fecha menor a consultar
     * @param date $fina la fecha mayor del rango a consultar
     * @return int con el numero de dias entre ambas fechas
     */
    protected static function howManyDaysBetween($inicio,$fin)
    {
        $i=strtotime($inicio);
        $f=strtotime($fin);
        $cant=$f-$i;
        return $cant/(60*60*24);
    }

    /**
     * Funcion que verifica si una fecha en parametro uno es menor que la fecha en el 
     * parametro dos
     * @access protected
     * @static
     * @param date $uno primera fecha
     * @param date $dos segunda fecha
     * @return boolean true si es menor el primero y false si es menor el segundo 
     */
    protected static function isLower($uno,$dos)
    {
        $uno=strtotime($uno);
        $dos=strtotime($dos);
        if($uno<$dos)
        {
            return true;
        }
        elseif ($uno==$dos)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Metodo que retorna la fecha pasada mientras esta sea menor a la fecha maxima,
     * de lo contrario retorna la fecha maxima
     * @access protected
     * @static
     * @param date $nueva fecha nueva generada
     * @param date $max es la maxima fecha que puede tener el parametro $nueva
     * @return date
     */
    protected static function maxDate($nueva,$max)
    {
        if(self::isLower($nueva,$max))
        {
            return $nueva;
        }
        else
        {
            return $max;
        }
    }

    /**
     * metodo encargado de validar el titulo por tabla creada en reportes, si la fecha inicial y final son
     * la de inicio y fin del respectivo mes retorna el nombre del mes, de lo contrario regresa el texto
     * con las fechas.
     * @access public
     * @static
     * @param date $inicio es la fecha de inicio del reporte consultado
     * @param date $fin es la fecha final del reporte consultado
     * @return string
     */
    public static function reportTitle($inicio,$fin)
    {
        $i=explode('-', $inicio);
        $f=explode('-', $fin);
        if($i[2]==1 && $f[2]==self::howManyDays($fin))
        {
            return " ".self::getNameMonth($inicio,true)." ".$f[0];
        }
        elseif($inicio==$fin)
        {
            return " al ".str_replace("-","",$inicio);
        }
        else
        {
            return " desde ".str_replace("-","",$inicio)." al ".str_replace("-","",$fin);
        }
    }

    /**
     * Retorna un array con los apellidos de los managers
     * @access protected
     * @static
     * @return array $array
     */
    protected function getLastNameManagers()
    {
        $array=array();
        $managers=Managers::getManagers();
        foreach ($managers as $key => $manager)
        {
            $array[$manager->lastname]=$manager->lastname;
        }
        return $array;
    }

    /**
     * Recibe un array con una lista y objeto CActiveRecord, un string con el nombre del atributo por el que se ordena,
     * devuelve un array con el atributo pasado como parametro del objeto, la lista es para agregar datos que el objeto no tenga.
     * @access protected
     * @static
     * @param array $lista
     * @param CActiveRecord $objeto
     * @param string $attribute
     * @return array
     */
    protected static function sortByList($lista,$objeto,$attribute)
    {
        $ordenado=$temp=array();
        foreach ($objeto as $key => $value)
        {
            $temp[$value->$attribute]=$value->$attribute;
        }
        foreach($temp as $key => $value)
        {
            $ordenado[]=$lista[$value];
        }
        foreach ($lista as $key => $value)
        {
            if(!isset($temp[$value]))
            {
                $ordenado[]=$value;
            }
        }
        return $ordenado;
    }

    /**
     * Se encarga de verificar las fechas de recibidas por los reportes
     * @access protected
     * @static
     * @param date $start fecha inincio
     * @param date $end fecha fin
     * @return array con los valores start y end validados
     */
    protected static function valDates($start,$end)
    {
        if($end==null)
        {
            $array['endingDate']=$array['startDate']=$start;
            $array['equal']=true;
        }
        else
        {
            $array['startDate']=$start;
            $array['endingDate']=$end;
            $array['equal']=false;
        }
        return $array;
    }

    /**
     * Recibe un objeto CActiveRecord y un string haciendo referencia a un atributo del objeto,
     * retorna un array enlistando los atributos usando un key numerico
     * @access protected
     * @static
     * @param CActiveRecord $objeto
     * @param string $attribute
     * @return array
     */
    protected static function sort($objeto,$attribute)
    {
        $lista=array();
        foreach ($objeto as $key => $value)
        {
            $lista[$key]['attribute']=$value->$attribute;
            $lista[$key]['id']=$value->id;
        }
        return $lista;
    }

    /**
     * Recibe una fecha y retorna un array con la fecha inicio y fin de un mes menos
     * @access public
     * @param date $date
     * @return array
     */
    public function leastOneMonth($date)
    {
        if($date==null) $date=date('Y-m-d');
        $arrayDate['firstday']=date('Y-m-d',strtotime('-1 month',strtotime(Utility::getDayOne($date))));
        $array=explode('-',$arrayDate['firstday']);
        $arrayDate['lastday']=$array[0]."-".$array[1]."-".self::howManyDays($arrayDate['firstday']);
        return $arrayDate;
    }

    /**
     * funcion encargada de verificar que el numero de una columna es el apropiado para ejecutar algo
     * @access protected
     * @static
     * @param int $col en la que esta parado el for
     * @param int $max la cantidad maxima que deberia tener
     * @param int $mul es el factor por el cual se multiplica
     * @return mixed $col si cumple la condicion de lo contrario devuelve false
     */
    public static function validColumn($del,$col,$max,$mul)
    {
        if($max<2)
        {
            if($col==$del+1)
            {
                return true;
            }
        }
        else
        {
            $j=$mul;
            for($i=$del+1; $i<=$col; $i++)
            {
                if($j<$mul)
                {
                    $j+=1;
                }
                else
                {
                    $j=1;
                    if($i==$col)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     *
     */
    public static function validIndex($del,$col,$mul)
    {
        $j=$mul;
        $index=0;
        for($i=$del; $i<=$col; $i++)
        {
            if($j<$mul)
            {
                $j+=1;
            }
            else
            {
                $j=1;
                if($i>$del) $index=$index+1;
            }
        }
        return $index;
    }
    
    /**
     * Retorna el primer dia del siguiente mes a la fecha pasada como parametro
     * @access public
     * @static
     * @param date $date
     * @return date yyyy-mm-dd
     */
    public static function firstDayNextMonth($date)
    {
        $newDate=strtotime('+1 month',strtotime($date));
        $newDate=date('Y-m-d',$newDate);
        $array=explode('-',$newDate);
        return $array[0]."-".$array[1]."-01";
    }

    /**
     * Retorna la fila con el nombre del manager y la posicion indicada
     * @access protected
     * @param int $pos posicion del manager
     * @param string $phrase es el nombre del manager
     * @param string $style es el estilo asignado al tipo de manager
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    protected function _getNames($pos,$phrase,$style,$type=true)
    {
        if($type) 
            return "<td style='".$this->_head[$style]."'>{$pos}</td><td style='".$this->_head[$style]."'>{$phrase}</td>";
        else
            return "<td style='".$this->_head[$style]."'>{$phrase}</td><td style='".$this->_head[$style]."'>{$pos}</td>";
    }

    /**
     * Metodo encargado de colocar un simbolo de subida o bajada en el html
     * @access protected
     * @param int $previous es el valor anterior
     * @param int $actual es el valor actual a revisar
     * @return string
     */
    protected function _upOrDown($previous,$actual)
    {
        if($previous!=null || $previous!="")
        {
            if($actual>$previous)
            {
                return "<font style='color:green;'>&#9650;</font>";
            }
            elseif($actual<$previous)
            {
                return "<font style='color:red;'>&#9660;</font>";
            }
            else
            {
                return "<font>=</font>";
            }
        }
        return "--";
    }

    /**
     * Determina el numero de dias que hay desde la fecha pasada hasta el fin del mes
     * @access protected
     * @param date $date
     * @return void
     */
    protected function _getDays($date)
    {
        $arrayDate=explode('-',$date);
        $newDate=$arrayDate[0]."-".$arrayDate[1]."-".self::howManyDays($date);
        $this->days=self::howManyDaysBetween($date,$newDate);
    }

    /**
     * Retorna el valor pasado como parametro multiplicado por la variable days
     * @access protected
     * @param float $data
     * @return float
     */
    protected function _forecast($data)
    {
        return ($data*$this->days);
    }

    /**
     * calcula el pronostico de cierre
     * @access protected
     * @param array $phrase lista de elementos para iterar, dejar null si se quiere hacer un macth entre ambos elementos
     * @param string $index es la ubicacion dentro del array $this->objetos
     * @param string $average es la ubicacion del promedio dentro del array $this->objetos[$index]
     * @param string $accumulated es la ubicacion del acumulado dentro del array $this->objetos[$index]
     * @return array un array con los datos calculados
     */
    protected function _closeOfTheMonth($phrase=null,$index,$average,$accumulated,$attribute=null)
    {
        $array=array();
        if($attribute===null) $attribute="apellido";
        if($phrase!=null)
        {
            foreach ($phrase as $key => $lastname)
            {
                foreach ($this->_objetos[$index][$average] as $key => $avg)
                {
                    if($avg->$attribute==$lastname)
                    {
                        foreach ($this->_objetos[$index][$accumulated] as $key => $acum)
                        {
                            if($acum->$attribute==$avg->$attribute)
                            {
                                $array[$acum->$attribute]=$acum->margin+$this->_forecast($avg->margin);
                            }
                        }
                    }

                }
            }
            foreach ($phrase as $key => $value)
            {
                if(!isset($array[$value]))
                {
                    $array[$value]=0;
                }
            }
            return $array;
        }
        else
        {
            foreach ($this->_objetos[$index][$average] as $key => $avg)
            {
                foreach ($this->_objetos[$index][$accumulated] as $key => $acum)
                {
                    if($acum->$attribute==$avg->$attribute)
                    {
                        $array[$acum->$attribute]=$acum->margin+$this->_forecast($avg->margin);
                    }
                    else
                    {
                        $array[$acum->$attribute]=$acum->margin;
                    }
                }
            }
        }
    }
}
?>
