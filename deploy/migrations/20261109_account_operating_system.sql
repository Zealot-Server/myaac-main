-- The platform selected by the player when creating an account.
-- Empty values identify accounts created before this field existed.
ALTER TABLE accounts
  ADD COLUMN IF NOT EXISTS operating_system VARCHAR(16) NOT NULL DEFAULT '';
