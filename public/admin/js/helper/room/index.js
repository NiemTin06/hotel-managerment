import { loadRooms } from "./load-room.js";
import { initFilterRoom } from "./filter-room.js";
import { changeMulti } from "./change-multi.js";

export async function initRoom() {
    await loadRooms();

    initFilterRoom();
    changeMulti();
}