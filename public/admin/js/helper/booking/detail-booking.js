import { API } from "../../api/api.js";

export function initDetailBooking() {
    const buttons = document.querySelectorAll("[detail-booking]");
    const popup = document.querySelector("#popup-detail");
    const btnClose = document.querySelector("#btn-close-detail");
    
    // Xử lý đóng Popup (Tối ưu lại code: chỉ cần dùng 1 biến popup là đủ)
    if (btnClose && popup) {
        btnClose.addEventListener("click", () => {
            popup.classList.remove("show");
        });
        popup.addEventListener("click", (e) => {
            if (e.target === popup) {
                popup.classList.remove("show");
            }
        });
    }

    // Từ điển dịch trạng thái sang tiếng Việt cho thân thiện
    const statusMap = {
        Pending: "Chờ xác nhận",
        Confirmed: "Đã xác nhận / Cọc",
        CheckedIn: "Đang ở (Checked-in)",
        CheckedOut: "Đã trả phòng",
        Cancelled: "Đã hủy"
    };

    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const res = await API.get(`admin/bookings/${id}`);
            
            if (!res) {
                alert("Không tìm thấy dữ liệu đơn đặt phòng.");
                return;
            }

            // Đổ dữ liệu từ API vào các thẻ HTML tương ứng trong detail.php
            document.querySelector("#dt-id").textContent = `#${res.BOOKING_ID}`;
            document.querySelector("#dt-customer").textContent = res.CUSTOMER_FULLNAME || "Khách vãng lai";
            document.querySelector("#dt-phone").textContent = res.CUSTOMER_PHONE || "---";
            document.querySelector("#dt-roomtype").textContent = res.ROOMTYPE_NAME || "Không xác định";
            
            // Xử lý logic: Nếu đã gán phòng thì hiện số phòng, chưa có thì báo chưa xếp
            document.querySelector("#dt-room").textContent = res.ROOM_NUMBER ? `Phòng ${res.ROOM_NUMBER}` : "Chưa xếp phòng";
            
            document.querySelector("#dt-checkin").textContent = res.BOOKING_CHECKIN || "";
            document.querySelector("#dt-checkout").textContent = res.BOOKING_CHECKOUT || "";
            
            // Hiển thị trạng thái tiếng Việt
            document.querySelector("#dt-status").textContent = statusMap[res.BOOKING_STATUS] || res.BOOKING_STATUS;
            
            // Định dạng tổng tiền sang chuẩn tiền tệ VNĐ (ví dụ: 1.500.000 ₫)
            const formattedPrice = new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND' 
            }).format(res.BOOKING_TOTAL_PRICE || 0);
            document.querySelector("#dt-price").textContent = formattedPrice;
            
            document.querySelector("#dt-note").textContent = res.BOOKING_NOTE || "Không có yêu cầu đặc biệt.";

            // Mở popup
            popup.classList.add("show");
        });
    });
}