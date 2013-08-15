/**
* Reporte que muestra los 100 operadores por posicion neta
*/
/*Muestra los cien primeros*/
SELECT operador.name AS Operador, vendedor.name AS Vendedor, customer.Vminutes, customer.Vrevenue, customer.Vmargin, supplier.Cminutes, supplier.Ccost, supplier.Cmargin, (customer.Vrevenue-supplier.Ccost) AS Posicion_neta, (customer.Vmargin+supplier.Cmargin) AS Margen_total
            FROM
                (SELECT id_carrier_customer, SUM(minutes) AS Vminutes, SUM(revenue) AS Vrevenue, SUM(margin) AS Vmargin 
                FROM balance 
                WHERE date_balance = '$fecha' 
                GROUP BY id_carrier_customer) customer,
                (SELECT id_carrier_supplier, SUM(minutes) AS Cminutes, SUM(cost) AS Ccost, SUM(margin) AS Cmargin 
                FROM balance 
                WHERE date_balance = '$fecha' 
                GROUP BY id_carrier_supplier) supplier,
                carrier operador, 
                managers vendedor, 
                carrier_managers cm 
            WHERE customer.id_carrier_customer = supplier.id_carrier_supplier AND operador.id = customer.id_carrier_customer AND cm.id_carrier = customer.id_carrier_customer AND cm.id_managers = vendedor.id
            ORDER BY Posicion_neta DESC