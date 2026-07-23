import { API } from "../../api/api.js";

export function initResetPassUser() {
    const buttons = document.querySelectorAll("[reset-pass]");
    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const confirmReset = confirm("⚠️ Bạn có chắc muốn đặt lại mật khẩu của tài khoản này về mặc định (123456)?");
            if (!confirmReset) return;
            
            const data = await API.post(`admin/users/reset-pass/${id}`);
            alert(data.message || "Đã reset mật khẩu thành công!");
        });
    });
}