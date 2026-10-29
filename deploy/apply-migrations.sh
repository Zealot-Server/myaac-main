#!/usr/bin/env bash
set -euo pipefail

readonly DB_CONTAINER="${DB_CONTAINER:-otbr-db-1}"
readonly MIGRATIONS_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/migrations" && pwd)"

run_sql() {
  docker exec -i "$DB_CONTAINER" sh -lc 'exec mariadb --batch --skip-column-names -uroot -p"$MARIADB_ROOT_PASSWORD" "$MARIADB_DATABASE"'
}

run_sql <<'SQL'
CREATE TABLE IF NOT EXISTS myaac_schema_migrations (
  id VARCHAR(255) NOT NULL PRIMARY KEY,
  applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
SQL

for migration in "$MIGRATIONS_DIR"/*.sql; do
  [ -e "$migration" ] || continue

  migration_id="$(basename "$migration")"
  applied="$({
    run_sql <<SQL
SELECT COUNT(*) FROM myaac_schema_migrations WHERE id = '${migration_id}';
SQL
  } | tr -d '[:space:]')"

  if [ "$applied" = "1" ]; then
    echo "Already applied: $migration_id"
    continue
  fi

  echo "Applying: $migration_id"
  run_sql < "$migration"
  run_sql <<SQL
INSERT INTO myaac_schema_migrations (id) VALUES ('${migration_id}');
SQL
done
