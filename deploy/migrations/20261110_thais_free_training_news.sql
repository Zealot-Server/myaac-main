-- Latest News entry for the free Thais training area.
-- The site translates this canonical English title and body per language.
INSERT INTO myaac_news (`title`, `body`, `type`, `date`, `category`, `player_id`, `comments`, `article_text`, `article_image`, `hidden`)
SELECT
  'Free training area now open in Thais',
  '<p><strong>A new free training area is open in Thais.</strong> Free Account players can practice their skills with Training Monks without Premium time. Use the images below to recognize the entrance and the training rooms.</p><p>Bring your character to Thais and start training today.</p><div class="zealot-training-news-gallery"><figure><a href="/images/news/thais-training-entrance.webp" target="_blank" rel="noopener noreferrer"><img src="/images/news/thais-training-entrance.webp" alt="Entrance to the training area in Thais" loading="lazy" decoding="async"></a><figcaption>Find the entrance in Thais</figcaption></figure><figure><a href="/images/news/thais-training-area.webp" target="_blank" rel="noopener noreferrer"><img src="/images/news/thais-training-area.webp" alt="Training rooms with Training Monks" loading="lazy" decoding="async"></a><figcaption>Train with the Training Monks</figcaption></figure></div>',
  1, UNIX_TIMESTAMP(), 1, 0, '', '', '', 0
WHERE NOT EXISTS (SELECT 1 FROM myaac_news WHERE title = 'Free training area now open in Thais');
