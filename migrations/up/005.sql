-- Crea la tabla de los tags de las notas.
CREATE TABLE IF NOT EXISTS notes_tags (
  id CHAR(36) NOT NULL,
  note_id CHAR(36) NOT NULL,
  tag_id CHAR(36) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT notes_tags_id_primary PRIMARY KEY(id),
  CONSTRAINT notes_tags_note_id_foreign FOREIGN KEY(note_id)
    REFERENCES notes(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT notes_tags_tag_id_foreign FOREIGN KEY(tag_id)
    REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT notes_tags_note_id_tag_id_unique UNIQUE(note_id, tag_id)
);
