-- Initial public communication for the Zealot alpha. This is a one-time,
-- versioned seed so production and a freshly provisioned environment start
-- with the same editable news content.

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Welcome to Zealot Alpha',
  '<p>Zealot has entered its first public alpha phase. The world is online for explorers who want to help shape its systems, balance and community from the beginning.</p><p>Create your account, make a character and begin your journey in Rookgaard. This phase is focused on stability, gameplay feedback and building a welcoming community.</p><p>Thank you for playing early and helping us make Zealot better with every update.</p>',
  1, UNIX_TIMESTAMP('2026-10-29 18:00:00'), 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Welcome to Zealot Alpha');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Alpha: what to expect',
  '<p>During alpha, the game is actively evolving. You may see balancing changes, small restarts and improvements based on player feedback.</p><p>Characters and progress are part of the test environment. Please report anything unusual with as much detail as possible so we can investigate it quickly.</p>',
  1, UNIX_TIMESTAMP('2026-10-29 18:05:00'), 2, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Alpha: what to expect');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'How to help Zealot grow',
  '<p>The most useful alpha reports include the character name, what happened, what you expected and the steps needed to reproduce the issue.</p><p>For account or website support, contact us at <a href="mailto:server.zealot@gmail.com">server.zealot@gmail.com</a>. Community announcements and discussions are also available on Discord.</p>',
  1, UNIX_TIMESTAMP('2026-10-29 18:10:00'), 3, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'How to help Zealot grow');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Zealot Alpha is live',
  'Create your account, explore Rookgaard and help us test the first public build.',
  2, UNIX_TIMESTAMP('2026-10-29 18:15:00'), 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Alpha is live');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Alpha feedback channel',
  'Found an issue or have an idea? Reach us at server.zealot@gmail.com.',
  2, UNIX_TIMESTAMP('2026-10-29 18:20:00'), 2, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Alpha feedback channel');

INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Zealot Alpha: the journey begins',
  '<p>Zealot is now in alpha. This is the beginning of a world built with its players: test the game, share your experience and follow each update as the server grows.</p>',
  3, UNIX_TIMESTAMP('2026-10-29 18:25:00'), 1, 0, '',
  'Join the first public alpha and help shape the future of Zealot.',
  'images/news/announcement.jpg', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Zealot Alpha: the journey begins');
