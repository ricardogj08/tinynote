-- Crea la tabla de los usuarios.
CREATE TABLE IF NOT EXISTS users (
  id VARCHAR(36) NOT NULL,
  username VARCHAR(32) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  active BOOLEAN NOT NULL DEFAULT FALSE,
  is_admin BOOLEAN NOT NULL DEFAULT FALSE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT users_id_primary PRIMARY KEY(id),
  CONSTRAINT users_username_unique UNIQUE(username),
  CONSTRAINT users_email_unique UNIQUE(email)
);

INSERT INTO users(id, username, email, password, active, is_admin) VALUES
  ('a394ca44-7ff4-498a-9ce4-2f9ba7f57071', 'admin', 'admin@example.com', '$2y$10$WCTV774UrzVepTglmzV5NOqYtkH3dMO9uAjWCrYbIOcxBjQrRAAEi', TRUE, TRUE);
