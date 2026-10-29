-- The public Donation page is distinct from the transactional Shop Offer.
UPDATE myaac_menu
SET `link` = 'donations'
WHERE `template` = 'tibiacom' AND `name` = 'Donate' AND `link` = 'donate';

-- Boxes is a legacy PagSeguro surprise-box checkout with no configured item
-- or payment provider. Keep the implementation available for future work but
-- remove the non-functional navigation entry.
DELETE FROM myaac_menu
WHERE `template` = 'tibiacom' AND (`name` = 'Boxes' OR `link` = 'boxes');
