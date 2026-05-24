#!/usr/bin/env sh
# Git hooks run under Git Bash on Windows; ServBay shims break npx. Prefer real node/npm paths.

case "$(uname -s 2>/dev/null)" in
MINGW* | MSYS* | CYGWIN*)
    _path_clean=""
    _old_ifs=$IFS
    IFS=:
    for _part in $PATH; do
        case "$_part" in
        *[Ss]erv[Bb]ay* | *%APPDATA%*) continue ;;
        esac
        if [ -n "$_path_clean" ]; then
            _path_clean="$_path_clean:$_part"
        else
            _path_clean="$_part"
        fi
    done
    IFS=$_old_ifs
    PATH="$_path_clean"
    for _node_dir in \
        "/c/Program Files/nodejs" \
        "/c/laragon/bin/nodejs/node-v18" \
        "$HOME/AppData/Roaming/nvm/current"; do
        if [ -x "$_node_dir/node.exe" ] 2>/dev/null || [ -x "$_node_dir/node" ] 2>/dev/null; then
            PATH="$_node_dir:$PATH"
            break
        fi
    done
    export PATH
    ;;
esac

husky_repo_root() {
    if [ -n "${HUSKY_ROOT:-}" ] && [ -d "$HUSKY_ROOT" ]; then
        echo "$HUSKY_ROOT"
        return
    fi
    _hook_dir=$(cd "$(dirname "$0")" && pwd)
    case "$_hook_dir" in
    */.husky)
        cd "$(dirname "$_hook_dir")" && pwd
        return
        ;;
    esac
    if [ -f "$(pwd)/package.json" ]; then
        pwd
        return
    fi
    cd "$(dirname "$0")/.." && pwd
}

run_node_tool() {
    _root=$(husky_repo_root)
    cd "$_root" || exit 1
    if command -v node >/dev/null 2>&1; then
        node "$@"
        return $?
    fi
    if [ -x "/c/Program Files/nodejs/node.exe" ]; then
        "/c/Program Files/nodejs/node.exe" "$@"
        return $?
    fi
    echo "husky: node not found in PATH" >&2
    return 127
}
