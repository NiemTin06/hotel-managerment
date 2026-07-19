import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initPagination } from "../common/pagination.js";
import { renderRoomType } from "./render-room-type.js";

export async function initRoomType() {
    await loadItem("admin/rooms-type", "room-type-list", renderRoomType);
    initFilter("admin/rooms-type","room-type-list", renderRoomType);
    initPagination("admin/rooms-type","room-type-list", renderRoomType);
    changeMulti("Loại phòng","admin/rooms-type", "room-type-list", renderRoomType);
}