-- MovieSHOP Database
-- Create tables based on the specified data model

-- Create CLIENT table
CREATE TABLE CLIENT (
    ID_CLIENT INT AUTO_INCREMENT PRIMARY KEY,
    NomCli VARCHAR(50) NOT NULL,
    PrenomCli VARCHAR(50) NOT NULL,
    EmailCli VARCHAR(100) NOT NULL UNIQUE,
    AdresseCli VARCHAR(255) NOT NULL,
    Ville VARCHAR(50) NOT NULL,
    Pays VARCHAR(50) NOT NULL,
    Preferences TEXT,
    TelephoneCli VARCHAR(20),
    PhotoCli VARCHAR(255),
    MotDePasse VARCHAR(255) NOT NULL,
    DateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create PRODUIT table
CREATE TABLE PRODUIT (
    ID_PROD INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(100) NOT NULL,
    Realisateur VARCHAR(100) NOT NULL,
    DateSortie DATE NOT NULL,
    PHOTO VARCHAR(255) NOT NULL,
    Durée INT NOT NULL,
    PaysOrigine VARCHAR(50) NOT NULL,
    ActeursPrincipaux TEXT NOT NULL,
    Prix_unitaire DECIMAL(10, 2) NOT NULL,
    Langue VARCHAR(50) NOT NULL,
    GENRE VARCHAR(50) NOT NULL,
    Stock INT NOT NULL DEFAULT 10,
    DateAjout DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create VENDEUR table
CREATE TABLE VENDEUR (
    ID_Vendeur INT AUTO_INCREMENT PRIMARY KEY,
    Nomvendeur VARCHAR(50) NOT NULL,
    PrenomVendeur VARCHAR(50) NOT NULL,
    CIN VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Telephone VARCHAR(20) NOT NULL,
    Adresse VARCHAR(255) NOT NULL,
    PhotoVendeur VARCHAR(255),
    MotDePasse VARCHAR(255) NOT NULL,
    DateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create ACHETER table (for customer purchases)
CREATE TABLE ACHETER (
    ID_CLIENT INT NOT NULL,
    ID_PROD INT NOT NULL,
    DateAchat DATETIME NOT NULL,
    Prix_unitaire DECIMAL(10, 2) NOT NULL,
    Quantite INT NOT NULL,
    PRIMARY KEY (ID_CLIENT, ID_PROD, DateAchat),
    FOREIGN KEY (ID_CLIENT) REFERENCES CLIENT (ID_CLIENT),
    FOREIGN KEY (ID_PROD) REFERENCES PRODUIT (ID_PROD)
);

-- Create VenteProduit table (for vendor sales)
CREATE TABLE VenteProduit (
    ID_Vente INT AUTO_INCREMENT PRIMARY KEY,
    ID_PROD INT NOT NULL,
    ID_Vendeur INT NOT NULL,
    DateVente DATETIME NOT NULL,
    FOREIGN KEY (ID_PROD) REFERENCES PRODUIT (ID_PROD),
    FOREIGN KEY (ID_Vendeur) REFERENCES VENDEUR (ID_Vendeur)
);

-- Create COMMANDE table (for order tracking)
CREATE TABLE COMMANDE (
    ID_Commande INT AUTO_INCREMENT PRIMARY KEY,
    ID_CLIENT INT NOT NULL,
    DateCommande DATETIME NOT NULL,
    Statut VARCHAR(50) NOT NULL,
    Adresse_Livraison TEXT NOT NULL,
    Adresse_Facturation TEXT NOT NULL,
    Total DECIMAL(10, 2) NOT NULL,
    MethodePaiement VARCHAR(50) NOT NULL,
    FOREIGN KEY (ID_CLIENT) REFERENCES CLIENT (ID_CLIENT)
);

-- Create DETAIL_COMMANDE table (for order line items)
CREATE TABLE DETAIL_COMMANDE (
    ID_Detail INT AUTO_INCREMENT PRIMARY KEY,
    ID_Commande INT NOT NULL,
    ID_PROD INT NOT NULL,
    Quantite INT NOT NULL,
    Prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_Commande) REFERENCES COMMANDE (ID_Commande),
    FOREIGN KEY (ID_PROD) REFERENCES PRODUIT (ID_PROD)
);

-- Create FACTURE table (for invoices)
CREATE TABLE FACTURE (
    ID_Facture INT AUTO_INCREMENT PRIMARY KEY,
    ID_Commande INT NOT NULL,
    DateFacture DATETIME NOT NULL,
    Montant_HT DECIMAL(10, 2) NOT NULL,
    Montant_TVA DECIMAL(10, 2) NOT NULL,
    Montant_TTC DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_Commande) REFERENCES COMMANDE (ID_Commande)
);

-- Sample data for testing

-- Sample clients
INSERT INTO CLIENT (NomCli, PrenomCli, EmailCli, AdresseCli, Ville, Pays, Preferences, TelephoneCli, PhotoCli, MotDePasse) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '123 Rue de Paris', 'Paris', 'France', 'Science-Fiction, Action', '+33612345678', 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg', SHA2('password123', 256)),
('Martin', 'Marie', 'marie.martin@example.com', '456 Avenue des Champs', 'Lyon', 'France', 'Comédie, Romance', '+33687654321', 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg', SHA2('password123', 256)),
('Brown', 'John', 'john.brown@example.com', '789 Main St', 'London', 'Royaume-Uni', 'Horreur, Thriller', '+447911123456', 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg', SHA2('password123', 256));

-- Sample products
INSERT INTO PRODUIT (Titre, Realisateur, DateSortie, PHOTO, Durée, PaysOrigine, ActeursPrincipaux, Prix_unitaire, Langue, GENRE) VALUES
('Inception', 'Christopher Nolan', '2010-07-16', 'https://images.pexels.com/photos/7991579/pexels-photo-7991579.jpeg', 148, 'États-Unis', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page', 14.99, 'Anglais', 'Science-Fiction'),
('The Shawshank Redemption', 'Frank Darabont', '1994-09-23', 'https://images.pexels.com/photos/7991432/pexels-photo-7991432.jpeg', 142, 'États-Unis', 'Tim Robbins, Morgan Freeman', 12.99, 'Anglais', 'Drame'),
('Parasite', 'Bong Joon-ho', '2019-05-30', 'https://images.pexels.com/photos/2873486/pexels-photo-2873486.jpeg', 132, 'Corée du Sud', 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong', 19.99, 'Coréen', 'Drame'),
('The Dark Knight', 'Christopher Nolan', '2008-07-18', 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg', 152, 'États-Unis', 'Christian Bale, Heath Ledger, Aaron Eckhart', 14.99, 'Anglais', 'Action'),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'https://images.pexels.com/photos/274937/pexels-photo-274937.jpeg', 154, 'États-Unis', 'John Travolta, Uma Thurman, Samuel L. Jackson', 13.99, 'Anglais', 'Crime'),
('La La Land', 'Damien Chazelle', '2016-12-09', 'https://images.pexels.com/photos/7234256/pexels-photo-7234256.jpeg', 128, 'États-Unis', 'Ryan Gosling, Emma Stone', 15.99, 'Anglais', 'Comédie'),
('The Godfather', 'Francis Ford Coppola', '1972-03-24', 'https://images.pexels.com/photos/3945317/pexels-photo-3945317.jpeg', 175, 'États-Unis', 'Marlon Brando, Al Pacino, James Caan', 12.99, 'Anglais', 'Crime'),
('Amélie', 'Jean-Pierre Jeunet', '2001-04-25', 'https://images.pexels.com/photos/6899542/pexels-photo-6899542.jpeg', 122, 'France', 'Audrey Tautou, Mathieu Kassovitz', 14.99, 'Français', 'Comédie'),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'https://images.pexels.com/photos/924824/pexels-photo-924824.jpeg', 169, 'États-Unis', 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 16.99, 'Anglais', 'Science-Fiction'),
('The Grand Budapest Hotel', 'Wes Anderson', '2014-03-07', 'https://images.pexels.com/photos/3945336/pexels-photo-3945336.jpeg', 99, 'États-Unis', 'Ralph Fiennes, F. Murray Abraham, Mathieu Amalric', 14.99, 'Anglais', 'Comédie');

-- Sample vendors
INSERT INTO VENDEUR (Nomvendeur, PrenomVendeur, CIN, Email, Telephone, Adresse, PhotoVendeur, MotDePasse) VALUES
('Cinéma', 'Express', 'AB123456', 'contact@cinexpress.fr', '+33123456789', '1 Rue du Commerce, Paris', 'https://images.pexels.com/photos/3760067/pexels-photo-3760067.jpeg', SHA2('password123', 256)),
('Film', 'Haven', 'CD789012', 'info@filmhaven.com', '+33987654321', '42 Boulevard des Films, Lyon', 'https://images.pexels.com/photos/6476191/pexels-photo-6476191.jpeg', SHA2('password123', 256));

-- Sample sales
INSERT INTO VenteProduit (ID_PROD, ID_Vendeur, DateVente) VALUES
(1, 1, '2023-04-15 10:30:00'),
(2, 1, '2023-04-15 10:45:00'),
(3, 2, '2023-04-16 14:20:00'),
(4, 2, '2023-04-17 09:15:00'),
(5, 1, '2023-04-18 16:30:00');

-- Sample purchases
INSERT INTO ACHETER (ID_CLIENT, ID_PROD, DateAchat, Prix_unitaire, Quantite) VALUES
(1, 1, '2023-04-20 11:30:00', 14.99, 1),
(1, 3, '2023-04-20 11:30:00', 19.99, 1),
(2, 2, '2023-04-21 15:45:00', 12.99, 2),
(3, 4, '2023-04-22 09:20:00', 14.99, 1),
(3, 5, '2023-04-22 09:20:00', 13.99, 1);

-- Sample orders
INSERT INTO COMMANDE (ID_CLIENT, DateCommande, Statut, Adresse_Livraison, Adresse_Facturation, Total, MethodePaiement) VALUES
(1, '2023-04-20 11:30:00', 'Livré', '123 Rue de Paris, Paris, France', '123 Rue de Paris, Paris, France', 34.98, 'Carte'),
(2, '2023-04-21 15:45:00', 'Livré', '456 Avenue des Champs, Lyon, France', '456 Avenue des Champs, Lyon, France', 25.98, 'PayPal'),
(3, '2023-04-22 09:20:00', 'Livré', '789 Main St, London, Royaume-Uni', '789 Main St, London, Royaume-Uni', 28.98, 'Carte');

-- Sample order details
INSERT INTO DETAIL_COMMANDE (ID_Commande, ID_PROD, Quantite, Prix_unitaire) VALUES
(1, 1, 1, 14.99),
(1, 3, 1, 19.99),
(2, 2, 2, 12.99),
(3, 4, 1, 14.99),
(3, 5, 1, 13.99);

-- Sample invoices
INSERT INTO FACTURE (ID_Commande, DateFacture, Montant_HT, Montant_TVA, Montant_TTC) VALUES
(1, '2023-04-20 11:35:00', 29.15, 5.83, 34.98),
(2, '2023-04-21 15:50:00', 21.65, 4.33, 25.98),
(3, '2023-04-22 09:25:00', 24.15, 4.83, 28.98);