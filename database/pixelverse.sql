-- ==============================================
-- Base de données : pixelverse
-- Système de Gestion de Personnages
-- FantasyRealm Online - PixelVerse Studios
-- ==============================================

CREATE DATABASE IF NOT EXISTS pixelverse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pixelverse;

-- ----------------------------------------------
-- Table : users (comptes utilisateurs)
-- ----------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    pseudo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user','employee','admin') DEFAULT 'user',
    status ENUM('active','suspended','deleted') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Table : accessories (articles de personnalisation)
-- ----------------------------------------------
CREATE TABLE accessories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('clothing','armor','accessory','weapon') NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    status ENUM('available','disabled') DEFAULT 'available',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Table : characters (personnages)
-- ----------------------------------------------
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL UNIQUE,
    gender ENUM('male','female','other') NOT NULL,
    eye_shape VARCHAR(50),
    nose_shape VARCHAR(50),
    mouth_shape VARCHAR(50),
    skin_color VARCHAR(50),
    hair_color VARCHAR(50),
    eye_color VARCHAR(50),
    character_type VARCHAR(50),
    build VARCHAR(50),
    age_group VARCHAR(50),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    shared TINYINT(1) DEFAULT 0,
    rejection_reason TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Table : character_accessories (lien personnage / accessoire)
-- ----------------------------------------------
CREATE TABLE character_accessories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    accessory_id INT NOT NULL,
    equipped_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
    FOREIGN KEY (accessory_id) REFERENCES accessories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Table : reviews (avis / commentaires)
-- ----------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Table : contact_requests (demandes de contact)
-- ----------------------------------------------
CREATE TABLE contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pseudo VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------
-- Insertion des données de test
-- ----------------------------------------------

-- Administrateur par défaut (mot de passe : Admin@123)
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('admin@pixelverse.com', 'admin', '$2y$10$LzaYNiklZYn6GTHRsfhoVuXutd9C4/tip9BF0Ga.JHJyann6/Z.Yy', 'admin', 'active');

-- Employé de test (mot de passe : Employee@123)
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('employee@pixelverse.com', 'employee', '$2y$10$.h91DYHZCljkBx7MRC3tsu5.a0l/A/bciXS3K.4SpVTNE9fKhjrby', 'employee', 'active');

-- Utilisateur de test (mot de passe : User@123)
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('user@pixelverse.com', 'player1', '$2y$10$a9sJEq9dCoDSDUMUVmPhL.hGiZ2QU9PZ7yk41D8jfZmLNAmJPKLHa', 'user', 'active');

-- Accessoires de base
INSERT INTO accessories (name, type, description, status) VALUES
('Épée de fer', 'weapon', 'Une épée robuste pour les débutants.', 'available'),
('Bouclier en bois', 'armor', 'Protection légère mais efficace.', 'available'),
('Cape rouge', 'clothing', 'Une cape élégante qui attire tous les regards.', 'available'),
('Amulette de pouvoir', 'accessory', 'Augmente légèrement la magie.', 'available'),
('Hache de guerre', 'weapon', 'Arme dévastatrice pour les guerriers.', 'available'),
('Plastron d\'acier', 'armor', 'Armure lourde offrant une grande protection.', 'available');

-- Personnage de test
INSERT INTO characters (user_id, name, gender, eye_shape, nose_shape, mouth_shape, skin_color, hair_color, eye_color, character_type, build, age_group, status, shared) VALUES
(3, 'Thalor', 'male', 'Amande', 'Droit', 'Fine', 'Claire', 'Brun', 'Vert', 'Guerrier', 'Musclé', 'Adulte', 'approved', 1);

-- Associer des accessoires au personnage
INSERT INTO character_accessories (character_id, accessory_id) VALUES
(1, 1), (1, 3);

-- Avis de test
INSERT INTO reviews (character_id, user_id, rating, comment, status) VALUES
(1, 3, 5, 'Personnage très bien construit, j\'adore les détails !', 'approved');
