import { API } from "../../api/api.js";
import { renderRooms } from "./render-room.js";

export function initFilterRoom() {
    const form = document.querySelector("[filter-form]");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(form));
        const rooms = await API.get(`rooms/data?${params.toString()}`);
        renderRooms(rooms);
    });
}