-- `deletion` is the Canary soft-deletion timestamp. Older MyAAC deletes used
-- the value 1, which has no usable restoration deadline. Start their 30-day
-- grace period when this migration is applied.
UPDATE `players`
SET `deletion` = UNIX_TIMESTAMP()
WHERE `deletion` = 1;
