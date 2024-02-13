-- Crea la tabla de los tags de los usuarios.
CREATE TABLE IF NOT EXISTS tags (
  id CHAR(36) NOT NULL,
  user_id CHAR(36) NOT NULL,
  name VARCHAR(64) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT tags_id_primary PRIMARY KEY(id),
    CONSTRAINT tags_user_id_foreign FOREIGN KEY(user_id)
      REFERENCES users(id)
      ON DELETE CASCADE
      ON UPDATE RESTRICT,
    CONSTRAINT tags_user_id_name_unique UNIQUE(user_id, name)
);
