import { API } from "../api/api.js";
import { renderRooms } from "./render-room.js";

export function initFilterRoom() {
    const form = document.querySelector("[filter-form]");
    console.log("sdfds")
    console.log(form);

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        console.log("submit");
        e.preventDefault();

        const params = new URLSearchParams(new FormData(form));

        console.log(params.toString());

        const rooms = await API.get(`rooms/data?${params.toString()}`);

        console.log(rooms);

        renderRooms(rooms);
    });
}