import { API } from "../../api/api.js";
import { renderRoomType } from "../room-type/render-room-type.js";
import { loadItem } from "./load-item.js";

export function initFilter(link, container) {
    const form = document.querySelector("[filter-form]");
    console.log(form);

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const params = new URLSearchParams(new FormData(form));
        loadItem(`${link}/data?${params.toString()}`, container)
    });
}