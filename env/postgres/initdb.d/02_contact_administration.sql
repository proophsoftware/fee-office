CREATE SCHEMA contact;

CREATE TABLE contact.contact_card (
  id UUID NOT NULL,
  doc JSONB NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX on contact.contact_card ((doc->'createdAt') ASC);
CREATE INDEX on contact.contact_card ((doc->'name') ASC);

CREATE USER contact WITH PASSWORD 'changeme';

ALTER USER contact SET search_path TO contact;

GRANT ALL PRIVILEGES ON SCHEMA contact to contact;

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA contact to contact;