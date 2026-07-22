import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";
import { renderBooking } from "./render-booking.js";
import { getQueryString } from "../common/url.js";
import { bindUpdateBookingButton } from "./update-booking.js";

// =========================================================================
// HÀM CHUYÊN DỤNG: Chủ động tải Khách hàng & Loại phòng vào thẻ Select
// =========================================================================
async function loadSelectOptions() {
    const selectCustomer = document.querySelector("#select-customer");
    const selectRoomType = document.querySelector("#select-roomtype");
    
    try {
        // Gọi API lấy dữ liệu JSON (đã thấy thành công trong tab Network của bạn)
        const res = await API.get("admin/bookings/data?limit=100");
        
        // 1. Vẽ danh sách Khách hàng vào thẻ Select
        if (selectCustomer && res && Array.isArray(res["record-customers"])) {
            selectCustomer.innerHTML = `<option value="">-- Chọn Khách hàng --</option>` + 
                res["record-customers"].map(c => `
                    <option value="${c.CUSTOMER_ID}">
                        ${c.CUSTOMER_FULLNAME} (${c.CUSTOMER_PHONE})
                    </option>
                `).join("");
        }

        // 2. Vẽ danh sách Loại phòng vào thẻ Select
        if (selectRoomType && res && Array.isArray(res["record-rooms-type"])) {
            selectRoomType.innerHTML = `<option value="">-- Chọn Loại phòng --</option>` + 
                res["record-rooms-type"].map(rt => `
                    <option value="${rt.ROOMTYPE_ID}" data-price="${rt.ROOMTYPE_PRICE_PER_NIGHT}">
                        ${rt.ROOMTYPE_NAME} - ${new Intl.NumberFormat('vi-VN').format(rt.ROOMTYPE_PRICE_PER_NIGHT)}đ/đêm
                    </option>
                `).join("");
        }
    } catch (error) {
        console.error("Lỗi khi tải danh sách ô Select:", error);
        if (selectCustomer) selectCustomer.innerHTML = `<option value="">⚠️ Lỗi tải danh sách</option>`;
    }
}

export function handleFormBooking() {
    const form = document.querySelector("[popup-form]");
    if (!form) return;
    const popupContainer = document.querySelector("#popup");
    const btnCreate = document.querySelector("#btnCreatePopup");
    const btnClose = document.querySelector("#btnClosePopup");
    const btnsUpdate = document.querySelectorAll("[update-booking]");

    // Xử lý đóng Popup
    if (btnClose && popupContainer) {
        btnClose.onclick = () => popupContainer.classList.remove("show");
        popupContainer.onclick = (e) => { if (e.target === popupContainer) popupContainer.classList.remove("show"); };
    }

    // 1. KHI BẤM NÚT TẠO MỚI -> Gọi loadSelectOptions() để nạp khách hàng vào thẻ Select
    if (btnCreate) {
        btnCreate.onclick = async () => {
            form.reset();
            delete form.dataset.id;
            document.querySelector(".popup-title").textContent = "Tạo đơn đặt phòng mới";
            document.querySelector(".btn-submit").textContent = "Lưu đơn";
            
            await loadSelectOptions(); // <-- Nạp danh sách khách hàng từ API vào đây
            
            popupContainer.classList.add("show");
        };
    }

    // 2. KHI BẤM NÚT SỬA -> Nạp danh sách xong mới chọn đúng khách hàng cũ
    btnsUpdate.forEach(btn => {
        btn.onclick = async () => {
            await loadSelectOptions(); // <-- Nạp danh sách trước
            await bindUpdateBookingButton(btn, form); // <-- Chọn khách hàng cũ sau
            
            const id = btn.dataset.id;
            document.querySelector(".popup-title").textContent = `Cập nhật đơn đặt phòng #${id}`;
            document.querySelector(".btn-submit").textContent = "Cập nhật";
            popupContainer.classList.add("show");
        };
    });

    // Tự động tính tiền khi Lễ tân chọn Ngày ở hoặc đổi Loại phòng
    const selectType = form.querySelector("#select-roomtype");
    const inputIn = form.querySelector("#input-checkin");
    const inputOut = form.querySelector("#input-checkout");
    const inputPrice = form.querySelector("#input-price");
    const hint = form.querySelector("#price-hint");

    const calculatePrice = () => {
        if (!selectType || !selectType.value || !inputIn.value || !inputOut.value) return;
        const d1 = new Date(inputIn.value);
        const d2 = new Date(inputOut.value);
        const nights = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
            const option = selectType.options[selectType.selectedIndex];
            const pricePerNight = parseFloat(option.dataset.price || 0);
            const total = nights * pricePerNight;
            if (inputPrice) inputPrice.value = total;
            if (hint) hint.textContent = `Tạm tính: ${nights} đêm x ${new Intl.NumberFormat('vi-VN').format(pricePerNight)}đ`;
        } else {
            if (inputPrice) inputPrice.value = 0;
            if (hint) hint.textContent = "⚠️ Ngày Check-out phải sau Check-in";
        }
    };

    if (selectType) selectType.onchange = calculatePrice;
    if (inputIn) inputIn.onchange = calculatePrice;
    if (inputOut) inputOut.onchange = calculatePrice;

    // Xử lý gửi Form Tạo mới / Cập nhật
    if (!form.dataset.bound) {
        form.dataset.bound = "true";
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = form.dataset.id;
            
            const url = id ? `admin/bookings/update/${id}` : `admin/bookings/create`;
            const res = await API.post(url, formData);
            
            alert(res.message);
            if (res.success) {
                form.reset();
                delete form.dataset.id;
                popupContainer.classList.remove("show");
                await loadItem("admin/bookings", "booking-list", renderBooking, getQueryString());
            }
        });
    }
}