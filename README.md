#Etelix_RENOC
============

##Sistema de Reportes para el NOC

###Release 1.3.22
- Corregido reporte Distribucion comercial, ahora funciona con la fecha consultada
- Corregido reporte de Posición Neta, ahora muestra todo los carriers que generaron trafico en los ultimos 30 dias.
- Agregada validacion en vista especificos para que en los reportes calidad y arbol 2n proveedor, emita un msj recordando que solo debe llenar uno de los input carrier o grupo.

###Release 1.3.21.1
- Corregido error de ending_date en actionMail y actionLista.

###Release 1.3.21
- Agregada validacion para reportes evolucion y distribucion comercial al momento de generar vista previa.
- Agregada vista previa a los reportes tanto de especificos como de rutinarios.
- Cambiada estructura de especificos, ahora solo se puede generar un reporte a la vez, y los parametros varian segun el tipo de reporte.
- la vista previa solo aplica para generar un reporte a la vez, si se selecciona mas de uno, se emite un msj que indica que solo se puede seleccionar uno.

###Realese 1.3.20.2
- Reparado costo por minutos para los carriers en el reporte Arbol 2N proveedor.

###Realese 1.3.20.1
- Agregado totales por destinos en reporte arbol 2n proveedor 
- Agregado totales por destinos en arbol 2n proveedor.
- Correcciones en reportes arbol 2n proveedor.

###Realese 1.3.20
- Agregado reporte arbol 2n proveedor a especifico.
- Correcciones en interfaz especifico, problemas con indexado del html.

###Release 1.3.19.3
- Diferenciados entornos de desarrollo y producción.

###Release 1.3.19.2
- Correcion de margen total en Ranking Compra Venta

###Release 1.3.19.1
- Correcion de sin asignar en clientes, para el mes anterior en Ranking Compra Venta

###Release 1.3.19
- Correccion de mes anterior en Ranking Compra Venta

###Release 1.3.18
- Depurados division por cero en Reporte Alto Impacto(+10$)
- Cambio en Reporte Ranking Compra Venta para soporte para cualquier cantidad de managers

###Release 1.3.17
- Agregados meses anteriores a reporte Alto Impacto
- Cambio en la forma de generar los excel

###Release 1.3.16
- Color de fondo para las celdas en Reporte Distribucion Comercial
- Validacion de las fechas en Reporte Calidad
- Agregadas la columnas Dia Anterior, Promedio 7D, Acumulado Mes, Proyeccion Mes y Meses Anteriores en Reporte Posicion Neta
- Agregado a especificos Reporte Posicion Neta

###Release 1.3.15
- Agregado un septimo mes en reporte de Ranking Compra Venta

###Release 1.3.14
- Correcion en consulta a base de datos del reporte de arbol de trafico clientes/proveedores/destinos

###Release 1.3.13

###Release 1.3.12

###Release 1.3.11
- Agregando ultimos cuatro meses en Reporte Ranking Compra/Venta

###Release 1.3.10
- Agregado funcion ABS() en el calculo de margenes.
- Agregada la proyeccion en el reporte AltoImpacto

###Release 1.3.9
-Correcion de totales de margenes por managers en Ranking Compra Venta

###Release 1.3.8
- Correccion de formato Reporte Alto Impacto

###Release 1.3.7
- Correcion de formato correo electronico en Raning Compra Venta

###Release 1.3.6
- Correccion de errores de procesamiento de fechas en Ranking Compra Venta y Alto Impacto

###Release 1.3.5
- Correcion de totales de Reporte Ranking Compra Venta

###Release 1.3.4
- Reporte Ranking Compra Venta, Agregada la columna de cierre del mes anterior, ademas de columnas indicadoras del margen con respecto al dia anterior y el promedio. columna indicadora con respecto a la proyeccion de cierre de mes y monto cierre de mes anterior.

###Release 1.3.3
- Reporte Ranking Compra/Venta, es modificado para que incluya el margen del dia de ayer, el acumulado hasta el dia consultado, el promedio de los ultimos 7 dias, y el pronostico de cierre.

###Release 1.3.2
- Reporte de Calidad validado para Grupo Cabinas Peru
- Reporte de Ranking Compra/Venta, es modificado para que el nombre del rango sea ubicado en la mismo cuadro

###Release 1.3.1
- Correccion de tiempo en generar reporte alto impacto

###Release 1.3.0
- Reporte Alto impacto (+10$) por rango de fechas, dos versiones una resumida y otra completa.
- Reporte Distribucion Comercial, agregado el campo estado.
- Validacion de campos de reportes especificos.

###Release 1.2.3
- Aumento de Destinos/Clientes/Proveedor de cinco a siete en Reportes de Arbol de Trafico

###Release 1.2.2
- Se quitan el no repetir nombres en Distribucion Comercial
- Totales de ASR, PDD, etc en Reporte Calidad

###Release 1.2.1
- Reporte Distribucion Comercial en un solo archivo excel

###Release 1.2.0
- Agregado modulo de reportes especificos con los siguientes reportes
	. RENOC Ranking Compra Venta
	. RENOC Calidad(BSG)
- Agregado unidad de produccion en los reportes de Distribucion Comercial
- Agregado destinos internos e alto impacto

###Release 1.1.7
- Modificado: Arbol de Trafico, ahora Clientes y Proveedor con destinos external e internal.
- Modificado: Reporte CompraVenta generado por mes

###Release 1.1.6
- Modificacion en nombre de Arbol de Trafico Internal y External a Arbol Destino Internal y External.
- Agregados reportes de Arbol de Trafico por Clientes y Proveedores.
- Agregado reporte de Evolución.
- Agregados los Reportes de Distribucion Comercial por, Vendedor, Terminos de Pago, Monetizable, Compañia y Carrier.
- Modificacion en el Scroll de la interfaz
- Login e Index Responsive Funcional
- Agregado Total de PN en AltoImpacto (+10$) por Vendedor

###Release 1.1.5
- Reporte Arbol de Trafico Internal y External
- Cambios en la lista de Reportes en Rutinarios
- Mensaje de RR en proceso
- Mensaje de RR en proceso a la hora de hacer click en cualquier boton de exportacion
- Reporte Posicion Neta por Vendedor
- Totales en Alto Impacto (+10$) por vendedor corregidos
- Agregado PN en Alto Impacto (+10$) por vendedor
- Nombre de Reportes RENOP o RENOCD dependiendo de la carga en SORI (P=Preliminares / D=Definitivos)

###Release 1.1.4
- Solo apellidos para los vendedores
- Cambios en Reporte AltoImpacto+10$ (se muestra el resto de los Carriers)
- Totales por Vendedor en Reporte AltoImpacto+10$ por Vendedor
- Camios Generales de estilos para los Reportes
- Eliminado Ranking negativo
- PN en AltoImpacto(+10$)

###Release 1.1.3
- Corrección de margenes de millones
- Cambio de nombre de vendedores en reportes
- Reporte Ranking Compra/Venta

###Release 1.1.2
- Adjuntado de archivo excel en correo electronico

###Release 1.1.1
- Reporte Alto Impacto por vendedor
- Reporte Posicion Neta
- Reporte Distribucion Comercial
- Reporte Perdidas

###Release 1.0.1
- Envio de Reportes a correo electronico de usuario logueado
- Envio de Reportes a lista de correo renoc@etelix.com

###Release 1.0
- Envio de reportes por correo
- Descarga de reportes en archivo Excel

###Release 0.0.1
- Reporte Alto Impacto
- Reporte Alto Impacto Retail
