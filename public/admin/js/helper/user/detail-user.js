import { API } from "../../api/api.js";

export function initDetailUser() {
    const buttons = document.querySelectorAll("[detail-user]");
    const popup = document.querySelector("#popup-detail");
    const btnClose = document.querySelector("#btn-close-detail");
    const popupContainer = document.querySelector("#popup-detail");

    if (btnClose && popup) {
        btnClose.addEventListener("click", () => {
            popupContainer.classList.remove("show");
        });
        popup.addEventListener("click", (e) => {
            if (e.target === popup) {
                popupContainer.classList.remove("show");
            }
        });
    }

    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const user = await API.get(`admin/users/${id}`);
            if (!user) {
                alert("Không tìm thấy dữ liệu.");
                return;
            }
           
            document.querySelector("#detail-user-id").textContent = `#${user.USER_ID}`;
            document.querySelector("#detail-user-username").textContent = user.USER_USERNAME;
            document.querySelector("#detail-user-email").textContent = user.USER_EMAIL;
            document.querySelector("#detail-user-phone").textContent = user.USER_PHONE || "Chưa cập nhật";
            document.querySelector("#detail-user-role").textContent = user.USER_ROLE;
            document.querySelector("#detail-user-status").textContent = user.USER_STATUS;
            document.querySelector("#detail-user-created").textContent = user.USER_CREATED_AT || "---";
            
            popup.classList.add("show");
        });
    });
}