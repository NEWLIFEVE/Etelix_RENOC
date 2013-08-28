<?php
/**
* @package components
*/
class reportes extends CApplicationComponent
{
    public $tipo;
    /**
    * Init method for the application component mode.
    */
    public function init() 
    {
        
    }
    /**
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function AltoImpactoVendedor($fecha)
    {
      $variable=AltoImpactoVendedor::Vendedor($fecha);
      return $variable;
    }
    /**
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function Perdidas($fecha)
    {
      $variable=Perdidas::reporte($fecha);
      return $variable;
    }
    /**
    * @param $fecha date fecha para ser consuldada
    * @return $variable string cuerpo de reporte
    */
    public function AltoImpacto($fecha)
    {
        $variable=AltoImpacto::reporte($fecha);
        return $variable;
    }
    /**
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
    * @param $fecha date es la fecha que se necesita el reporte
    * @return $variable string con el cuerpo del reporte
    */
    public function PosicionNeta($fecha)
    {
        $variable=PosicionNeta::reporte($fecha);
        return $variable;
    }
    /**
    * Metodo encargado de generar el reporte de distribucion comercial
    * @param $fecha date la fecha que se quiere consultar
    * @return $variable string con el cuerpo del reporte
    */
    public function DistComercial($fecha)
    {
        $variable=DistComercial::reporte($fecha);
        return $variable;
    }
    /**
    * Metodo encargado de generar el reporte de Ranking Compra Venta
    * @param $fecha date lafecha que se quiere consultar
    * @return $variable string con el cuerpo del reporte
    */
    public function RankingCompraVenta($fecha)
    {
        $variable=RankingCompraVenta::reporte($fecha);
        return $variable;
    }
    /**
    * Metodo encargado de pintar las filas de los reportes
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
    public static function colorVendedor($var)
    {
        $color=null;
        if(substr_count($var, 'Leandro') >= 1)
        {
            $color="background-color:#fe6500; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Juan Carlos Lopez Silva') >= 1)
        {
            $color="background-color:#4aabc5; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Jose Ramon Olivar') >= 1)
        {
            $color="background-color:#333399; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Juan Carlos Robayo') >= 1)
        {
            $color="background-color:#00ffff; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Jaime Laguna') >= 1)
        {
            $color="background-color:#ffcc99; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Carlos Pinango') >= 1)
        {
            $color="background-color:#cc99ff; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Augusto Cardenas') >= 1)
        {
            $color="background-color:#00ff00; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Luis Ernesto Barbaran') >= 1)
        {
            $color="background-color:#ff8080; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Alonso Van Der Biest') >= 1)
        {
            $color="background-color:#c0504d; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Soiret Solarte') >= 1)
        {
            $color="background-color:#ff9900; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Ernesto Da Rocha') >= 1)
        {
            $color="background-color:#c0c0c0; color:584E4E; border: 1px solid rgb(121, 115, 115)";
        }
        elseif(substr_count($var, 'Diana Mirakyan') >= 1)
        {
            $color="background-color:#00b0f0; color:584E4E; border: 1px solid rgb(121, 115, 115)";
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
                substr_count($var, 'BELGIUM') >= 1 ||
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
                substr_count($var, 'CUBA') >= 1 ||
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
    public static function ranking($pos,$max)
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
}
?>
