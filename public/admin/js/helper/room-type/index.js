import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initDetailRoomType } from "./detail-room-type.js";
import { handleFormRoomType } from "./handle-form-room-type.js";

export async function initRoomType() {
    await loadItem("admin/rooms-type", "room-type-list");
    handleFormRoomType()
    initFilter("admin/rooms-type","room-type-list");
    initDetailRoomType();
    changeMulti("Loại phòng","admin/rooms-type", "room-type-list");
}