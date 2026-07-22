// delete-booking.js
import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";
import { renderBooking } from "./render-booking.js";
import { getQueryString } from "../common/url.js";
export function initDeleteBooking() {
    document.querySelectorAll("[delete-booking]").forEach(btn => {
        btn.onclick = async () => {
            if (!confirm("Bạn có chắc chắn muốn xóa đơn đặt phòng này?")) return;
            const res = await API.delete("admin/bookings/delete", { ids: [btn.dataset.id] });
            alert(res.message);
            if (res.success) await loadItem("admin/bookings", "booking-list", renderBooking, getQueryString());
        };
    });
}