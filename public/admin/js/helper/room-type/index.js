import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";


export async function initRoomType() {
    await loadItem("admin/rooms-type", "room-type-list");
    initFilter("admin/rooms-type","room-type-list");
    changeMulti("Loại phòng","admin/rooms-type", "room-type-list");
}