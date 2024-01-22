-- Crea la tabla de roles de los usuarios.
CREATE TABLE IF NOT EXISTS roles (
  id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(16) NOT NULL,
  description VARCHAR(32) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT roles_id_primary PRIMARY KEY(id),
  CONSTRAINT roles_name_unique UNIQUE(name)
);

INSERT INTO roles(id, name, description) VALUES
  (1, 'admin', 'Administrator'),
  (2, 'user', 'General user');
