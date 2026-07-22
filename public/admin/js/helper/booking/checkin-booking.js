import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";
import { renderBooking } from "./render-booking.js";
import { getQueryString } from "../common/url.js";

export function initCheckInBooking() {
    const popupCheckIn = document.querySelector("#popup-checkin");
    const btnClose = document.querySelector("#btn-close-checkin");
    const btnConfirm = document.querySelector("#btn-confirm-checkin");
    const selectRoom = document.querySelector("#select-available-room");

    if (btnClose && popupCheckIn) {
        btnClose.onclick = () => popupCheckIn.classList.remove("show");
        popupCheckIn.onclick = (e) => { if (e.target === popupCheckIn) popupCheckIn.classList.remove("show"); };
    }

    let currentBookingId = 0;

    // Lắng nghe sự kiện click mở Check-in bằng Event Delegation trên bảng
    document.addEventListener("click", async (e) => {
        const btnCI = e.target.closest("[btn-open-checkin]");
        if (btnCI) {
            currentBookingId = btnCI.dataset.id;
            const typeId = btnCI.dataset.type;

            document.querySelector("#ci-booking-id").textContent = `#${currentBookingId}`;
            document.querySelector("#ci-customer-name").textContent = btnCI.dataset.name;
            document.querySelector("#ci-roomtype-name").textContent = btnCI.dataset.typename;

            // Gọi API lấy phòng trống của Loại phòng này
            selectRoom.innerHTML = `<option value="">-- Đang tìm phòng trống... --</option>`;
            popupCheckIn.classList.add("show");

            const res = await API.get(`admin/bookings/available-rooms/${typeId}`);
            if (res.success && res.rooms.length > 0) {
                selectRoom.innerHTML = res.rooms.map(r => `<option value="${r.ROOM_ID}">Phòng ${r.ROOM_NUMBER} (${r.ROOM_DESCRIPTION || 'Sẵn sàng'})</option>`).join("");
            } else {
                selectRoom.innerHTML = `<option value="">⚠️ Hết phòng trống cho loại này!</option>`;
            }
        }

        // Xử lý nút Check-out nhanh
        const btnCO = e.target.closest("[btn-checkout]");
        if (btnCO) {
            if (!confirm("Xác nhận khách đã thanh toán và trả phòng?")) return;
            const id = btnCO.dataset.id;
            const roomId = btnCO.dataset.room;
            const res = await API.post(`admin/bookings/checkout/${id}`, { roomId });
            alert(res.message);
            if (res.success) {
                await loadItem("admin/bookings", "booking-list", renderBooking, getQueryString());
            }
        }
    });

    // Bấm nút Xác nhận gán phòng
    if (btnConfirm) {
        btnConfirm.onclick = async () => {
            const roomId = selectRoom.value;
            if (!roomId) {
                alert("Vui lòng chọn 1 phòng trống để xếp cho khách!");
                return;
            }
            const res = await API.post(`admin/bookings/checkin/${currentBookingId}`, { roomId });
            alert(res.message);
            if (res.success) {
                popupCheckIn.classList.remove("show");
                await loadItem("admin/bookings", "booking-list", renderBooking, getQueryString());
            }
        };
    }
}