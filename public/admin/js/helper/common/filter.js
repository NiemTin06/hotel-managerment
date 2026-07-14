import { API } from "../../api/api.js";
import { renderRoomType } from "../room-type/render-room-type.js";
import { loadItem } from "./load-item.js";

export function initFilter(link, container) {
    const form = document.querySelector("[filter-form]");
    if (!form) return;
    // Do du lieu len URL tu FROM
    const currentParams = new URLSearchParams(window.location.search);
    
    Array.from(form.elements).forEach(element => {
        if (element.name && currentParams.has(element.name)) {
            element.value = currentParams.get(element.name);
        }
    });
    
    const initialQuery = currentParams.toString() ? `?${currentParams.toString()}` : '';
    loadItem(`${link}/data${initialQuery}`, container);

    // Dong bo FORM len url
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const params = new URLSearchParams(new FormData(form));
        const queryString = params.toString();
        const newUrl = queryString 
            ? `${window.location.pathname}?${queryString}`
            : window.location.pathname;
        window.history.pushState({ path: newUrl }, '', newUrl);
        const apiQuery = queryString ? `?${queryString}` : ''
        loadItem(`${link}/data?${apiQuery}`, container)
    });

    // --- Xử lý khi người dùng bấm nút Back/Forward của trình duyệt ---
    window.addEventListener('popstate', () => {
        const popParams = new URLSearchParams(window.location.search);
        Array.from(form.elements).forEach(element => {
            if (element.name) {
                element.value = popParams.get(element.name) || '';
            }
        });
        const popQuery = popParams.toString() ? `?${popParams.toString()}` : '';
        loadItem(`${link}/data${popQuery}`, container);
    });
} 