-- Refresh the existing announcement after the validated 1.0.4 release is public.
UPDATE myaac_news
SET
  title = 'Zealot Client 1.0.4 is available',
  body = '<p><strong>Coin conversion is live.</strong> Use a full stack of 100 gold coins to make 1 platinum coin, or 100 platinum coins to make 1 crystal coin. With Classic Control, right click the full stack. In another control mode, choose Use. The new coin stays in the same inventory slot. You can still use a single platinum or crystal coin to change it back into 100 coins of the lower denomination.</p><p>The updated world map is live on the server, and the free training area in Thais remains available.</p><p><strong>Client 1.0.4 is available for Windows, Linux and macOS.</strong> It includes the complete minimap for the updated world, smoother long distance minimap travel, version checks at login and an automatic updater for future releases. Players on 1.0.3 should download and install 1.0.4 from the <a href="/?subtopic=downloadclient">client download page</a> once. Later releases can use the automatic updater.</p>'
WHERE title = 'Coins are live, client 1.0.4 is next';
