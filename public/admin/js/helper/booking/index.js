import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initPagination } from "../common/pagination.js";
import { renderRoom } from "./render-room.js";


export async function initBooking() {
    console.log("hello")
    // await loadItem("admin/rooms", "room-list", renderRoom);
    // initFilter("admin/rooms","room-list", renderRoom);
    // initPagination("admin/rooms","room-list", renderRoom);
    // changeMulti("Loại phòng","admin/rooms", "room-list", renderRoom);
}