import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initPagination } from "../common/pagination.js";
import { renderBooking } from "./render-booking.js";
import { handleFormBooking } from "./handle-form-booking.js";
import { initCheckInBooking } from "./checkin-booking.js";

document.addEventListener("DOMContentLoaded", initBooking);

export async function initBooking() {
    // Kích hoạt ngay sự kiện nút Tạo mới và Modal Check-in
    handleFormBooking();
    initCheckInBooking();

    await loadItem("admin/bookings", "booking-list", renderBooking);
    initFilter("admin/bookings", "booking-list", renderBooking);
    initPagination("admin/bookings", "booking-list", renderBooking);
    changeMulti("Đơn đặt phòng", "admin/bookings", "booking-list", renderBooking);
}