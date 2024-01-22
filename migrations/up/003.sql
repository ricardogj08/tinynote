-- Crea la tabla de notas de los usuarios.
CREATE TABLE IF NOT EXISTS notes (
  id CHAR(36) NOT NULL,
  user_id CHAR(36) NOT NULL,
  title VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT notes_id_primary PRIMARY KEY(id),
  CONSTRAINT notes_user_id_foreign FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
);
