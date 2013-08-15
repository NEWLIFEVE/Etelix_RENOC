/**
* Reporte que muestra los 100 operadores por posicion neta
*/
/*Muestra los cien primeros*/
SELECT o.name AS operadora, m.name AS vendedor, c.minutes, c.revenue, c.margin, s.minutes, s.cost, s.margin
FROM (SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, SUM(margin) AS margin
      FROM balance
      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
      GROUP BY id_carrier_customer
      ORDER BY id_carrier_customer) c,
     (SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(margin) AS margin
      FROM balance
      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
      GROUP BY id_carrier_supplier
      ORDER BY id_carrier_supplier) s,
      carrier o,
      managers m,
      carrier_managers cm
WHERE c.id_carrier_customer = s.id_carrier_supplier AND c.id_carrier_customer = o.id AND cm.id_carrier = o.id AND cm.id_managers = m.id
ORDER BY id_carrier_customer ASC