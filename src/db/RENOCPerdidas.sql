
SELECT c.name AS cliente, d.name AS destino, s.name AS proveedor, b.margin AS margin, b.minutes AS minutes, b.cost AS cost, b.revenue AS revenue
FROM(SELECT id_carrier_customer, id_destination_int, id_carrier_supplier, SUM(margin) AS margin, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue
  FROM balance
  WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
  GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
  ORDER BY margin ASC) b,
     carrier c,
     carrier s,
     destination_int d
WHERE b.id_carrier_customer=c.id AND d.id=b.id_destination_int AND b.id_carrier_supplier=s.id AND b.margin<0
ORDER BY margin ASC;

SELECT SUM(b.margin) AS margin, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue
FROM(SELECT SUM(margin) AS margin, SUM(cost) AS cost, SUM(revenue) AS revenue
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
     ORDER BY margin ASC) b
WHERE b.margin<0;