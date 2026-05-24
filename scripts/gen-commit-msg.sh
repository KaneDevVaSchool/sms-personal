#!/usr/bin/env bash
set -euo pipefail

COMMIT_MSG_FILE="${1:?commit message file required}"
SOURCE="${2:-}"

if [[ "$SOURCE" == "message" || "$SOURCE" == "merge" || "$SOURCE" == "squash" ]]; then
  exit 0
fi

if [[ -s "$COMMIT_MSG_FILE" ]]; then
  first_line="$(sed '/^#/d;/^[[:space:]]*$/d' "$COMMIT_MSG_FILE" | head -n 1 || true)"
  if [[ -n "$first_line" ]]; then
    exit 0
  fi
fi

mapfile -t staged_files < <(git diff --cached --name-only --diff-filter=ACMR 2>/dev/null || true)

if [[ ${#staged_files[@]} -eq 0 ]]; then
  exit 0
fi

declare -A type_scores=(
  [feat]=0
  [fix]=0
  [style]=0
  [test]=0
  [docs]=0
  [chore]=0
  [refactor]=0
)

declare -A scope_counts=()

score_type() {
  local path="$1"
  case "$path" in
    tests/*|*Test.php|*.test.*|*.spec.*)
      type_scores[test]=$((type_scores[test] + 3))
      ;;
    docs/*|*.md)
      type_scores[docs]=$((type_scores[docs] + 3))
      ;;
    package.json|package-lock.json|composer.json|composer.lock|.github/*|Dockerfile|docker-compose*|.dockerignore|postcss.config.js|vite.config.js)
      type_scores[chore]=$((type_scores[chore] + 3))
      ;;
    resources/css/*|*.css)
      type_scores[style]=$((type_scores[style] + 2))
      ;;
    resources/js/*|resources/views/*|public/*)
      type_scores[feat]=$((type_scores[feat] + 2))
      ;;
    routes/*|app/Http/Controllers/*|app/Models/*|database/migrations/*)
      type_scores[feat]=$((type_scores[feat] + 2))
      ;;
    app/*|bootstrap/*|config/*|database/*)
      type_scores[refactor]=$((type_scores[refactor] + 1))
      ;;
    *)
      type_scores[refactor]=$((type_scores[refactor] + 1))
      ;;
  esac

  if git diff --cached -- "$path" 2>/dev/null | grep -qiE '^\+.*fix|^\-.*bug|hotfix'; then
    type_scores[fix]=$((type_scores[fix] + 2))
  fi
}

derive_scope() {
  local path="$1"
  local scope=""

  if [[ "$path" =~ resources/js/modules/([^/]+)/ ]]; then
    scope="${BASH_REMATCH[1]}"
  elif [[ "$path" =~ resources/js/Pages/Auth/ ]]; then
    scope="auth"
  elif [[ "$path" =~ resources/js/Pages/Profile/ ]]; then
    scope="profile"
  elif [[ "$path" =~ resources/js/(Layouts|Components)/ ]]; then
    scope="ui"
  elif [[ "$path" =~ app/Http/Controllers/Auth/ ]]; then
    scope="auth"
  elif [[ "$path" =~ routes/ ]]; then
    scope="routes"
  elif [[ "$path" =~ \.github/workflows/ ]]; then
    scope="ci"
  elif [[ "$path" =~ ^\.husky/ ]]; then
    scope="ci"
  elif [[ "$path" =~ ^database/migrations/ ]]; then
    scope="db"
  elif [[ "$path" =~ ^tests/ ]]; then
    scope="test"
  else
    scope="$(echo "$path" | cut -d/ -f1 | tr '[:upper:]' '[:lower:]' | sed 's/^\.//')"
  fi

  scope="${scope// /-}"
  if [[ -n "$scope" ]]; then
    scope_counts["$scope"]=$(( ${scope_counts[$scope]:-0} + 1 ))
  fi
}

basename_no_ext() {
  local file="$1"
  local base
  base="$(basename "$file")"
  echo "${base%.*}"
}

for file in "${staged_files[@]}"; do
  score_type "$file"
  derive_scope "$file"
done

commit_type="chore"
max_score=-1
for t in feat fix style test docs chore refactor; do
  if (( type_scores[$t] > max_score )); then
    max_score=${type_scores[$t]}
    commit_type=$t
  fi
done

scope=""
max_scope=-1
for s in "${!scope_counts[@]}"; do
  if (( scope_counts[$s] > max_scope )); then
    max_scope=${scope_counts[$s]}
    scope=$s
  fi
done

declare -a subjects=()
for file in "${staged_files[@]}"; do
  subjects+=("$(basename_no_ext "$file")")
done

unique_subject="$(printf '%s\n' "${subjects[@]}" | sort -u | tr '\n' ' ' | sed 's/ $//')"
if [[ ${#staged_files[@]} -eq 1 ]]; then
  action="update $(basename_no_ext "${staged_files[0]}")"
elif [[ ${#staged_files[@]} -le 3 ]]; then
  action="update ${unique_subject}"
else
  action="update ${#staged_files[@]} files (${unique_subject:0:40}...)"
fi

action="$(echo "$action" | tr '[:upper:]' '[:lower:]')"
action="$(echo "$action" | sed 's/[[:space:]]\+/ /g')"

if [[ -n "$scope" ]]; then
  header="${commit_type}(${scope}): ${action}"
else
  header="${commit_type}: ${action}"
fi

if [[ ${#header} -gt 72 ]]; then
  header="${header:0:69}..."
fi

printf '%s\n' "$header" >"$COMMIT_MSG_FILE"
