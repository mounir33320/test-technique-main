-- Données de test
-- @copyright ©2025 AuCOFFRE.com

-- Nettoyage des données existantes
DELETE FROM account;

-- Réinitialisation de l'auto-increment
DELETE FROM sqlite_sequence WHERE name='account';

-- Insertion des comptes de test
INSERT INTO account (acc_id, acc_balance) VALUES (1, 10000);
INSERT INTO account (acc_id, acc_balance) VALUES (2, 1254);
INSERT INTO account (acc_id, acc_balance) VALUES (3, 2356);