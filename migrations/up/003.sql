-- Crea la tabla de tags de los usuarios.
CREATE TABLE IF NOT EXISTS tags (
  id CHAR(36) NOT NULL,
  user_id CHAR(36) NOT NULL,
  name VARCHAR(64) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
    CONSTRAINT tags_id_primary PRIMARY KEY(id),
    CONSTRAINT tags_user_id_foreign FOREIGN KEY(user_id)
      REFERENCES users(id)
      ON DELETE CASCADE
      ON UPDATE RESTRICT,
    CONSTRAINT tags_user_id_name_unique UNIQUE(user_id, name)
);
