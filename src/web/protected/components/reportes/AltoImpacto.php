<?php
/**
* @version 7.1.0
* @package reportes
*/
class AltoImpacto extends Reportes
{
    /**
     * Atributo encargado de almacenar la data traida de base de datos
     * @var array
     */
    private $_objetos;

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
        $this->type=$type;
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '300M');
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

        $span=11;
        $spanDes=$span+2;
        if(!$this->type)
        {
            $span=3;
            $spanDes=3;
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
            for ($col=1; $col <= 3+($num*$span); $col++)
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
                //Nombres de los managers vendedores izquierda con menos de 10$
                if($row>$numCustomer+6  && $row<=$numCustomer+$numCustomerLess && self::validColumn(3,$col,$num,$span))
                {
                    //le resto las 8 filas que tiene delante para que continue la cuenta anterior
                    $pos=$row-8;
                    //le resto el total de clientes - 7 filas
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'customersWithLessThanTenDollars','cliente',$sorted['customersWithLessThanTenDollars'][$row-$numCustomer-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //Nombres de los managers proveedores izquierda con mas de 10$
                if($row>$numCustomer+$numCustomerLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier && self::validColumn(3,$col,$num,$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-6;
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'providersWithMoreThanTenDollars','proveedor',$sorted['providersWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //Nombres de los managers proveedores izquierda con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess && self::validColumn(3,$col,$num,$span))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-12;
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'providersWithLessThanTenDollars','proveedor',$sorted['providersWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-7],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-6;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'externalDestinationsMoreThanTenDollars','destino',$sorted['externalDestinationsMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //Nombres de los destinos external con menos de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-12;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'externalDestinationsLessThanTenDollars','destino',$sorted['externalDestinationsLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }

                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-6;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'internalDestinationsWithMoreThanTenDollars','destino',$sorted['internalDestinationsWithMoreThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }
                //Nombres de los destinos external con mas de 10$
                if($row>$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+6  && $row<=$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess && self::validColumn(3,$col,$num,$spanDes))
                {
                    $pos=$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-12;
                    $body.=$this->_getRowDestination(self::validIndex(3,$col,$spanDes),'internalDestinationsWithLessThanTenDollars','destino',$sorted['internalDestinationsWithLessThanTenDollars'][$row-$numCustomer-$numCustomerLess-$numSupplier-$numSupplierLess-$numDestinationExt-$numDestinationExtLess-$numDestinationInt-7]);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$spanDes))) $body.="<td></td>";
                }

                //Totales de las tablas 
                if($row==$numCustomer+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'clientsTotalMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'clientsTotalLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'suppliersTotalMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'suppliersTotalLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }

                //total generales
                if(($row==$numCustomer+2||$row==$numCustomer+$numCustomerLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'totalCustomer','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if(($row==$numCustomer+$numCustomerLess+$numSupplier+2||$row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+2) && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalCarrier(self::validIndex(3,$col,$span),'totalSuppliers','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
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
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalExternalDestinationsLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalInternalDestinationsWithMoreThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCustomer+$numCustomerLess+$numSupplier+$numSupplierLess+$numDestinationExt+$numDestinationExtLess+$numDestinationInt+$numDestinationIntLess+1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotalDestination(self::validIndex(3,$col,$span),'totalInternalDestinationsWithLessThanTenDollars','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
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
        $yesterday=Utility::calculateDate('-1',$startDateTemp);
        $sevenDaysAgo=Utility::calculateDate('-7',$yesterday);
        $firstDay=Utility::getDayOne($start);
        $this->equal=$array['equal'];
        $arrayStartTemp=null;
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".self::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /***/
            //Guardo los datos de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['customersWithMoreThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo el margen del dia anterior de clientes de mas de 10 dolares
            if($this->type && $this->equal) $this->_objetos[$index]['customersYesterday']=$this->_getCarriers($yesterday,$yesterday,true,null,'margin');
            //Guardo el promedio del margen de los ultimos 7 dias de clientes con mas de 10$
            if($this->type && $this->equal) $this->_objetos[$index]['customersAverage']=$this->_getAvgCarriers($sevenDaysAgo,$yesterday,true);
            //Guardo el margen acumulado por los clientes en lo que va de mes
            //if($this->type && $this->equal) $this->_objetos[$index]['customersAccumulated']=$this->_getCarriers();
            //Guardo los datos de los totales de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['clientsTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,true);
            // Guardo los datos de los totales de ayer de los clientes con mas de 10 dolares de ganancia
            if($this->type && $this->equal) $this->_objetos[$index]['clientsTotalMoreThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,true,true,'margin');
            //Guardo los datos de los totales de todos los clientes
            $this->_objetos[$index]['totalCustomer']=$this->_getTotalCompleteCarriers($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los totales de todos los clientes del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['totalCustomerYesterday']=$this->_getTotalCompleteCarriers($yesterday,$yesterday,true,'margin');
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
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia
            $this->_objetos[$index]['suppliersTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['suppliersTotalMoreThanTenDollarsYesterday']=$this->_getTotalCarriers($yesterday,$yesterday,false,true,'margin');
            //Guardo los datos de los totales de todos los proveedores
            $this->_objetos[$index]['totalSuppliers']=$this->_getTotalCompleteCarriers($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los totales de todos los proveedores del dia anterior
            if($this->type && $this->equal) $this->_objetos[$index]['totalSuppliersYesterday']=$this->_getTotalCompleteCarriers($yesterday,$yesterday,false,'margin');
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
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalExternalDestinationsMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalExternalDestinationsMoreThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,true,true,'margin');
            //Guardo los datos de los totales de los destinos externos
            $this->_objetos[$index]['totalExternalDestinations']=$this->_getTotalCompleteDestination($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los totales de los destinos externos de dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalExternalDestinationsYesterday']=$this->_getTotalCompleteDestination($yesterday,$yesterday,true,'margin');
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
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsWithMoreThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,false,true,'margin');
            //Guardo los datos de los totales de los destinos internos
            $this->_objetos[$index]['totalInternalDestinations']=$this->_getTotalCompleteDestination($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los totales de los destinos internos del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsYesterday']=$this->_getTotalCompleteDestination($yesterday,$yesterday,false,'margin');
            //Guardo los datos de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['internalDestinationsWithLessThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithLessThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia del dia de ayer
            if($this->type && $this->equal) $this->_objetos[$index]['totalInternalDestinationsWithLessThanTenDollarsYesterday']=$this->_getTotalDestination($yesterday,$yesterday,false,false,'margin');

            /*Itero la fecha*/
            $startDateTemp=self::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     * Encargado de traer los datos de los carriers
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=+10$, false=-10$
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return array $models
     */
    private function _getCarriers($startDate,$endingDate,$typeCarrier=true,$type=true,$attribute=null)
    {
        $condicion="x.margin<10";
        if($type) $condicion="x.margin>=10";
        $select="id_carrier_supplier";
        if($typeCarrier) $select="id_carrier_customer";
        $titulo="proveedor";
        if($typeCarrier) $titulo="cliente";
        $data="c.name AS {$titulo}, x.{$select} AS id, x.{$attribute} ";
        if($attribute==null) $data="c.name AS {$titulo}, x.{$select} AS id, x.total_calls, x.complete_calls, x.minutes, x.asr,x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, cs.posicion_neta AS posicion_neta ";
        $completa="WHERE {$condicion} AND x.{$select}=c.id AND x.{$select}=cs.id";
        if($type==null) $completa="WHERE x.{$select}=c.id AND x.{$select}=cs.id";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$select}
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
              {$completa}
              ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * trae el total de todos los carriers
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=margen mayor a 10$, false=margen menor a 10$
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return object $model
     */
    private function _getTotalCarriers($startDate,$endingDate,$typeCarrier=true,$type=null,$attribute=null)
    {
        $condicion="margin<10";
        if($type) $condicion="margin>=10";
        $select="id_carrier_supplier";
        if($typeCarrier) $select="id_carrier_customer";
        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin ";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance
              WHERE {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna el total de todos los clientes en la fecha especificada
     * @access private
     * @param date $startDate
     * @param date $endingDate
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return array $models
     */
    private function _getTotalCompleteCarriers($startDate,$endingDate,$typeCarrier=true,$attribute=null)
    {
        $select="id_carrier_supplier";
        if($typeCarrier) $select="id_carrier_customer";
        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna la data de los destinos
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @param string $attribute default null, es usado para traer solo uno de los atributos del modelo, ejemplo ='margin'
     * @return array $models
     */
    private function _getDestination($startDate,$endingDate,$typeDestination=true,$type=null,$attribute=null)
    {
        $condicion="x.margin<10";
        if($type) $condicion="x.margin>=10";
        $table="destination_int";
        if($typeDestination) $table="destination";
        $select="id_destination_int";
        if($typeDestination) $select="id_destination";
        $data="d.name AS destino, x.{$attribute}";
        if($attribute==null) $data="d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, CASE WHEN x.minutes=0 THEN 0 ELSE(x.cost/x.minutes)*100 END AS costmin, CASE WHEN x.minutes=0 THEN 0 ELSE(x.revenue/x.minutes)*100 END AS ratemin, CASE WHEN x.minutes=0 THEN 0 ELSE((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) END AS marginmin";
        $completa="WHERE {$condicion} AND x.{$select}=d.id";
        if($type==null) $completa="WHERE x.{$select}=d.id";
        $sql="SELECT {$data}
                      FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
        $condicion="margin<10";
        if($type) $condicion="margin>=10";
        $select="id_destination_int";
        if($typeDestination) $select="id_destination";
        $table="destination_int";
        if($typeDestination) $table="destination";
        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance
              WHERE {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna el total de la data de todos los destinos
     * @access private
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @return object $model
     */
    private function _getTotalCompleteDestination($startDate,$endingDate,$typeDestination=true,$attribute=null)
    {
        $select="id_destination_int";
        if($typeDestination) $select="id_destination";
        $table="destination_int";
        if($typeDestination) $table="destination";
        $data="SUM({$attribute}) AS {$attribute}";
        if($attribute==null) $data="SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage";
        $sql="SELECT {$data}
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance";
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
              FROM(SELECT date_balance, {$carrier}, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$carrier}, date_balance
                   ORDER BY margin DESC) x, carrier c
              WHERE x.{$carrier}=c.id
              GROUP BY x.{$carrier}, c.name";
        return Balance::model()->findAllBySql($sql);
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
              FROM(SELECT date_balance, {$destination}, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$destination}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$destination} IS NOT NULL
                   GROUP BY {$destination}, date_balance
                   ORDER BY margin DESC) b, {$table} d
              WHERE b.{$destination}=d.id
              GROUP BY d.name";
        return Balance::model()->findAllBySql($sql);   
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=null;
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
        if($this->type) $c14="<td style='".$this->_head['styleHead']."'>Margin%</td>";
        if($this->type) $c15="<td style='".$this->_head['styleHead']."'>PN</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15;
    }

    /**
     * Retorna la cabecera de la data de managers
     * @access private
     * @return string celdas construidas
     */
    private function _getHeaderDestination()
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=null;
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
        if($this->type) $c14="<td style='".$this->_head['styleHead']."'>Margin%</td>";
        if($this->type) $c15="<td style='".$this->_head['styleHead']."'>Cost/Min</td>";
        if($this->type) $c16="<td style='".$this->_head['styleHead']."'>Rate/Min </td>";
        if($this->type) $c17="<td style='".$this->_head['styleHead']."'>Margin/Min</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17;
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=null;
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
                if($this->type) $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."</td>";
                if($this->type) $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
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
                }
            }
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
            if($c12==null) $c14="<td style='".$style."'>--</td>";
            if($c13==null) $c15="<td style='".$style."'>--</td>";
        }
        if($this->type && $this->equal)
        {
            if($c10==null) $c10="<td style='".$style."'>--</td>";
            if($c11==null) $c11="<td style='".$style."'>--</td>";
            if($c12==null) $c12="<td style='".$style."'>--</td>";
            if($c13==null) $c13="<td style='".$style."'>--</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15;
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=null;
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
                $c9="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $margin=$value->margin;
                if($this->type) $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
                if($this->type) $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($value->costmin)."</td>";
                if($this->type) $c16="<td style='".$style."'>".Yii::app()->format->format_decimal($value->ratemin)."</td>";
                if($this->type) $c17="<td style='".$style."'>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
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
                }
            }
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
            if($c14==null) $c14="<td style='".$style."'>--</td>";
            if($c15==null) $c15="<td style='".$style."'>--</td>";
            if($c16==null) $c16="<td style='".$style."'>--</td>";
            if($c17==null) $c17="<td style='".$style."'>--</td>";
        }
        if($this->type && $this->equal)
        {
            if($c10==null) $c10="<td style='".$style."'>--</td>";
            if($c11==null) $c11="<td style='".$style."'>--</td>";
            if($c12==null) $c12="<td style='".$style."'>--</td>";
            if($c13==null) $c13="<td style='".$style."'>--</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17;
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=null;
        $margin=null;
        $value=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index2."Yesterday"];
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
        $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$value->margin)."</td>";
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if(!$type)
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
            if($this->type) $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
        }
        else
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c12="<td style='".$this->_head[$style]."'></td>"; 
        }
        if($this->type) $c13="<td style='".$this->_head[$style]."'></td>"; 
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13;
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c9=$c10=$c10=$c11=$c12=$c13=$c14=$c15=null;
        $value=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index2."Yesterday"];
        if($this->type) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
        if($this->type) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
        if($this->type) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
        $c9="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
        $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
        if($this->equal && $this->type) $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$value->margin)."</td>";
        if($this->equal && $this->type) $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if(!$type)
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
            if($this->type) $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
            if($this->type) $c13="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c14="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c15="<td style='".$this->_head[$style]."'></td>";
        }
        else
        {
            if($this->type) $c4="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c5="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c6="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c12="<td style='".$this->_head[$style]."'></td>";
            if($this->type) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->costmin)."</td>";
            if($this->type) $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->ratemin)."</td>";
            if($this->type) $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c9.$c10.$c10.$c11.$c12.$c13.$c14.$c15;
    }

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes de los carriers seleccionado y el total de los carriers.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string
     */
    private function _getRowTotalCarrierPercentage($index,$index2,$index3,$style)
    {
        $uno=$dos=$tres=$cuatro=$cinco=$seis=$siete=$ocho=$nueve=$diez=$once=$doce=$trece=null;
        $totalCondition=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterdayCondition=$this->_objetos[$index][$index2."Yesterday"];
        $total=$this->_objetos[$index][$index3];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index3."Yesterday"];
        if($this->type) $uno="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>";
        if($this->type) $dos="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>";
        if($this->type) $tres="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>";
        if($this->type) $cuatro="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $cinco="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $seis="<td style='".$this->_head[$style]."'></td>";
        $siete="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>";
        $ocho="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>";
        $nueve="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if($this->equal && $this->type) $diez="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($totalCondition->margin/$total->margin)*(100),($yesterdayCondition->margin/$yesterday->margin)*(100))."</td>";
        if($this->equal && $this->type) $once="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterdayCondition->margin/$yesterday->margin)*(100))."%</td>";
        if($this->type) $doce="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $trece="<td style='".$this->_head[$style]."'></td>"; 
        return $uno.$dos.$tres.$cuatro.$cinco.$seis.$siete.$ocho.$nueve.$diez.$once.$doce.$trece;
    } 

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes del destino seleccionado y el total de los destinos.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string
     */
    private function _getRowTotalDestinationsPercentage($index,$index2,$index3,$style)
    {
        $uno=$dos=$tres=$cuatro=$cinco=$seis=$siete=$ocho=$nueve=$diez=$once=$doce=$trece=$catorce=$quince=null;
        $totalCondition=$this->_objetos[$index][$index2];
        if($this->type && $this->equal) $yesterdayCondition=$this->_objetos[$index][$index2."Yesterday"];
        $total=$this->_objetos[$index][$index3];
        if($this->type && $this->equal) $yesterday=$this->_objetos[$index][$index3."Yesterday"];
        if($this->type) $uno="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>";
        if($this->type) $dos="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>";
        if($this->type) $tres="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>";
        if($this->type) $cuatro="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $cinco="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $seis="<td style='".$this->_head[$style]."'></td>";
        $siete="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>";
        $ocho="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>";
        $nueve="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if($this->equal && $this->type) $diez="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($totalCondition->margin/$total->margin)*(100),($yesterdayCondition->margin/$yesterday->margin)*(100))."</td>";
        if($this->equal && $this->type) $once="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterdayCondition->margin/$yesterday->margin)*(100))."%</td>";
        if($this->type) $doce="<td style='".$this->_head[$style]."'></td>";
        if($this->type) $trece="<td style='".$this->_head[$style]."'></td>"; 
        if($this->type) $catorce="<td style='".$this->_head[$style]."'></td>"; 
        if($this->type) $quince="<td style='".$this->_head[$style]."'></td>"; 
        return $uno.$dos.$tres.$cuatro.$cinco.$seis.$siete.$ocho.$nueve.$diez.$once.$doce.$trece.$catorce.$quince;
    } 
}
?>