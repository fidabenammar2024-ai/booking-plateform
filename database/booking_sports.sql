CREATE DATABASE IF NOT EXISTS booking_sports
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE booking_sports;


-- ============================================
-- USERS
-- ============================================

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('admin', 'user')
        NOT NULL
        DEFAULT 'user',

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- FIELDS
-- ============================================

CREATE TABLE IF NOT EXISTS fields (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    sport_type ENUM(
        'football',
        'basket',
        'tennis'
    ) NOT NULL,

    location VARCHAR(255),

    price DECIMAL(10,2)
        NOT NULL
        DEFAULT 0.00,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CHECK (price >= 0)
);


-- ============================================
-- RESERVATIONS
-- ============================================

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    field_id INT NOT NULL,

    date DATE NOT NULL,

    start_time TIME NOT NULL,

    end_time TIME NOT NULL,

    status ENUM(
        'pending',
        'confirmed',
        'cancelled'
    )
    NOT NULL
    DEFAULT 'pending',

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reservation_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_reservation_field
        FOREIGN KEY (field_id)
        REFERENCES fields(id)
        ON DELETE RESTRICT,

    CHECK (end_time > start_time)
);


-- ============================================
-- FIELD AVAILABILITY SETTINGS
-- ============================================

CREATE TABLE IF NOT EXISTS field_availability_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,

    field_id INT NOT NULL,

    day_of_week INT NOT NULL,

    opening_time TIME NULL,

    closing_time TIME NULL,

    slot_duration INT
        NOT NULL
        DEFAULT 60,

    is_closed TINYINT(1)
        NOT NULL
        DEFAULT 0,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        NULL
        DEFAULT NULL,

    CONSTRAINT fk_availability_field
        FOREIGN KEY (field_id)
        REFERENCES fields(id)
        ON DELETE CASCADE,

    CONSTRAINT chk_day_of_week
        CHECK (day_of_week BETWEEN 1 AND 7),

    CONSTRAINT chk_slot_duration
        CHECK (slot_duration > 0),

    CONSTRAINT chk_is_closed
        CHECK (is_closed IN (0, 1)),

    UNIQUE KEY unique_field_day (
        field_id,
        day_of_week
    )
);


-- ============================================
-- INDEXES
-- ============================================

CREATE INDEX idx_reservation_user_date
ON reservations (
    user_id,
    date
);

CREATE INDEX idx_reservation_field_date
ON reservations (
    field_id,
    date
);

CREATE INDEX idx_reservation_availability
ON reservations (
    field_id,
    date,
    status,
    start_time,
    end_time
);


-- ============================================
-- INITIAL DATA
-- ============================================

INSERT INTO fields (
    name,
    sport_type,
    location,
    price
)
SELECT
    'Terrain Foot 1',
    'football',
    'Centre Sportif A',
    50.00
WHERE NOT EXISTS (
    SELECT 1
    FROM fields
    WHERE name = 'Terrain Foot 1'
);


INSERT INTO fields (
    name,
    sport_type,
    location,
    price
)
SELECT
    'Terrain Basket 1',
    'basket',
    'Centre Sportif B',
    30.00
WHERE NOT EXISTS (
    SELECT 1
    FROM fields
    WHERE name = 'Terrain Basket 1'
);


INSERT INTO fields (
    name,
    sport_type,
    location,
    price
)
SELECT
    'Terrain Tennis 1',
    'tennis',
    'Club Tennis C',
    25.00
WHERE NOT EXISTS (
    SELECT 1
    FROM fields
    WHERE name = 'Terrain Tennis 1'
);