import { API } from "../../api/api.js";

export function initDetailCustomer() {
    const buttons = document.querySelectorAll("[detail-customer]");
    const popup = document.querySelector("#popup-detail");
    const btnClose = document.querySelector("#btn-close-detail");
    
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

    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const res = await API.get(`admin/customers/${id}`);
            if (!res) {
                alert("Không tìm thấy dữ liệu.");
                return;
            }
            
            document.querySelector("#detail-fullname").textContent = res.CUSTOMER_FULLNAME || "";
            document.querySelector("#detail-phone").textContent = res.CUSTOMER_PHONE || "";
            document.querySelector("#detail-cccd").textContent = res.CUSTOMER_CCCD || "Chưa cập nhật";
            document.querySelector("#detail-email").textContent = res.CUSTOMER_EMAIL || "Chưa cập nhật";
            document.querySelector("#detail-bookings").textContent = res.SO_LAN_DAT || 0;
            document.querySelector("#detail-spent").textContent = new Intl.NumberFormat('vi-VN').format(res.TONG_CHI_TIEU || 0);
            
            popup.classList.add("show");
        });
    });
}