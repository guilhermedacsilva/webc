
ALTER TABLE code ADD forbidden BOOLEAN;
UPDATE code SET forbidden = 0;
ALTER TABLE code MODIFY forbidden BOOLEAN NOT NULL;

