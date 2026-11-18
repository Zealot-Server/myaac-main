-- Announce the live coin conversion and the upcoming client 1.0.4.
-- The English title is a stable key for the three site translations.
INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Coins are live, client 1.0.4 is next',
  '<p><strong>Coin conversion is live.</strong> Use a full stack of 100 gold coins to make 1 platinum coin, or 100 platinum coins to make 1 crystal coin. With Classic Control, right click the full stack. In another control mode, choose Use. The new coin stays in the same inventory slot. You can still use a single platinum or crystal coin to change it back into 100 coins of the lower denomination.</p><p>The updated world map is live on the server, and the free training area in Thais remains available.</p><p><strong>Client 1.0.4 is in final validation.</strong> It will add the complete minimap for the updated world, smoother long distance minimap travel, version checks at login and an automatic updater for future releases. The public download remains 1.0.3 until the new release passes its package tests. Players on 1.0.3 will need to install 1.0.4 from the <a href="/?subtopic=downloadclient">client download page</a> once published.</p>',
  1, UNIX_TIMESTAMP(), 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Coins are live, client 1.0.4 is next');
