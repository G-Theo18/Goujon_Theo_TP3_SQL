DROP DATABASE IF EXISTS banque;
CREATE DATABASE banque;
USE banque;

-- Table des comptes bancaires
CREATE TABLE compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solde DECIMAL(10,2) NOT NULL CHECK (solde >= 0)
);

-- Table de l'historique des virements
CREATE TABLE virement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compte_source INT NOT NULL,
    compte_destination INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL CHECK (montant > 0),
    date_virement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (compte_source) REFERENCES compte(id),
    FOREIGN KEY (compte_destination) REFERENCES compte(id)
);

INSERT INTO compte (solde) VALUES (1000.00);
INSERT INTO compte (solde) VALUES (500.50);
INSERT INTO compte (solde) VALUES (250.00);
INSERT INTO compte (solde) VALUES (2000.00);

