-- Schéma de la base de données de test
-- @copyright ©2025 AuCOFFRE.com

-- Table des comptes clients
CREATE TABLE IF NOT EXISTS account (
    acc_id INTEGER PRIMARY KEY AUTOINCREMENT,
    acc_balance INTEGER NOT NULL DEFAULT 0
);