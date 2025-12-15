USE banque;

DROP PROCEDURE IF EXISTS somme_soldes_sup;

DELIMITER //

CREATE PROCEDURE somme_soldes_sup(
    IN  p_sup DECIMAL(10,2),
    OUT p_total DECIMAL(10,2)
)
BEGIN
    SELECT SUM(solde)
    INTO p_total
    FROM compte
    WHERE solde >= p_sup;
END;
//

DELIMITER ;