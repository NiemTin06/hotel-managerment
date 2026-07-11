import { loadItem } from "../common/load-item.js";
import { createRoomType } from "./create-room-type.js";
// import { initFilterRoom } from "./filter-room.js";
// import { changeMulti } from "./change-multi.js";

export async function initRoomType() {
    await loadItem("admin/rooms-type", "room-type-list");
    createRoomType();
    // initFilterRoom();
    // changeMulti();
}