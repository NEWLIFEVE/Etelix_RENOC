<?php
/**
* @version 7.1.5
* @package reportes
*/
class AltoImpacto extends Reportes
{
    //Promedios
    public $totalAverageCustomerMore;
    public $totalAverageCustomerLess;
    public $totalAverageSupplierMore;
    public $totalAverageSupplierLess;
    public $totalAverageExternalDesMore;
    public $totalAverageExternalDesLess;
    public $totalAverageInternalDesMore;
    public $totalAverageInternalDesLess;
     //Acumulados
    public $totalAccumCustomerMore;
    public $totalAccumCustomerLess;
    public $totalAccumSupplierMore;
    public $totalAccumSupplierLess;
    public $totalAccumExternalDesMore;
    public $totalAccumExternalDesLess;
    public $totalAccumInternalDesMore;
    public $totalAccumInternalDesLess;
     //Proyeccion del mes
    public $totalForecastCustomerMore;
    public $totalForecastCustomerLess;
    public $totalForecastSupplierMore;
    public $totalForecastSupplierLess;
    public $totalForecastExternalDesMore;
    public $totalForecastExternalDesLess;
    public $totalForecastInternalDesMore;
    public $totalForecastInternalDesLess;
     //Mes anterior
    public $totalPreviousCustomerMore;
    public $totalPreviousCustomerLess;
    public $totalPreviousSupplierMore;
    public $totalPreviousSupplierLess;
    public $totalPreviousExternalDesMore;
    public $totalPreviousExternalDesLess;
    public $totalPreviousInternalDesMore;
    public $totalPreviousInternalDesLess;

    function __construct()
    {
        $this->_objetos=array();
        $this->_head=array(
            'styleHead'=>'text-align:center;background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
            'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
            'styleFooterTotal'=>'text-align:center;background-color:#615E5E; color:#FFFFFF;'
            );
    }
	/**
	* Genera la tabla de Alto Impacto (+10$)
    * @access public
	* @param date $start fecha inicio a consultar
    * @param date $ending fecha fin a consultar
    * @param boolean $type true=completo, false=resumido
	* @return string con la tabla armada
	*/
	public function reporte($start,$end,$type=true)
	{
        $this->_getDays($start);
        $this->type=$type;
        //Consigo la data respactiva
        $this->_loopData($start,$end);
        
        //Cuento el numero de objetos en el array
        $num=count($this->_objetos);
        $last=$num-1;
        
        //Loscuento para saber que numero seguir en el resto
        $numCustomer=count($this->_objetos[$last]['customersWithMoreThanTenDollars']);
        $numCustomerLess=count($this->_objetos[$last]['customersWithLessThanTenDollars']);
        $numSupplier=count($this->_objetos[$last]['providersWithMoreThanTenDollars']);
        $numSupplierLess=count($this->_objetos[$last]['providersWithLessThanTenDollars']);
        $numDestinationExt=count($this->_objetos[$last]['externalDestinationsMoreThanTenDollars']);
        $numDestinationExtLess=count($this->_objetos[$last]['externalDestinationsLessThanTenDollars']);
        $numDestinationInt=count($this->_objetos[$last]['internalDestinationsWithMoreThanTenDollars']);
        $numDestinationIntLess=count($this->_objetos[$last]['internalDestinationsWithLessThanTenDollars']);

        //Organizo los datos
        $sorted['customersWithMoreThanTenDollars']=self::sort($this->_objetos[$last]['customersWithMoreThanTenDollars'],'cliente');
        $sorted['customersWithLessThanTenDollars']=self::sort($this->_objetos[$last]['customersWithLessThanTenDollars'],'cliente');
        $sorted['providersWithMoreThanTenDollars']=self::sort($this->_objetos[$last]['providersWithMoreThanTenDollars'],'proveedor');
        $sorted['providersWithLessThanTenDollars']=self::sort($this->_objetos[$last]['providersWithLessThanTenDollars'],'proveedor');
        $sorted['externalDestinationsMoreThanTenDollars']=self::sort($this->_objetos[$last]['externalDestinationsMoreThanTenDollars'],'destino');
        $sorted['externalDestinationsLessThanTenDollars']=self::sort($this->_objetos[$last]['externalDestinationsLessThanTenDollars'],'destino');
        $sorted['internalDestinationsWithMoreThanTenDollars']=self::sort($this->_objetos[$last]['internalDestinationsWithMoreThanTenDollars'],'destino');
        $sorted['internalDestinationsWithLessThanTenDollars']=self::sort($this->_objetos[$last]['internalDestinationsWithLessThanTenDollars'],'destino');
        
        /*
        //Loscuento para saber que numero seguir en el resto
        
        //Organizo los datos
        
        */
        //Les sumo la cantidad de filas que tienen delante
        $numCustomer+=2;
        $numCustomerLess+=6;
        $numSupplier+=6;
        $numSupplierLess+=6;
        $numDestinationExt+=6;
        $numDestinationExtLess+=6;
        $numDestinationInt+=6;
        $numDestinationIntLess+=4;

        $span=21;
        $spanDes=$span+2;
        if(!$this->type && !$this->equal)
        {
            $span=3;
            $spanDes=4;
        }
        if($this->type && !$this->equal)
        {
            $span=12;
            $spanDes=$span+2;
        }
        $body="<table>";
        $total=$numCustomer
              +$numCustomerLess
              +$numSupplier
              +$numSupplierLess
              +$numDestinationExt
              +$numDestinationExtLess
              +$numDestinationInt
              +$numDestinationIntLess+42;
        for($row=1;$row<$total;$row++)
        {
            $body.="<tr>";
            for ($col=1; $col<=3+($num*$span); $col++)
            { 
                //Celda vacia superior izquierda
                if(($row==1 
                 || $row==$numCustomer+5 
                 || $row==$numCustomer+$numCustomerLess+5 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+5) && ($col==1 || $col==3+($num*$span)))
                {
                    $body.="<td colspan='3' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Celda vacia superior izquierda
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+5) && ($col==1 || $col==3+($num*$span)))
                {
                    $body.="<td colspan='4' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }

                //Cabecera superior izquierda de clientes con mas de 10$
                if($row==2 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Clientes (+10)</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                if($row==2 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Clientes (+10)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de clientes con menos de 10$
                if($row==$numCustomer+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Clientes (Resto)</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                if($row==$numCustomer+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Clientes (Resto)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de proveedores con mas de 10$
                if($row==$numCustomer+$numCustomerLess+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Proveedores (+10)</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                if($row==$numCustomer+$numCustomerLess+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Proveedores (+10)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de proveedores con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Proveedores (Resto)</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Proveedores (Resto)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de destinos external con mas de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Destinos Externos (+10)</td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Destinos Externos (+10)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de destinos external con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Destinos Externos (Resto)</td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Destinos Externos (Resto)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de destinos internal con mas de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Destinos Internos (+10)</td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Destinos Internos (+10)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera superior izquierda de destinos internal con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Destinos Internos (Resto)</td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Destinos Internos (Resto)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Nombres de los managers vendedores izquierda con mas de 10$
                if($row>2 && $row<=$numCustomer && $col==1)
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    //le resto las dos filas delante y uno mas para que empiece en cero
                    $body.=$this->_getNames($pos,$sorted['customersWithMoreThanTenDollars'][$row-3],true);
                }
                if($row>2 && $row<=$numCustomer && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    //le resto las dos filas delante y uno mas para que empiece en cero
                    $body.=$this->_getNames($pos,$sorted['customersWithMoreThanTenDollars'][$row-3],false);
                }

                //Nombres de los managers vendedores izquierda con menos de 10$
                if($row>$numCustomer+6  && $row<=$numCustomer+$numCustomerLess && $col==1)
                {
                    //le resto las 8 filas que tiene delante para que continue la cuenta anterior
                    $pos=$row-8;
                    //le resto el total de clientes - 7 filas
                    $body.=$this->_getNames($pos,$sorted['customersWithLessThanTenDollars'][$row-$numCustomer-7],true);
                }
                if($row>$numCustomer+6  && $row<=$numCustomer+$numCustomerLess && $col==3+($num*$span))
                {
                    //le resto las 8 filas que tiene delante para que continue la cuenta anterior
                    $pos=$row-8;
                    //le resto el total de clientes - 7 filas
                    $body.=$this->_getNames($pos,$sorted['customersWithLessThanTenDollars'][$row-$numCustomer-7],false);
                }

                //Nombres de los managers proveedores izquierda con mas de 10$
                if($row>$numCustomer+$numCustomerLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-6;
                    $body.=$this->_getNames($pos,$sorted['providersWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-6;
                    $body.=$this->_getNames($pos,$sorted['providersWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-7],false);
                }

                //Nombres de los managers proveedores izquierda con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-12;
                    $body.=$this->_getNames($pos,$sorted['providersWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+$numSupplier+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-12;
                    $body.=$this->_getNames($pos,$sorted['providersWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-7],false);
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-6;
                    $body.=$this->_getNamesDestination($pos,$sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-6;
                    $body.=$this->_getNamesDestination($pos,$sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7],false);
                }

                //Nombres de los destinos external con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-12;
                    $body.=$this->_getNamesDestination($pos,$sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-12;
                    $body.=$this->_getNamesDestination($pos,$sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7],false);
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-6;
                    $body.=$this->_getNamesDestination($pos,$sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-6;
                    $body.=$this->_getNamesDestination($pos,$sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7],false);
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess && $col==1)
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-12;
                    $body.=$this->_getNamesDestination($pos,$sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7],true);
                }
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess && $col==3+($num*$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-12;
                    $body.=$this->_getNamesDestination($pos,$sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7],false);
                }


                //Cabecera Izquiera de totales gris
                if(($row==$numCustomer+1 
                    || $row==$numCustomer+$numCustomerLess+1 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+1
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+1) && $col==1)
                {
                    $body.="<td></td><td></td><td style='".$this->_head['styleFooter']."'>Total</td>";
                }
                if(($row==$numCustomer+1 
                    || $row==$numCustomer+$numCustomerLess+1 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+1
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+1) && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."'>Total</td><td></td><td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+1) && $col==1)
                {
                    $body.="<td></td><td style='".$this->_head['styleFooter']."' colspan='3'>Total</td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+1
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+1) && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."' colspan='3'>Total</td><td></td>";
                }
                //Cabecera Izquiera de totales oscuro
                //mas uno por encima
                if(($row==$numCustomer+2 
                    || $row==$numCustomer+$numCustomerLess+2 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+2
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+2) && $col==1)
                {
                    $body.="<td></td><td></td><td style='".$this->_head['styleFooterTotal']."'>Total</td>";
                }
                if(($row==$numCustomer+2 
                    || $row==$numCustomer+$numCustomerLess+2 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+2
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+2) && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."'>Total</td><td></td><td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+2) && $col==1)
                {
                    $body.="<td></td><td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+2) && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td><td></td>";
                }
                //Celdas vacias para totales en procentaje
                if(($row==$numCustomer+3 || $row==$numCustomer+4
                    || $row==$numCustomer+$numCustomerLess+3 || $row==$numCustomer+$numCustomerLess+4 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+3 || $row==$numCustomer+$numCustomerLess+$numSupplier+4
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+3 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+4) && ($col==1 || $col==3+($num*$span)))
                {
                    $body.="<td></td><td></td><td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+3 
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+4
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+3
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+4
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+3
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+4
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+3
                    || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+4) && ($col==1 || $col==3+($num*$span)))
                {
                    $body.="<td></td><td></td><td></td><td></td>";
                }
                //Titulo de cada mes para diferenciar la data compradores/vendedores
                if(($row==1
                 || $row==$numCustomer+5
                 || $row==$numCustomer+$numCustomerLess+5 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+5) && self::validColumn(3,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex(3,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+5) && self::validColumn(3,$col,$num,$spanDes))
                {
                    $body.="<td colspan='".$spanDes."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex(3,$col,$spanDes)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //headers de las tablas
                if(($row==2
                 || $row==$numCustomer+6
                 || $row==$numCustomer+$numCustomerLess+6 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+6
                 || $row==$numCustomer+3 
                 || $row==$numCustomer+$numCustomerLess+3 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+3 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+3) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getHeaderCarriers();
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6
                 ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+3
                 ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+3
                 ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+3
                 ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+3) && self::validColumn(3,$col,$num,$spanDes))
                {
                    $body.=$this->_getHeaderDestination();
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }

                //Nombres de los managers vendedores izquierda con mas de 10$
                if($row>2 && $row<=$numCustomer && self::validColumn(3,$col,$num,$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    //le resto las dos filas delante y uno mas para que empiece en cero
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'customersWithMoreThanTenDollars','cliente',$sorted['customersWithMoreThanTenDollars'][$row-3],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //meses de los clientes con mas de 10$
                if($row>2 && $row<=$numCustomer && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    if($this->equal) $body.=$this->_getRowMonths('customers',$sorted['customersWithMoreThanTenDollars'][$row-3],self::colorEstilo($pos));
                }
                //Nombres de los managers vendedores izquierda con menos de 10$
                if($row>$numCustomer+6 && $row<=$numCustomer+$numCustomerLess && self::validColumn(3,$col,$num,$span))
                {
                    //le resto las 8 filas que tiene delante para que continue la cuenta anterior
                    $pos=$row-8;
                    //le resto el total de clientes - 7 filas
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'customersWithLessThanTenDollars','cliente',$sorted['customersWithLessThanTenDollars'][$row-$numCustomer-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //meses anteriores de los clientes con menos de diez dolares
                if($row>$numCustomer+6 && $row<=$numCustomer+$numCustomerLess && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-8;
                    if($this->equal) $body.=$this->_getRowMonths('customers',$sorted['customersWithLessThanTenDollars'][$row-$numCustomer-7],self::colorEstilo($pos));
                }
                //Nombres de los managers proveedores izquierda con mas de 10$
                if($row>$numCustomer+$numCustomerLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier && self::validColumn(3,$col,$num,$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-6;
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'providersWithMoreThanTenDollars','proveedor',$sorted['providersWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //meses anteriores de los clientes con menos de diez dolares
                if($row>$numCustomer+$numCustomerLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-$numCustomer-$numCustomerLess-6;
                    if($this->equal) $body.=$this->_getRowMonths('providers',$sorted['providersWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-7],self::colorEstilo($pos));
                }
                //Nombres de los managers proveedores izquierda con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess && self::validColumn(3,$col,$num,$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-12;
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'providersWithLessThanTenDollars','proveedor',$sorted['providersWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //meses anteriores de los clientes con menos de diez dolares
                if($row>$numCustomer+$numCustomerLess+$numSupplier+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-$numCustomer-$numCustomerLess-12;
                    if($this->equal) $body.=$this->_getRowMonths('providers',$sorted['providersWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-7],self::colorEstilo($pos));
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-6;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'externalDestinationsMoreThanTenDollars','destino',$sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //meses anteriores de los destinos externos con mas de diez dolares
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    if($this->equal) $body.=$this->_getRowDestinationMonths('external',$sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7],self::colorDestino($sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7]['attribute']));
                }
                //Nombres de los destinos external con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-12;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'externalDestinationsLessThanTenDollars','destino',$sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //meses anteriores de los destinos externos con menos de diez dolares
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    if($this->equal) $body.=$this->_getRowDestinationMonths('external',$sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7],self::colorDestino($sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7]['attribute']));
                }
                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-6;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'internalDestinationsWithMoreThanTenDollars','destino',$sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //meses anteriores de los destinos internos con mas de diez dolares
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    if($this->equal) $body.=$this->_getRowDestinationMonths('internal',$sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7],self::colorDestino($sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7]['attribute']));
                }
                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-12;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'internalDestinationsWithLessThanTenDollars','destino',$sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //meses anteriores de los destinos internos con menos de diez dolares
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    if($this->equal) $body.=$this->_getRowDestinationMonths('internal',$sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7],self::colorDestino($sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7]['attribute']));
                }

                //Totales de las tablas 
                if($row==$numCustomer+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'clientsTotalMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con mas de 10$
                if($row==$numCustomer+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsMore('customers','styleFooter');
                }
                if($row==$numCustomer+$numCustomerLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'clientsTotalLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con menos de 10$
                if($row==$numCustomer+$numCustomerLess+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsLess('customers','styleFooter');
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'suppliersTotalMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con mas de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsMore('providers','styleFooter');
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'suppliersTotalLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsLess('providers','styleFooter');
                }

                //total generales
                if(($row==$numCustomer+2||$row==$numCustomer+$numCustomerLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'totalCustomer','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores
                if(($row==$numCustomer+2||$row==$numCustomer+$numCustomerLess+2) && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonths('customers','styleFooterTotal');
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+2||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'totalSuppliers','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+2||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+2) && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonths('providers','styleFooterTotal');
                }

                //Totales en porcentajes
                if($row==$numCustomer+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrierPercentage(self::validIndex(3,$col,$span),'clientsTotalMoreThanTenDollars','totalCustomer','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrierPercentage(self::validIndex(3,$col,$span),'clientsTotalLessThanTenDollars','totalCustomer','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrierPercentage(self::validIndex(3,$col,$span),'suppliersTotalMoreThanTenDollars','totalSuppliers','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrierPercentage(self::validIndex(3,$col,$span),'suppliersTotalLessThanTenDollars','totalSuppliers','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }

                //Totales de destinos _getRowTotalDestination($index,$index2,$style,$type=true)
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalExternalDestinationsMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con mas de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsMore('external','styleFooter');
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalExternalDestinationsLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsLess('external','styleFooter');
                }
                //total generales meses anteriores
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+2 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+2) && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonths('external','styleFooterTotal');
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalInternalDestinationsWithMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con mas de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsMore('internal','styleFooter');
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalInternalDestinationsWithLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //total generales meses anteriores con menos de 10$
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+1 && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonthsLess('internal','styleFooter');
                }
                //total generales meses anteriores
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+2
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+2) && $col==3+($num*$span))
                {
                    if($this->equal) $body.=$this->_getTotalMonths('internal','styleFooterTotal');
                }

                //Totales completos
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+2
                  ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalExternalDestinations','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+2
                  ||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalInternalDestinations','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }

                //Totales en porcentajes destinos
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestinationsPercentage(self::validIndex(3,$col,$span),'totalExternalDestinationsMoreThanTenDollars','totalExternalDestinations','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestinationsPercentage(self::validIndex(3,$col,$span),'totalExternalDestinationsLessThanTenDollars','totalExternalDestinations','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestinationsPercentage(self::validIndex(3,$col,$span),'totalInternalDestinationsWithMoreThanTenDollars','totalInternalDestinations','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestinationsPercentage(self::validIndex(3,$col,$span),'totalInternalDestinationsWithLessThanTenDollars','totalInternalDestinations','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //titulo de los meses
                if(($row==2
                 || $row==$numCustomer+6
                 || $row==$numCustomer+$numCustomerLess+6 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+6
                 || $row==$numCustomer+3 
                 || $row==$numCustomer+$numCustomerLess+3 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+3 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+3
                 || ($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+3
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+3
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+3
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+3)) && $col==3+($num*$span))
                {
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleThirdMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFourthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFifthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSixthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSeventhMonth']."</td>";
                }
                //titulo que incluye los meses anteriores
                if(($row==1
                 || $row==$numCustomer+5
                 || $row==$numCustomer+$numCustomerLess+5 
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+5
                 || $row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+5) && $col==3+($num*$span))
                {
                    if($this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
            }
            $body.="</tr>";
        }
        $body.="</table>";
        return $body;
	}

    /**
     *
     */
    private function _loopData($start,$end)
    {
        //verifico las fechas
        $array=self::valDates($start,$end);
        $startDateTemp=$startDate=$array['startDate'];
        $endingDateTemp=$endingDate=$array['endingDate'];
        $yesterday=DateManagement::calculateDate('-1',$startDateTemp);
        $sevenDaysAgo=DateManagement::calculateDate('-7',$yesterday);
        $firstDay=DateManagement::getDayOne($start);
        $this->equal=$array['equal'];
        $arrayStartTemp=null;
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".DateManagement::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /***/
            //Guardo los datos de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['customersWithMoreThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo el margen del dia anterior de clientes de mas de 10 dolares
            if($this->type && $this->equal) $this->_objetos[$index]['customersYesterday']=$this->_getCarriers($yesterday,$yesterday,true,null,'margin');
            //Guardo el promedio del margen de los ultimos 7 dias de clientes con mas de 10$
            if($this->type && $this->equal) $this->_objetos[$index]['customersAverage']=$this->_getAvgCarriers($sevenDaysAgo,$yesterday,true);
            //
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalAverage']=$this->_getTotalAvgCarriers($sevenDaysAgo,$yesterday,true);
            //Guardo el margen acumulado por los clientes en lo que va de mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersAccumulated']=$this->_getCarriers($firstDay,$startDate,true,null,'margin');
            //total acumulado completo
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalAccumulated']=$this->_getTotalCarriers($firstDay,$startDate,true,null,'margin');
            //Guardo las proyecciones para el final del mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersForecast']=$this->_closeOfTheMonth(null,$index,'customersAverage','customersAccumulated','cliente');
            //Total de la proyeccion de clientes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalForecast']=array_sum($this->_objetos[$index]['customersForecast']);
            //Guardo los totales del mes anterior de los clientes
            if($this->type && $this->equal) $this->_objetos[$index]['customersPreviousMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true,null,'margin');
            //Total de mes acumulado anterior
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalPreviousMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true,null,'margin');
            // Titulo del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['titleThirdMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Guardo el tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersThirdMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,null,'margin');
            //Guardo el total del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalMoreThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalLessThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,false,'margin');
            // Titulo del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['titleFourthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Guardo el cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersFourthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,null,'margin');
            //Guardo el total del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalMoreFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalLessFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,false,'margin');
            // Titulo del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['titleFifthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Guardo el quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersFifthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,null,'margin');
            //Guardo el total del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalMoreFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalLessFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,false,'margin');
            // Titulo del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['titleSixthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Guardo el sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersSixthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,null,'margin');
            //Guardo el total del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalMoreSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalLessSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,false,'margin');
            // Titulo del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['titleSeventhMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            //Guardo el septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersSeventhMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,null,'margin');
            //Guardo el total del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalMoreSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['customersTotalLessSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,false,'margin');
            //Guardo los datos de los totales de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['clientsTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,true);
            // Guardo los datos de los totales de ayer de los clientes con mas de 10 dolares de ganancia
            if($this->type && $this->equal) $this->_objetos[$index]['clientsTotalMoreThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,true,true,'margin');
            //Guardo los datos de los totales de todos los clientes
            $this->_objetos[$index]['totalCustomer']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,null);
            //Guardo los datos de los totales de todos los clientes del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['totalCustomerYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,true,null,'margin');
            //Guardo los datos de los clientes con menos de 10 dolares de ganancia 
            $this->_objetos[$index]['customersWithLessThanTenDollars']=$this->_getCarriers($startDate,$endingDate,true,false);
            //Guardo los datos de los totales de los clientes con menis de 10 dolares de ganancia
            $this->_objetos[$index]['clientsTotalLessThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,false);
            //guardo los datos de los totales de los clientes con menos de 10 dolares de gananacia del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['clientsTotalLessThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,true,false,'margin');

            /***/
            //Guardo los datos de los proveedores con mas de 10 dolares de ganancia
            $this->_objetos[$index]['providersWithMoreThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los proveedores con mas de 10 dolares de ganacia del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['providersYesterday']=$this->_getCarriers($yesterday,$yesterday,false,null,'margin');
            //Guardo los proomedios de los proveedores con mas de 10 dolares de ganancia
            if($this->type && $this->equal) $this->_objetos[$index]['providersAverage']=$this->_getAvgCarriers($sevenDaysAgo,$yesterday,false);
            //
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalAverage']=$this->_getTotalAvgCarriers($sevenDaysAgo,$yesterday,false);
            //Guardo el margen acumulado por los proveedores en lo que va de mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersAccumulated']=$this->_getCarriers($firstDay,$startDate,false,null,'margin');
            //Totales completos acumulados
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalAccumulated']=$this->_getTotalCarriers($firstDay,$startDate,false,null,'margin');
            //Guardo las proyecciones para el final del mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersForecast']=$this->_closeOfTheMonth(null,$index,'providersAverage','providersAccumulated','proveedor');
            //Totales de proyeccion
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalForecast']=array_sum($this->_objetos[$index]['providersForecast']);
            //Guardo los totales del mes anterior de los providers
            if($this->type && $this->equal) $this->_objetos[$index]['providersPreviousMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false,null,'margin');
            //Total de mes acumulado anterior
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalPreviousMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false,null,'margin');
            // Guardo los totales del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersThirdMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,null,'margin');
            // Guardo el total del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalMoreThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalLessThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,false,'margin');
            // Guardo totales del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersFourthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,null,'margin');
            // Guardo el total del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalMoreFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalLessFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,false,'margin');
            // Guardo totales del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersFifthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,null,'margin');
            // Guardo el total del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalMoreFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalLessFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,false,'margin');
            // Guardo los totales del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersSixthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,null,'margin');
            // Guardo el total del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalMoreSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalLessSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,false,'margin');
            // Guardo totales del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['providersSeventhMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,null,'margin');
            //
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalMoreSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['providersTotalLessSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,false,'margin');
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia
            $this->_objetos[$index]['suppliersTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['suppliersTotalMoreThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,false,true,'margin');
            //Guardo los datos de los totales de todos los proveedores
            $this->_objetos[$index]['totalSuppliers']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,null);
            //Guardo los datos de los totales de todos los proveedores del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['totalSuppliersYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,false,null,'margin');
            //Guardo los datos de los proveedores con menos de 10 dolares de ganancia
            $this->_objetos[$index]['providersWithLessThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,false,false);
            //Gurado los datos de los totales de los proveedores con menos de 10 dolares de ganancia
            $this->_objetos[$index]['suppliersTotalLessThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los proveedores con menos de 10 dolares de ganancia
            if($this->type && $this->equal) $this->_objetos[$index]['suppliersTotalLessThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,false,false,'margin');
            /***/
            //Guardo los datos de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['externalDestinationsMoreThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales externos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['externalYesterday']=$this->_getDestination($yesterday,$yesterday,true,null,'margin');
            //Guardo los promedios de los destinos externos
            if($this->type && $this->equal) $this->_objetos[$index]['externalAverage']=$this->_getAvgDestination($sevenDaysAgo,$yesterday,true);
            //
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalAverage']=$this->_getTotalAvgDestination($sevenDaysAgo,$yesterday,true);
            //guardo el acumulado de los destinos internos esterno en lo que va de mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalAccumulated']=$this->_getDestination($firstDay,$startDate,true,null,'margin');
            //Totales completos acumulados
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalAccumulated']=$this-> _getTotalDestination($firstDay,$startDate,true,null,'margin');
            //Guardo las proyecciones para el final del mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalForecast']=$this->_closeOfTheMonth(null,$index,'externalAverage','externalAccumulated','destino');
            //Totales de proyeccion
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalForecast']=array_sum($this->_objetos[$index]['externalForecast']);
            //Guardo los totales del mes anterior de los providers
            if($this->type && $this->equal) $this->_objetos[$index]['externalPreviousMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true,null,'margin');
            //Totales completos del mes anterior
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalPreviousMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true,null,'margin');
            // Guardo los totales de los destinos externos del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalThirdMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,null,'margin');
            // Guardo el total completo de los destinos externos del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,null,'margin');
            //more
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalMoreThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,true,'margin');
            //less
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalLessThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true,null,'margin');
            // Guardo los totales de los destinos externos del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalFourthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,null,'margin');
            // Guardo el total completo de los destinos externos del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalMoreFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalLessFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true,false,'margin');
            // Guardo los totales de los destinos externos del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalFifthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,null,'margin');
            // Guardo el total completo de los destinos externos del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalMoreFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalLessFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true,false,'margin');
            // Guardo los totales de los destinos externos del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalSixthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,null,'margin');
            // Guardo el total completo de los destinos externos del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalMoreSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalLessSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true,false,'margin');
            // Guardo los totales de los destinos externos del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalSeventhMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,null,'margin');
            // Guardo el total completo de los destinos externos del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalMoreSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['externalTotalLessSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true,false,'margin');
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalExternalDestinationsMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalExternalDestinationsMoreThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,true,true,'margin');
            //Guardo los datos de los totales de los destinos externos
            $this->_objetos[$index]['totalExternalDestinations']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,null);
            //Guardo los datos de los totales de los destinos externos de dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalExternalDestinationsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,true,null,'margin');
            //Guardo los datos de los destinos externos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['externalDestinationsLessThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,true,false);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalExternalDestinationsLessThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,false);
            //Guardo los datos de los totales de los destinos externos con menos de 10 dolares de ganancia
            if($this->type && $this->equal) $this->_objetos[$index]['totalExternalDestinationsLessThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,true,false,'margin');
            /***/
            //Guardo los datos de los destinos internos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['internalDestinationsWithMoreThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los destinos internos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['internalYesterday']=$this->_getDestination($yesterday,$yesterday,false,null,'margin');
            //Guardo el promedio de los destinos internos
            if($this->type && $this->equal) $this->_objetos[$index]['internalAverage']=$this->_getAvgDestination($sevenDaysAgo,$yesterday,false);
            //
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalAverage']=$this->_getTotalAvgDestination($sevenDaysAgo,$yesterday,false);
            //Guardo el acumulado de destinos internos en lo que va de mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalAccumulated']=$this->_getDestination($firstDay,$startDate,false,null,'margin');
            //Totales completos acumulados
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalAccumulated']=$this-> _getTotalDestination($firstDay,$startDate,false,null,'margin');
            //Guardo las proyecciones para el final del mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalForecast']=$this->_closeOfTheMonth(null,$index,'internalAverage','internalAccumulated','destino');
            //Totales de proyeccion
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalForecast']=array_sum($this->_objetos[$index]['externalForecast']);
            //Guardo los totales del mes anterior de los providers
            if($this->type && $this->equal) $this->_objetos[$index]['internalPreviousMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false,null,'margin');
            //Totales completos del mes anterior
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalPreviousMonth']=$this-> _getTotalDestination(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false,null,'margin');
            // Guardo los totales de los destinos internos del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalThirdMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,null,'margin');
            // Guardo el total completo de los destinos internos del tercer mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalMoreThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalLessThirdMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false,false,'margin');
            // Guardo los totales de los destinos internos del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalFourthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,null,'margin');
            // Guardo el total completo de los destinos internos del cuarto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalMoreFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,true,'margin');
            //Less            
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalLessFourthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false,false,'margin');
            // Guardo los totales de los destinos internos del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalFifthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,null,'margin');
            // Guardo el total completo de los destinos internos del quinto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalMoreFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalLessFifthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false,false,'margin');
            // Guardo los totales de los destinos internos del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalSixthMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,null,'margin');
            // Guardo el total completo de los destinos internos del sexto mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalMoreSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalLessSixthMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false,false,'margin');
            // Guardo los totales de los destinos internos del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalSeventhMonth']=$this->_getDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,null,'margin');
            // Guardo el total completo de los destinos internos del septimo mes
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,null,'margin');
            //More
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalMoreSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,true,'margin');
            //Less
            if($this->type && $this->equal) $this->_objetos[$index]['internalTotalLessSeventhMonth']=$this->_getTotalDestination(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false,false,'margin');
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsWithMoreThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,false,true,'margin');
            //Guardo los datos de los totales de los destinos internos
            $this->_objetos[$index]['totalInternalDestinations']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,null);
            //Guardo los datos de los totales de los destinos internos del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,false,null,'margin');
            //Guardo los datos de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['internalDestinationsWithLessThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithLessThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsWithLessThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,false,false,'margin');

            /*Itero la fecha*/
            $startDateTemp=DateManagement::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     * Encargado de traer los datos de los carriers
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=+10$, false=-10$, null=todos
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return array $models
     */
    private function _getCarriers($startDate,$endingDate,$typeCarrier=true,$type=null,$attribute=null)
    {
        $condition="x.margin<10";
        if($type) $condition="x.margin>=10";

        $carrier="id_carrier_supplier";
        if($typeCarrier) $carrier="id_carrier_customer";

        $title="proveedor";
        if($typeCarrier) $title="cliente";

        $data="c.name AS ".$title.", x.".$carrier." AS id, x.".$attribute." ";
        if($attribute==null) $data="c.name AS ".$title.", x.".$carrier." AS id, x.total_calls, x.complete_calls, x.minutes, x.asr,x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, cs.posicion_neta AS posicion_neta ";
        
        $where="WHERE ".$condition." AND x.".$carrier."=c.id AND x.".$carrier."=cs.id";
        if($type===null) $where="WHERE x.".$carrier."=c.id AND x.".$carrier."=cs.id";
        
        $sql="SELECT {$data}
              FROM(SELECT {$carrier}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$carrier}
                   ORDER BY margin DESC) x,
                  (SELECT id,SUM(vrevenue-ccost) AS posicion_neta
                   FROM(SELECT id_carrier_customer AS id,SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS ccost
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_customer
                        UNION
                        SELECT id_carrier_supplier AS id,CAST(0 AS double precision) AS vrevenue, SUM(cost) AS ccost
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_supplier
                        ORDER BY id ASC)t
                   GROUP BY id
                   ORDER BY posicion_neta DESC)cs,
                   carrier c
              {$where}
              ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * trae el total de todos los carriers
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=margen mayor a 10$, false=margen menor a 10$, null=todos
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return object $model
     */
    private function _getTotalCarriers($startDate,$endingDate,$typeCarrier=true,$type=null,$attribute=null)
    {
        $condicion="WHERE margin<10";
        if($type) $condicion="WHERE margin>=10";

        $select="id_carrier_supplier";
        if($typeCarrier) $select="id_carrier_customer";
        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(total_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100)/SUM(total_calls) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE SUM(minutes)/SUM(complete_calls) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(total_calls)=0 THEN 0 ELSE SUM(pdd)/SUM(total_calls) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(cost)=0 THEN 0 ELSE ((SUM(revenue)*100)/SUM(cost))-100 END AS margin_percentage";

        if($type===null) $condicion="";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance
               {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna la data de los destinos
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$, null=cualquiera
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return array $models
     */
    private function _getDestination($startDate,$endingDate,$typeDestination=true,$type=null,$attribute=null)
    {
        $condicion="x.margin<10";
        if($type!=false) $condicion="x.margin>=10";
        $table="destination_int";
        if($typeDestination) $table="destination";
        $select="id_destination_int";
        if($typeDestination) $select="id_destination";
        $data="d.name AS destino, x.{$attribute}";
        if($attribute==null) $data="d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, CASE WHEN x.minutes=0 THEN 0 ELSE(x.cost/x.minutes)*100 END AS costmin, CASE WHEN x.minutes=0 THEN 0 ELSE(x.revenue/x.minutes)*100 END AS ratemin, CASE WHEN x.minutes=0 THEN 0 ELSE((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) END AS marginmin";
        $completa="WHERE {$condicion} AND x.{$select}=d.id";
        if($type===null) $completa="WHERE x.{$select}=d.id";
        $sql="SELECT {$data}
                      FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                           FROM balance
                           WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                           GROUP BY {$select}
                           ORDER BY margin DESC) x, {$table} d
                      {$completa}
                      ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Retorna el total de la data de los destinos
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return object $model
     */
    private function _getTotalDestination($startDate,$endingDate,$typeDestination=true,$type=true,$attribute=null)
    {
        $condicion="WHERE margin<10";
        if($type) $condicion="WHERE margin>=10";
        if($type===null) $condicion="";

        $select="id_destination_int";
        if($typeDestination) $select="id_destination";

        $table="destination_int";
        if($typeDestination) $table="destination";

        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage";

        $sql="SELECT {$data}
              FROM (SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                    GROUP BY {$select}
                    ORDER BY margin DESC) balance
              {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna un array con los promedios de los carriers
     * @access private
     * @param date $starDate fecha inicio de la consulta
     * @param date $edingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=+10$, false=-10$
     * @return array 
     */
    private function _getAvgCarriers($startDate,$endingDate,$typeCarrier=true)
    {
        $titulo="proveedor";
        $carrier="id_carrier_supplier";
        if($typeCarrier) $titulo="cliente";
        if($typeCarrier) $carrier="id_carrier_customer";
        $sql="SELECT c.name AS {$titulo}, x.{$carrier}, AVG(x.margin) AS margin
              FROM(SELECT date_balance, {$carrier}, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$carrier}, date_balance
                   ORDER BY margin DESC) x, carrier c
              WHERE x.{$carrier}=c.id
              GROUP BY x.{$carrier}, c.name";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     *
     */
    private function _getTotalAvgCarriers($startDate,$endingDate,$typeCarrier=true)
    {
        $titulo="proveedor";
        $carrier="id_carrier_supplier";
        if($typeCarrier) $titulo="cliente";
        if($typeCarrier) $carrier="id_carrier_customer";
        $sql="SELECT SUM(d.margin) AS margin
              FROM(SELECT c.name AS {$titulo}, x.{$carrier}, AVG(x.margin) AS margin
                   FROM(SELECT date_balance, {$carrier}, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                        GROUP BY {$carrier}, date_balance
                        ORDER BY margin DESC) x, carrier c
                   WHERE x.{$carrier}=c.id
                   GROUP BY x.{$carrier}, c.name)d";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna un array con los promedios de los destinos
     * @access private
     * @param date $startDate es la fecha de inicio de la consulta
     * @param date $endingDate es la fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @return string 
     */
    private function _getAvgDestination($startDate,$endingDate,$typeDestination=true)
    {
        $destination="id_destination_int";
        if($typeDestination) $destination="id_destination";
        $table="destination_int";
        if($typeDestination) $table="destination";
        
        $sql="SELECT d.name AS destino, AVG(b.margin) AS margin
              FROM(SELECT date_balance, {$destination}, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$destination}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$destination} IS NOT NULL
                   GROUP BY {$destination}, date_balance
                   ORDER BY margin DESC) b, {$table} d
              WHERE b.{$destination}=d.id
              GROUP BY d.name";
        return Balance::model()->findAllBySql($sql);   
    }

    /** 
     *
     */
    private function _getTotalAvgDestination($startDate,$endingDate,$typeDestination=true)
    {
        $destination="id_destination_int";
        if($typeDestination) $destination="id_destination";
        $table="destination_int";
        if($typeDestination) $table="destination";
        
        $sql="SELECT SUM(d.margin) AS margin
              FROM(SELECT d.name AS destino, AVG(b.margin) AS margin
                   FROM(SELECT date_balance, {$destination}, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$destination}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$destination} IS NOT NULL
                        GROUP BY {$destination}, date_balance
                        ORDER BY margin DESC) b, {$table} d
                   WHERE b.{$destination}=d.id
                   GROUP BY d.name)d";
        return Balance::model()->findBySql($sql);   
    }

    /**
     * Retorna la fila con el nombre del manager y la posicion indicada
     * @access protected
     * @param int $pos posicion del manager
     * @param array $value datos del carrier
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    protected function _getNames($pos,$value,$type=true)
    {
        $style=self::colorEstilo($pos);
        if($type) 
            return "<td style='".$style."'>{$pos}</td><td style='".$style."'>{$value['attribute']}</td><td style='".$style."'>".CarrierManagers::getManager($value['id'])."</td>";
        else
            return "<td style='".$style."'>".CarrierManagers::getManager($value['id'])."</td><td style='".$style."'>{$value['attribute']}</td><td style='".$style."'>{$pos}</td>";
    }

    /**
     * Retorna la fila con el nombre del destino y la posicion indicada
     * @access protected
     * @param int $pos posicion del destino
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    protected function _getNamesDestination($pos,$value,$type=true)
    {
        $style=self::colorDestino($value['attribute']);
        if($type) 
            return "<td style='".$style."'>{$pos}</td><td style='".$style."' colspan='3'>{$value['attribute']}</td>";
        else
            return "<td style='".$style."' colspan='3'>{$value['attribute']}</td><td style='".$style."'>{$pos}</td>";
    }

    /**
     * Retorna la cabecera de la data de managers
     * @access private
     * @return string celdas construidas
     */
    private function _getHeaderCarriers()
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=null;
        if($this->type) $c1="<td style='".$this->_head['styleHead']."'>TotalCalls</td>";
        if($this->type) $c2="<td style='".$this->_head['styleHead']."'>CompleteCalls</td>";
        if($this->type) $c3="<td style='".$this->_head['styleHead']."'>Minutes</td>";
        if($this->type) $c4="<td style='".$this->_head['styleHead']."'>ASR</td>";
        if($this->type) $c5="<td style='".$this->_head['styleHead']."'>ACD</td>";
        if($this->type) $c6="<td style='".$this->_head['styleHead']."'>PDD</td>";
        $c7="<td style='".$this->_head['styleHead']."'>Cost</td>";
        $c8="<td style='".$this->_head['styleHead']."'>Revenue</td>";
        $c9="<td style='".$this->_head['styleHead']."'>Margin</td>";
        if($this->type && $this->equal) $c10="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c11="<td style='".$this->_head['styleHead']."'>Dia Anterior</td>";
        if($this->type && $this->equal) $c12="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c13="<td style='".$this->_head['styleHead']."'>Promedio 7D</td>";
        if($this->type && $this->equal) $c14="<td style='".$this->_head['styleHead']."' colspan='2'>Acumulado Mes</td>";
        if($this->type && $this->equal) $c15="<td style='".$this->_head['styleHead']."'>Proyeccion Mes</td>";
        if($this->type && $this->equal) $c16="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c17="<td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        if($this->type) $c18="<td style='".$this->_head['styleHead']."' colspan='2'>Margin%</td>";
        if($this->type) $c19="<td style='".$this->_head['styleHead']."'>PN</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19;
    }

    /**
     * Retorna la cabecera de la data de managers
     * @access private
     * @return string celdas construidas
     */
    private function _getHeaderDestination()
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        if($this->type) $c1="<td style='".$this->_head['styleHead']."'>TotalCalls</td>";
        if($this->type) $c2="<td style='".$this->_head['styleHead']."'>CompleteCalls</td>";
        if($this->type) $c3="<td style='".$this->_head['styleHead']."'>Minutes</td>";
        if($this->type) $c4="<td style='".$this->_head['styleHead']."'>ASR</td>";
        if($this->type) $c5="<td style='".$this->_head['styleHead']."'>ACD</td>";
        if($this->type) $c6="<td style='".$this->_head['styleHead']."'>PDD</td>";
        $c7="<td style='".$this->_head['styleHead']."'>Cost</td>";
        $c8="<td style='".$this->_head['styleHead']."'>Revenue</td>";
        $c9="<td style='".$this->_head['styleHead']."' colspan='2'>Margin</td>";
        if($this->type && $this->equal) $c10="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c11="<td style='".$this->_head['styleHead']."'>Dia Anterior</td>";
        if($this->type && $this->equal) $c12="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c13="<td style='".$this->_head['styleHead']."'>Promedio 7D</td>";
        if($this->type && $this->equal) $c14="<td style='".$this->_head['styleHead']."'>Acumulado Mes</td>";
        if($this->type && $this->equal) $c15="<td style='".$this->_head['styleHead']."' colspan='2'>Proyeccion Mes</td>";
        if($this->type && $this->equal) $c16="<td style='".$this->_head['styleHead']."'></td>";
        if($this->type && $this->equal) $c17="<td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        if($this->type) $c18="<td style='".$this->_head['styleHead']."'>Margin%</td>";
        if($this->type) $c19="<td style='".$this->_head['styleHead']."'>Cost/Min</td>";
        if($this->type) $c20="<td style='".$this->_head['styleHead']."'>Rate/Min </td>";
        if($this->type) $c21="<td style='".$this->_head['styleHead']."'>Margin/Min</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    }
    
    /**
     * Retorna las celdas con la data que coincida dentro del index consultado y el apellido pasado como parametro
     * @access private
     * @param string $index es el index superior donde se encutra la data
     * @param string $index2 es el index inferior donde se encuentra la data
     * @param string $attribute es el atributo con el que el siguiente parametro deberá coincidir
     * @param string $phrase el dato que debe coincidir
     * @return string
     */
    private function _getRow($index,$index2,$attribute,$phrase,$style)
    {
        $previous=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=null;
        $margin=null;
        $otro="providers";
        foreach ($this->_objetos[$index][$index2] as $key => $value)
        {
            if($value->$attribute == $phrase['attribute'])
            {               
                if($this->type) $c1="<td style='".$style."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
                if($this->type) $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
                if($this->type) $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                if($this->type) $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
                if($this->type) $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
                if($this->type) $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
                $c8="<td style='".$style."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $c9="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $margin=$value->margin;
                if($this->type) $c18="<td style='".$style."' colspan='2'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
                if($this->type) $c19="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        if($this->equal)
        {
            if(strstr($index2, 'customers')!=false) $otro="customers";
            foreach ($this->_objetos[$index][$otro."Yesterday"] as $key => $yesterday)
            {
                if($yesterday->$attribute==$phrase['attribute'])
                {
                    $c10="<td style='".$style."'>".$this->_upOrDown($yesterday->margin,$margin)."</td>";
                    $c11="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
                }
            }
            foreach ($this->_objetos[$index][$otro.'Average'] as $key => $average)
            {
                if($average->$attribute==$phrase['attribute'])
                {
                    $c12="<td style='".$style."'>".$this->_upOrDown($average->margin,$margin)."</td>";
                    $c13="<td style='".$style."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
                    if(strstr($index2, 'customersWithMore')!=false) $this->totalAverageCustomerMore+=$average->margin;
                    if(strstr($index2, 'customersWithLess')!=false) $this->totalAverageCustomerLess+=$average->margin;
                    if(strstr($index2, 'providersWithMore')!=false) $this->totalAverageSupplierMore+=$average->margin;
                    if(strstr($index2, 'providersWithLess')!=false) $this->totalAverageCustomerLess+=$average->margin;
                }
            }
            foreach ($this->_objetos[$index][$otro.'Accumulated'] as $key => $accumulated)
            {
                if($accumulated->$attribute==$phrase['attribute'])
                {
                    $c14="<td style='".$style."' colspan='2'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
                    if(strstr($index2, 'customersWithMore')!=false) $this->totalAccumCustomerMore+=$accumulated->margin;
                    if(strstr($index2, 'customersWithLess')!=false) $this->totalAccumCustomerLess+=$accumulated->margin;
                    if(strstr($index2, 'providersWithMore')!=false) $this->totalAccumSupplierMore+=$accumulated->margin;
                    if(strstr($index2, 'providersWithLess')!=false) $this->totalAccumSupplierLess+=$accumulated->margin;
                }
            }
            $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']])."</td>";
            if(strstr($index2, 'customersWithMore')!=false) $this->totalForecastCustomerMore+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'customersWithLess')!=false) $this->totalForecastCustomerLess+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'providersWithMore')!=false) $this->totalForecastSupplierMore+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'providersWithLess')!=false) $this->totalForecastSupplierLess+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            foreach ($this->_objetos[$index][$otro.'PreviousMonth'] as $key => $month)
            {
                if($month->$attribute==$phrase['attribute'])
                {
                    $c17="<td style='".$style."'>".Yii::app()->format->format_decimal($month->margin)."</td>";
                    $previous=$month->margin;
                    if(strstr($index2, 'customersWithMore')!=false) $this->totalPreviousCustomerMore+=(float)$month->margin;
                    if(strstr($index2, 'customersWithLess')!=false) $this->totalPreviousCustomerLess+=(float)$month->margin;
                    if(strstr($index2, 'providersWithMore')!=false) $this->totalPreviousSupplierMore+=(float)$month->margin;
                    if(strstr($index2, 'providersWithLess')!=false) $this->totalPreviousSupplierLess+=(float)$month->margin;
                }
            }
            $c16="<td style='".$style."'>".$this->_upOrDown($previous,$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']])."</td>";
        }
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($this->type)
        {
            if($c1==null) $c1="<td style='".$style."'>--</td>";
            if($c2==null) $c2="<td style='".$style."'>--</td>";
            if($c3==null) $c3="<td style='".$style."'>--</td>";
            if($c4==null) $c4="<td style='".$style."'>--</td>";
            if($c5==null) $c5="<td style='".$style."'>--</td>";
            if($c6==null) $c6="<td style='".$style."'>--</td>";
            if($c18==null) $c18="<td style='".$style."' colspan='2'>--</td>";
            if($c19==null) $c19="<td style='".$style."'>--</td>";
        }
        if($this->type && $this->equal)
        {
            if($c10==null) $c10="<td style='".$style."'>--</td>";
            if($c11==null) $c11="<td style='".$style."'>--</td>";
            if($c12==null) $c12="<td style='".$style."'>--</td>";
            if($c13==null) $c13="<td style='".$style."'>--</td>";
            if($c14==null) $c14="<td style='".$style."' colspan='2'>--</td>";
            if($c15==null) $c15="<td style='".$style."'>--</td>";
            if($c16==null) $c16="<td style='".$style."'>--</td>";
            if($c17==null) $c17="<td style='".$style."'>--</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19;
    }

    /**
     *
     */
    private function _getRowMonths($index,$phrase,$style)
    {
        $name="proveedor";
        if($index=="customers") $name="cliente";

        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;        
        $margin=$this->_objetos[0][$index.'Forecast'][$phrase['attribute']];
        foreach ($this->_objetos[0][$index.'ThirdMonth'] as $key => $value)
        {
            if($value->$name == $phrase['attribute'])
            {
                $c1="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FourthMonth'] as $key => $value)
        {
            if($value->$name == $phrase['attribute'])
            {
                $c3="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FifthMonth'] as $key => $value)
        {
            if($value->$name == $phrase['attribute'])
            {
                $c5="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SixthMonth'] as $key => $value)
        {
            if($value->$name == $phrase['attribute'])
            {
                $c7="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SeventhMonth'] as $key => $value)
        {
            if($value->$name == $phrase['attribute'])
            {
                $c9="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        if($c1==null) $c1="<td style='".$style."'>--</td>";
        if($c2==null) $c2="<td style='".$style."'>--</td>";
        if($c3==null) $c3="<td style='".$style."'>--</td>";
        if($c4==null) $c4="<td style='".$style."'>--</td>";
        if($c5==null) $c5="<td style='".$style."'>--</td>";
        if($c6==null) $c6="<td style='".$style."'>--</td>";
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($c10==null) $c10="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     *
     */
    private function _getTotalMonths($index,$style)
    {
        $name="proveedor";
        if($index=="customers") $name="cliente";

        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;        
        $margin=$this->_objetos[0][$index.'TotalForecast'];
        $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalThirdMonth']->margin,$margin)."</td>";
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalThirdMonth']->margin)."</td>";
        $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalFourthMonth']->margin,$margin)."</td>";
        $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalFourthMonth']->margin)."</td>";
        $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalFifthMonth']->margin,$margin)."</td>";
        $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalFifthMonth']->margin)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalSixthMonth']->margin,$margin)."</td>";
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalSixthMonth']->margin)."</td>";
        $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalSeventhMonth']->margin,$margin)."</td>";
        $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalSeventhMonth']->margin)."</td>";
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>--</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>--</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     *
     */
    private function _getTotalMonthsMore($index,$style)
    {
        $name="proveedor";
        if($index=="customers") $name="cliente";

        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;        
        $margin=$this->_objetos[0][$index.'TotalForecast'];
        $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalMoreThirdMonth']->margin,$margin)."</td>";
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalMoreThirdMonth']->margin)."</td>";
        $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalMoreFourthMonth']->margin,$margin)."</td>";
        $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalMoreFourthMonth']->margin)."</td>";
        $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalMoreFifthMonth']->margin,$margin)."</td>";
        $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalMoreFifthMonth']->margin)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalMoreSixthMonth']->margin,$margin)."</td>";
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalMoreSixthMonth']->margin)."</td>";
        $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalMoreSeventhMonth']->margin,$margin)."</td>";
        $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalMoreSeventhMonth']->margin)."</td>";
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>--</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>--</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     *
     */
    private function _getTotalMonthsLess($index,$style)
    {
        $name="proveedor";
        if($index=="customers") $name="cliente";

        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;        
        $margin=$this->_objetos[0][$index.'TotalForecast'];
        $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalLessThirdMonth']->margin,$margin)."</td>";
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalLessThirdMonth']->margin)."</td>";
        $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalLessFourthMonth']->margin,$margin)."</td>";
        $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalLessFourthMonth']->margin)."</td>";
        $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalLessFifthMonth']->margin,$margin)."</td>";
        $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalLessFifthMonth']->margin)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalLessSixthMonth']->margin,$margin)."</td>";
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalLessSixthMonth']->margin)."</td>";
        $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0][$index.'TotalLessSeventhMonth']->margin,$margin)."</td>";
        $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0][$index.'TotalLessSeventhMonth']->margin)."</td>";
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>--</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>--</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     * Retorna las celdas con la data que conincida dentro del index consultado y el apellido pasado como parametro
     * @access private
     * @param string $index es el index superior donde se encutra la data
     * @param string $index2 es el index inferior donde se encuentra la data
     * @param string $attribute es el apallido que debe coincidir la data
     * @param string $phrase el nombre del estilo asignado 
     * @return string
     */
    private function _getRowDestination($index,$index2,$attribute,$phrase)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        $margin=$previous=$average=$previousMonth=null;
        $otro="internal";
        $style=self::colorDestino($phrase['attribute']);
        foreach ($this->_objetos[$index][$index2] as $key => $value)
        {
            if($value->$attribute == $phrase['attribute'])
            {               
                if($this->type) $c1="<td style='".$style."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
                if($this->type) $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
                if($this->type) $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                if($this->type) $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
                if($this->type) $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
                if($this->type) $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $c9="<td style='".$style."' colspan='2'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $margin=$value->margin;
                if($this->type) $c18="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
                if($this->type) $c19="<td style='".$style."'>".Yii::app()->format->format_decimal($value->costmin)."</td>";
                if($this->type) $c20="<td style='".$style."'>".Yii::app()->format->format_decimal($value->ratemin)."</td>";
                if($this->type) $c21="<td style='".$style."'>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
            }
        }
        if($this->equal)
        {
            if(strstr($index2, 'external')!=false) $otro="external";
            foreach ($this->_objetos[$index][$otro."Yesterday"] as $key => $yesterday)
            {
                if($yesterday->$attribute==$phrase['attribute'])
                {
                    $c10="<td style='".$style."'>".$this->_upOrDown($yesterday->margin,$margin)."</td>";
                    $c11="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
                }
            }
            foreach ($this->_objetos[$index][$otro."Average"] as $key => $average)
            {
                if($average->$attribute==$phrase['attribute'])
                {
                    $c12="<td style='".$style."'>".$this->_upOrDown($average->margin,$margin)."</td>";
                    $c13="<td style='".$style."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
                    if(strstr($index2, 'externalDestinationsMore')!=false) $this->totalAverageExternalDesMore+=$average->margin;
                    if(strstr($index2, 'externalDestinationsLess')!=false) $this->totalAverageExternalDesLess+=$average->margin;
                    if(strstr($index2, 'internalDestinationsWithMore')!=false) $this->totalAverageInternalDesMore+=$average->margin;
                    if(strstr($index2, 'internalDestinationsWithLess')!=false) $this->totalAverageInternalDesLess+=$average->margin;
                }
            }
            foreach ($this->_objetos[$index][$otro."Accumulated"] as $key => $accumulated)
            {
                if($accumulated->$attribute==$phrase['attribute'])
                {
                    $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
                    if(strstr($index2, 'externalDestinationsMore')!=false) $this->totalAccumExternalDesMore+=$accumulated->margin;
                    if(strstr($index2, 'externalDestinationsLess')!=false) $this->totalAccumExternalDesLess+=$accumulated->margin;
                    if(strstr($index2, 'internalDestinationsWithMore')!=false) $this->totalAccumInternalDesMore+=$accumulated->margin;
                    if(strstr($index2, 'internalDestinationsWithLess')!=false) $this->totalAccumInternalDesLess+=$accumulated->margin;
                }
            }
            $c15="<td style='".$style."' colspan='2'>".Yii::app()->format->format_decimal($this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']])."</td>";
            foreach ($this->_objetos[$index][$otro.'PreviousMonth'] as $key => $month)
            {
                if($month->$attribute==$phrase['attribute'])
                {
                    $c17="<td style='".$style."'>".Yii::app()->format->format_decimal($month->margin)."</td>";
                    $previous=$month->margin;
                    if(strstr($index2, 'externalDestinationsMore')!=false) $this->totalPreviousExternalDesMore+=$month->margin;
                    if(strstr($index2, 'externalDestinationsLess')!=false) $this->totalPreviousExternalDesLess+=$month->margin;
                    if(strstr($index2, 'internalDestinationsWithMore')!=false) $this->totalPreviousInternalDesMore+=$month->margin;
                    if(strstr($index2, 'internalDestinationsWithLess')!=false) $this->totalPreviousInternalDesLess+=$month->margin;
                }
            }
            $c16="<td style='".$style."'>".$this->_upOrDown($previous,$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']])."</td>";
            if(strstr($index2, 'externalDestinationsMore')!=false) $this->totalForecastExternalDesMore+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'externalDestinationsLess')!=false) $this->totalForecastExternalDesLess+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'internalDestinationsWithMore')!=false) $this->totalForecastInternalDesMore+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
            if(strstr($index2, 'internalDestinationsWithLess')!=false) $this->totalForecastInternalDesLess+=$this->_objetos[$index][$otro.'Forecast'][$phrase['attribute']];
        }
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null) $c9="<td style='".$style."' colspan='2'>--</td>";
        if($this->type)
        {
            if($c1==null) $c1="<td style='".$style."'>--</td>";
            if($c2==null) $c2="<td style='".$style."'>--</td>";
            if($c3==null) $c3="<td style='".$style."'>--</td>";
            if($c4==null) $c4="<td style='".$style."'>--</td>";
            if($c5==null) $c5="<td style='".$style."'>--</td>";
            if($c6==null) $c6="<td style='".$style."'>--</td>";
            if($c18==null) $c18="<td style='".$style."'>--</td>";
            if($c19==null) $c19="<td style='".$style."'>--</td>";
            if($c20==null) $c20="<td style='".$style."'>--</td>";
            if($c21==null) $c21="<td style='".$style."'>--</td>";
        }
        if($this->type && $this->equal)
        {
            if($c10==null) $c10="<td style='".$style."'>--</td>";
            if($c11==null) $c11="<td style='".$style."'>--</td>";
            if($c12==null) $c12="<td style='".$style."'>--</td>";
            if($c13==null) $c13="<td style='".$style."'>--</td>";
            if($c14==null) $c14="<td style='".$style."'>--</td>";
            if($c15==null) $c15="<td style='".$style."' colspan='2'>--</td>";
            if($c16==null) $c16="<td style='".$style."'>--</td>";
            if($c17==null) $c17="<td style='".$style."'>--</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    }

    /**
     *
     */
    private function _getRowDestinationMonths($index,$phrase,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;        
        $margin=$this->_objetos[0][$index.'Forecast'][$phrase['attribute']];
        foreach ($this->_objetos[0][$index.'ThirdMonth'] as $key => $value)
        {
            if($value->destino == $phrase['attribute'])
            {
                $c1="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FourthMonth'] as $key => $value)
        {
            if($value->destino == $phrase['attribute'])
            {
                $c3="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FifthMonth'] as $key => $value)
        {
            if($value->destino == $phrase['attribute'])
            {
                $c5="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SixthMonth'] as $key => $value)
        {
            if($value->destino == $phrase['attribute'])
            {
                $c7="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SeventhMonth'] as $key => $value)
        {
            if($value->destino == $phrase['attribute'])
            {
                $c9="<td style='".$style."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        if($c1==null) $c1="<td style='".$style."'>--</td>";
        if($c2==null) $c2="<td style='".$style."'>--</td>";
        if($c3==null) $c3="<td style='".$style."'>--</td>";
        if($c4==null) $c4="<td style='".$style."'>--</td>";
        if($c5==null) $c5="<td style='".$style."'>--</td>";
        if($c6==null) $c6="<td style='".$style."'>--</td>";
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($c10==null) $c10="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     * Retorna la columna de totales de carriers
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @param boolean $type true=totales con condicion, false=totales completos
     * @return string
     */
    private function _getRowTotalCarrier($index,$index2,$style,$type=true)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=null;
        $average=$accumulated=$forecast=$previous=null;
        $value=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index2."Yesterday"];
        if($type==true)
        {
            if($index2=='clientsTotalMoreThanTenDollars')
            {
                if($this->type && $this->equal) $average=$this->totalAverageCustomerMore;
                if($this->type && $this->equal) $forecast=$this->totalForecastCustomerMore;
                if($this->type && $this->equal) $accumulated=$this->totalAccumCustomerMore;
                if($this->type && $this->equal) $previous=$this->totalPreviousCustomerMore;
            }
            if($index2=='clientsTotalLessThanTenDollars')
            {
                if($this->type && $this->equal) $average=$this->totalAverageCustomerLess;
                if($this->type && $this->equal) $forecast=$this->totalForecastCustomerLess;
                if($this->type && $this->equal) $accumulated=$this->totalAccumCustomerLess;
                if($this->type && $this->equal) $previous=$this->totalPreviousCustomerLess;
            }
            if($index2=='suppliersTotalMoreThanTenDollars')
            {
                if($this->type && $this->equal) $average=$this->totalAverageSupplierMore;
                if($this->type && $this->equal) $forecast=$this->totalForecastSupplierMore;
                if($this->type && $this->equal) $accumulated=$this->totalAccumSupplierMore;
                if($this->type && $this->equal) $previous=$this->totalPreviousSupplierMore;
            }
            if($index2=='suppliersTotalLessThanTenDollars')
            {
                if($this->type && $this->equal) $average=$this->totalAverageSupplierLess;
                if($this->type && $this->equal) $forecast=$this->totalForecastSupplierLess;
                if($this->type && $this->equal) $accumulated=$this->totalAccumSupplierLess;
                if($this->type && $this->equal) $previous=$this->totalPreviousSupplierLess;
            }
        }
        else
        {
            if($index2=='totalCustomer')
            {
                if($this->type && $this->equal) $average=$this->_objetos[$index]['customersTotalAverage']->margin;
                if($this->type && $this->equal) $accumulated=$this->_objetos[$index]['customersTotalAccumulated']->margin;
                if($this->type && $this->equal) $forecast=$this->_objetos[$index]['customersTotalForecast'];
                if($this->type && $this->equal) $previous=$this->_objetos[$index]['customersTotalPreviousMonth']->margin;
            }

            if($index2=='totalSuppliers')
            {
                if($this->type && $this->equal) $average=$this->_objetos[$index]['providersTotalAverage']->margin;
                if($this->type && $this->equal) $accumulated=$this->_objetos[$index]['providersTotalAccumulated']->margin;
                if($this->type && $this->equal) $forecast=$this->_objetos[$index]['providersTotalForecast'];
                if($this->type && $this->equal) $previous=$this->_objetos[$index]['providersTotalPreviousMonth']->margin;
            }
        }
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
        $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$value->margin)."</td>";
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if($this->equal && $this->type) $c12="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$value->margin)."</td>";
        if($this->equal && $this->type) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average)."</td>";
        if($this->equal && $this->type) $c14="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal($accumulated)."</td>";
        if($this->equal && $this->type) $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($forecast)."</td>";
        if($this->equal && $this->type) $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previous,$forecast)."</td>";
        if($this->equal && $this->type) $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previous)."</td>";
        if(!$type)
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
            if($this->type) $c18="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
            if($this->type) $c19="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
        }
        else
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c18="<td style='".$this->_head[$style]."' colspan='2'></td>"; 
            if($this->type) $c19="<td style='".$this->_head[$style]."'></td>"; 
        }
        if($this->type) $c19="<td style='".$this->_head[$style]."'></td>"; 
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19;
    }

    /**
     * Retorna la columna de totales de destinos
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @param boolean $type true=totales con condicion, false=totales completos
     * @return string
     */
    private function _getRowTotalDestination($index,$index2,$style,$type=true)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c9=$c10=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        $average=$accumulated=$forecast=$previous=null;
        $value=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index2."Yesterday"];
        if($type)
        {
            if(strstr($index2, 'totalExternalDestinationsMore')!=false) $average=$this->totalAverageExternalDesMore;
            if(strstr($index2, 'totalExternalDestinationsLess')!=false) $average=$this->totalAverageExternalDesLess;
            if(strstr($index2, 'totalInternalDestinationsWithMore')!=false) $average=$this->totalAverageInternalDesMore;
            if(strstr($index2, 'totalInternalDestinationsWithLess')!=false) $average=$this->totalAverageInternalDesLess;
            if(strstr($index2, 'totalExternalDestinationsMore')!=false) $accumulated=$this->totalAccumExternalDesMore;
            if(strstr($index2, 'totalExternalDestinationsLess')!=false) $accumulated=$this->totalAccumExternalDesLess;
            if(strstr($index2, 'totalInternalDestinationsWithMore')!=false) $accumulated=$this->totalAccumInternalDesMore;
            if(strstr($index2, 'totalInternalDestinationsWithLess')!=false) $accumulated=$this->totalAccumInternalDesLess;
            if(strstr($index2, 'totalExternalDestinationsMore')!=false) $forecast=$this->totalForecastExternalDesMore;
            if(strstr($index2, 'totalExternalDestinationsLess')!=false) $forecast=$this->totalForecastExternalDesLess;
            if(strstr($index2, 'totalInternalDestinationsWithMore')!=false) $forecast=$this->totalForecastInternalDesMore;
            if(strstr($index2, 'totalInternalDestinationsWithLess')!=false) $forecast=$this->totalForecastInternalDesLess;
            if(strstr($index2, 'totalExternalDestinationsMore')!=false) $previous=$this->totalPreviousExternalDesMore;
            if(strstr($index2, 'totalExternalDestinationsLess')!=false) $previous=$this->totalPreviousExternalDesLess;
            if(strstr($index2, 'totalInternalDestinationsWithMore')!=false) $previous=$this->totalPreviousInternalDesMore;
            if(strstr($index2, 'totalInternalDestinationsWithLess')!=false) $previous=$this->totalPreviousInternalDesLess;
        }
        else
        {
            if(strstr($index2, 'totalExternal')!=false)
            {
                if($this->type && $this->equal) $average=$this->_objetos[$index]['externalTotalAverage']->margin;
                if($this->type && $this->equal) $accumulated=$this->_objetos[$index]['externalTotalAccumulated']->margin;
                if($this->type && $this->equal) $forecast=$this->_objetos[$index]['externalTotalForecast'];
                if($this->type && $this->equal) $previous=$this->_objetos[$index]['externalTotalPreviousMonth']->margin;
            }
            if(strstr($index2, 'totalInternal')!=false)
            {
                if($this->type && $this->equal) $average=$this->_objetos[$index]['internalTotalAverage']->margin;
                if($this->type && $this->equal) $accumulated=$this->_objetos[$index]['internalTotalAccumulated']->margin;
                if($this->type && $this->equal) $forecast=$this->_objetos[$index]['internalTotalForecast'];
                if($this->type && $this->equal) $previous=$this->_objetos[$index]['internalTotalPreviousMonth']->margin;
            }
        }
        //Total Calls
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
        //Complete Calls
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
        //Minutes
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
        //ASR
        if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
        if(!$type)
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
        }
        //ACD
        if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
        if(!$type)
        {
            if($this->type) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
        }
        //PDD
        if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
        if(!$type)
        {
            if($this->type) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
        }
        //Cost
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
        //Revenue
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->revenue)."</td>";
        //Margin
        $c9="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal($value->margin)."</td>";
        //indicador dia anterior
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$value->margin)."</td>";
        //Dia Anterior
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        //Indicador de Promedio
        if($this->equal && $this->type) $c12="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$value->margin)."</td>";
        //Promedio
        if($this->equal && $this->type) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average)."</td>";
        //Acumulado Mes
        if($this->equal && $this->type) $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated)."</td>";
        //Proyeccion Mes
        if($this->equal && $this->type) $c15="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal($forecast)."</td>";
        //Indicador Mes anterior
        if($this->equal && $this->type) $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previous,$forecast)."</td>";
        //Mes Anterior
        if($this->equal && $this->type) $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previous)."</td>";
        //Margen Procentaje
        if($this->type) $c18="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c19="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->costmin)."</td>";
        if($this->type) $c20="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->ratemin)."</td>";
        if($this->type) $c21="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
        if(!$type)
        {   
            //Margen Procentaje
            if($this->type) $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
            if($this->type) $c19="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c20="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c21="<td style='".$this->_head[$style]."'></td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    }

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes de los carriers seleccionado y el total de los carriers.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones:
     *      - clientsTotalMoreThanTenDollars 
     *      - clientsTotalLessThanTenDollars
     *      - suppliersTotalMoreThanTenDollars
     *      * suppliersTotalLessThanTenDollars
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string 
     */
    private function _getRowTotalCarrierPercentage($index,$index2,$index3,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=null;
        $totalCondition=$this->_objetos[$index][$index2];
        if($this->type && $this->equal)
        {
            $yesterdayCondition=$this->_objetos[$index][$index2."Yesterday"];
            if($index2=='clientsTotalMoreThanTenDollars')
            {
                $averageCondition=$this->totalAverageCustomerMore;
                $accumulatedCondition=$this->totalAccumCustomerMore;
                $forecastCondition=$this->totalForecastCustomerMore;
                $previousCondition=$this->totalPreviousCustomerMore;
            }
            if($index2=='clientsTotalLessThanTenDollars')
            {
                $averageCondition=$this->totalAverageCustomerLess;
                $accumulatedCondition=$this->totalAccumCustomerLess;
                $forecastCondition=$this->totalForecastCustomerLess;
                $previousCondition=$this->totalPreviousCustomerLess;
            }
            if($index2=='suppliersTotalMoreThanTenDollars')
            {
                $averageCondition=$this->totalAverageSupplierMore;
                $accumulatedCondition=$this->totalAccumSupplierMore;
                $forecastCondition=$this->totalForecastSupplierMore;
                $previousCondition=$this->totalPreviousSupplierMore;
            }
            if($index2=='suppliersTotalLessThanTenDollars')
            {
                $averageCondition=$this->totalAverageSupplierLess;
                $accumulatedCondition=$this->totalAccumSupplierLess;
                $forecastCondition=$this->totalForecastSupplierLess;
                $previousCondition=$this->totalPreviousSupplierLess;
            }
        }
        $total=$this->_objetos[$index][$index3];
        if($this->type && $this->equal)
        {
            $yesterday=$this->_objetos[$index][$index3."Yesterday"];
            if(strstr($index2, 'clientsTotal')!=false)
            {
                $average=$this->_objetos[$index]['customersTotalAverage']->margin;
                $accumulated=$this->_objetos[$index]['customersTotalAccumulated']->margin;
                $forecast=$this->_objetos[$index]['customersTotalForecast'];
                $previous=$this->_objetos[$index]['customersTotalPreviousMonth']->margin;
            }
            if(strstr($index2, 'suppliersTotal')!=false)
            {
                $average=$this->_objetos[$index]['providersTotalAverage']->margin;
                $accumulated=$this->_objetos[$index]['providersTotalAccumulated']->margin;
                $forecast=$this->_objetos[$index]['providersTotalForecast'];
                $previous=$this->_objetos[$index]['providersTotalPreviousMonth']->margin;
            }
        }
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>";
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>";
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>";
        if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>";
        $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>";
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($yesterdayCondition->margin/$yesterday->margin)*(100),($totalCondition->margin/$total->margin)*(100))."</td>";
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterdayCondition->margin/$yesterday->margin)*(100))."%</td>";
        if($this->equal && $this->type) $c12="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($averageCondition/$average)*(100),($totalCondition->margin/$total->margin)*(100))."</td>";
        if($this->equal && $this->type) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($averageCondition/$average)*(100))."%</td>";
        if($this->equal && $this->type) $c14="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal(($accumulatedCondition/$accumulated)*(100))."%</td>";
        if($this->equal && $this->type) $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($forecastCondition/$forecast)*(100))."%</td>";
        if($this->equal && $this->type) $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($previousCondition/$previous)*(100),($forecastCondition/$forecast)*(100))."</td>";
        if($this->equal && $this->type) $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($previousCondition/$previous)*(100))."%</td>";
        if($this->type) $c18="<td style='".$this->_head[$style]."' colspan='2'></td>";
        if($this->type) $c19="<td style='".$this->_head[$style]."'></td>"; 
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19;
    } 

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes del destino seleccionado y el total de los destinos.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones:
     *        - totalExternalDestinationsMoreThanTenDollars
     *        - totalExternalDestinationsLessThanTenDollars
     *        - totalInternalDestinationsWithMoreThanTenDollars
     *        - totalInternalDestinationsWithLessThanTenDollars
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string
     */
    private function _getRowTotalDestinationsPercentage($index,$index2,$index3,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        $totalCondition=$this->_objetos[$index][$index2];
        if($this->type && $this->equal)
        {
            $yesterdayCondition=$this->_objetos[$index][$index2."Yesterday"];
            if($index2=='totalExternalDestinationsMoreThanTenDollars')
            {
                $averageCondition=$this->totalAverageExternalDesMore;
                $accumulatedCondition=$this->totalAccumExternalDesMore;
                $forecastCondition=$this->totalForecastExternalDesMore;
                $previousCondition=$this->totalPreviousExternalDesMore;

            }
            if($index2=='totalExternalDestinationsLessThanTenDollars')
            {
                $averageCondition=$this->totalAverageExternalDesLess;
                $accumulatedCondition=$this->totalAccumExternalDesLess;
                $forecastCondition=$this->totalForecastExternalDesLess;
                $previousCondition=$this->totalPreviousExternalDesLess;
            }
            if($index2=='totalInternalDestinationsWithMoreThanTenDollars')
            {
                $averageCondition=$this->totalAverageInternalDesMore;
                $accumulatedCondition=$this->totalAccumInternalDesMore;
                $forecastCondition=$this->totalForecastInternalDesMore;
                $previousCondition=$this->totalPreviousInternalDesMore;
            }
            if($index2=='totalInternalDestinationsWithLessThanTenDollars')
            {
                $averageCondition=$this->totalAverageInternalDesLess;
                $accumulatedCondition=$this->totalAccumInternalDesLess;
                $forecastCondition=$this->totalForecastInternalDesLess;
                $previousCondition=$this->totalPreviousInternalDesLess;
            }
        }
        $total=$this->_objetos[$index][$index3];
        if($this->type && $this->equal)
        {
            $yesterday=$this->_objetos[$index][$index3."Yesterday"];
            if(strstr($index2, 'totalExternalDestinations')!=false)
            {
                $average=$this->_objetos[$index]['externalTotalAverage']->margin;
                $accumulated=$this->_objetos[$index]['externalTotalAccumulated']->margin;
                $forecast=$this->_objetos[$index]['externalTotalForecast'];
                $previous=$this->_objetos[$index]['externalTotalPreviousMonth']->margin;
            }
            if(strstr($index2, 'totalInternalDestinations')!=false)
            {
                $average=$this->_objetos[$index]['internalTotalAverage']->margin;
                $accumulated=$this->_objetos[$index]['internalTotalAccumulated']->margin;
                $forecast=$this->_objetos[$index]['internalTotalForecast'];
                $previous=$this->_objetos[$index]['internalTotalPreviousMonth']->margin;
            }
        }
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>";
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>";
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>";
        if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>";
        $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>";
        $c9="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($yesterdayCondition->margin/$yesterday->margin)*(100),($totalCondition->margin/$total->margin)*(100))."</td>";
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterdayCondition->margin/$yesterday->margin)*(100))."%</td>";
        if($this->equal && $this->type) $c12="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($averageCondition/$average)*(100),($totalCondition->margin/$total->margin)*(100))."</td>";
        if($this->equal && $this->type) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($averageCondition/$average)*(100))."%</td>";
        if($this->equal && $this->type) $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($accumulatedCondition/$accumulated)*(100))."%</td>";
        if($this->equal && $this->type) $c15="<td style='".$this->_head[$style]."' colspan='2'>".Yii::app()->format->format_decimal(($forecastCondition/$forecast)*(100))."%</td>";
        if($this->equal && $this->type) $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($previousCondition/$previous)*(100),($forecastCondition/$forecast)*(100))."</td>";
        if($this->equal && $this->type) $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($previousCondition/$previous)*(100))."%</td>";
        if($this->type) $c18="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $c19="<td style='".$this->_head[$style]."'></td>"; 
        if($this->type) $c20="<td style='".$this->_head[$style]."'></td>"; 
        if($this->type) $c21="<td style='".$this->_head[$style]."'></td>"; 
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    } 
}
?>