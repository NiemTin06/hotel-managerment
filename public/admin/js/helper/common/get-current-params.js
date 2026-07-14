// query-state.js
export function getCurrentParams() {
    return new URLSearchParams(window.location.search);
}