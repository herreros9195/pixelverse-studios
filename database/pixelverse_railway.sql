CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    pseudo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    password_reset_token VARCHAR(128) DEFAULT NULL,
    password_reset_expires_at DATETIME DEFAULT NULL,
    role ENUM('user','employee','admin') DEFAULT 'user',
    status ENUM('active','suspended','deleted') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE accessories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('clothing','armor','accessory','weapon') NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    status ENUM('available','disabled') DEFAULT 'available',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL UNIQUE,
    gender ENUM('male','female') NOT NULL,
    body_style VARCHAR(50),
    ear_shape VARCHAR(50),
    eye_shape VARCHAR(50),
    nose_shape VARCHAR(50),
    mouth_shape VARCHAR(50),
    skin_color VARCHAR(50),
    hair_style VARCHAR(50),
    hair_color VARCHAR(50),
    eye_color VARCHAR(50),
    character_type VARCHAR(50),
    outfit_variant VARCHAR(50) DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    shared TINYINT(1) DEFAULT 0,
    rejection_reason TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE character_accessories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    accessory_id INT NOT NULL,
    equipped_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
    FOREIGN KEY (accessory_id) REFERENCES accessories(id) ON DELETE CASCADE
) ENGINE=InnoDB;
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
CREATE TABLE contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pseudo VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('admin@pixelverse.com', 'admin', '$2y$10$LzaYNiklZYn6GTHRsfhoVuXutd9C4/tip9BF0Ga.JHJyann6/Z.Yy', 'admin', 'active');
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('employee@pixelverse.com', 'employee', '$2y$10$.h91DYHZCljkBx7MRC3tsu5.a0l/A/bciXS3K.4SpVTNE9fKhjrby', 'employee', 'active');
INSERT INTO users (email, pseudo, password_hash, role, status) VALUES
('user@pixelverse.com', 'player1', '$2y$10$a9sJEq9dCoDSDUMUVmPhL.hGiZ2QU9PZ7yk41D8jfZmLNAmJPKLHa', 'user', 'active');
INSERT INTO accessories (name, type, description, status) VALUES
('Epee de fer', 'weapon', 'Une epee robuste pour les debutants.', 'available'),
('Bouclier en bois', 'armor', 'Protection legere mais efficace.', 'available'),
('Cape rouge', 'clothing', 'Une cape elegante qui attire tous les regards.', 'available'),
('Amulette de pouvoir', 'accessory', 'Augmente legerement la magie.', 'available'),
('Hache de guerre', 'weapon', 'Arme devastatrice pour les guerriers.', 'available'),
('Plastron d''acier', 'armor', 'Armure lourde offrant une grande protection.', 'available');
INSERT INTO characters (
    user_id,
    name,
    gender,
    body_style,
    ear_shape,
    eye_shape,
    nose_shape,
    mouth_shape,
    skin_color,
    hair_style,
    hair_color,
    eye_color,
    character_type,
    outfit_variant,
    status,
    shared
) VALUES (
    3,
    'Thalor',
    'male',
    'body_04',
    'ear_01',
    'brow_03',
    'nose_06',
    'face_none',
    'Claire',
    'hair_02',
    'Brun',
    'Vert',
    'Guerrier',
    'warrior_full',
    'approved',
    1
);
INSERT INTO character_accessories (character_id, accessory_id) VALUES
(1, 1), (1, 3);
INSERT INTO reviews (character_id, user_id, rating, comment, status) VALUES
(1, 3, 5, 'Personnage tres bien construit, j''adore les details !', 'approved');