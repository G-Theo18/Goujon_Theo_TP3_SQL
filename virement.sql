USE banque;

ALTER TABLE compte ENGINE=InnoDB;
ALTER TABLE virement ENGINE=InnoDB;

DROP PROCEDURE IF EXISTS virement;

DELIMITER //

CREATE PROCEDURE virement(
    IN  p_source INT,
    IN  p_destination INT,
    IN  p_montant DECIMAL(10,2),
    OUT p_result INT
)
proc: BEGIN
    DECLARE solde_source DECIMAL(10,2);
    DECLARE nb_source INT;
    DECLARE nb_destination INT;
    DECLARE v_dummy INT;

    SET p_result = 0;

    -- Le montant est invalide
    IF p_montant <= 0 THEN
        LEAVE proc;
    END IF;

    -- Vérifier l'existence des comptes
    SELECT COUNT(*) INTO nb_source FROM compte WHERE id = p_source;
    SELECT COUNT(*) INTO nb_destination FROM compte WHERE id = p_destination;

    IF nb_source = 0 OR nb_destination = 0 THEN
        LEAVE proc;
    END IF;

    -- Vérifier le solde du compte source
    SELECT solde INTO solde_source FROM compte WHERE id = p_source;
    IF solde_source < p_montant THEN
        LEAVE proc;
    END IF;

    START TRANSACTION;

    SELECT SLEEP(5) INTO v_dummy;

    UPDATE compte SET solde = solde - p_montant WHERE id = p_source;

    SELECT SLEEP(5) INTO v_dummy;

    UPDATE compte SET solde = solde + p_montant WHERE id = p_destination;

    INSERT INTO virement(compte_source, compte_destination, montant)
    VALUES (p_source, p_destination, p_montant);

    COMMIT;

    SET p_result = 1;

END;
//

DELIMITER ;

