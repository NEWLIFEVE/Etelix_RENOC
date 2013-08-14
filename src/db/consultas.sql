/**
* RENOC Alto Impacto (+10$)
*/
/*Total por Clientes +10$*/
/*Version Eduardo*/
SELECT c.name AS Cliente, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos, x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT b.id_carrier_customer AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE  WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_customer
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id
ORDER BY x.MARGEN DESC;

/*Version Manuel*/
SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_customer = c.id
ORDER BY x.margin DESC;


/*Total Clientes +10$*/
/*Version Eduardo*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT b.id_carrier_customer AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE  WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_customer
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id;

/*Version Manuel*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) balance
WHERE margin >10

/*Total Completo Clientes*/
/*Version Eduardo*/
SELECT 'TOTAL' AS etiqueta, SUM(complete_calls+incomplete_calls) AS TotalCalls, SUM(complete_calls) AS CompleteCalls, SUM(minutes) AS Minutos, SUM(PDD) AS Pdd, SUM(COST) AS Cost, SUM(REVENUE) AS Revenue, SUM(margin) AS Margin
FROM balance b, carrier c, destination_int d
WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'

/*Version Manuel*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
	 FROM balance
	 WHERE date_balance='$fecha'
	 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
	 AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) balance