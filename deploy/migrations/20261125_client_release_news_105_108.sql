-- Four independent release announcements; idempotent by title.

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT 'Zealot Client 1.0.5 released', '<p>Client 1.0.5 improves the Windows update installer and adds automated checks for the desktop update process.</p><p>Players using Windows 1.0.4 were advised to install this release manually once.</p><p><a href="/?subtopic=downloadclient">Download the latest version</a></p>', 1, 1795437056, 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Client 1.0.5 released');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT 'Zealot Client 1.0.6 released', '<p>Client 1.0.6 fixes automatic package downloads when GitHub redirects the download. The updater continues to check the package before installation.</p><p>Versions 1.0.4 and 1.0.5 need a one-time manual installation of a newer client.</p><p><a href="/?subtopic=downloadclient">Download the latest version</a></p>', 1, 1795615574, 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Client 1.0.6 released');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT 'Zealot Client 1.0.7 released', '<p>Client 1.0.7 improves the update window with neatly stacked buttons and brighter text, making the choices easier to read.</p><p>The new window appears when the installed 1.0.7 or newer client offers a later update.</p><p><a href="/?subtopic=downloadclient">Download the latest version</a></p>', 1, 1795621761, 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Client 1.0.7 released');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT 'Zealot Client 1.0.8 released', '<p>Client 1.0.8 avoids a premature missing-assets error and checks the required game files before login. Its updater preserves existing game data for later updates when the new package does not supply the same version.</p><p>The update from 1.0.7 still uses the previous installer, so game data may need to be downloaded again or recovered from its backup once. Preservation was tested in the installers; a full production update with real assets remains to be validated.</p><p><a href="/?subtopic=downloadclient">Download the latest version</a></p>', 1, 1795629241, 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Client 1.0.8 released');
