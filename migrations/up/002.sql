-- Crea la tabla de usuarios.
CREATE TABLE IF NOT EXISTS users (
  id VARCHAR(36) NOT NULL,
  role_id TINYINT UNSIGNED NOT NULL,
  username VARCHAR(32) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  active BOOLEAN NOT NULL DEFAULT FALSE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT users_id_primary PRIMARY KEY(id),
  CONSTRAINT users_role_id_foreign FOREIGN KEY(role_id)
    REFERENCES roles(id)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT users_username_unique UNIQUE(username),
  CONSTRAINT users_email_unique UNIQUE(email)
);

INSERT INTO users(id, role_id, username, email, password, active) VALUES
  ('a394ca44-7ff4-498a-9ce4-2f9ba7f57071', 1, 'admin', 'admin@example.com', '$2y$10$bKmbeRH2D1sMis.iDUrzU.HlddYsUH.5vU4B8SBjXImui/tb6PAsy', TRUE);
