import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";

export function initDeleteUser(callback) {
    const buttons = document.querySelectorAll("[delete-user]");
    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const confirmDelete = confirm("Bạn có chắc muốn xóa tài khoản này?");
            if (!confirmDelete) return;
            
            const data = await API.delete(`admin/users/delete`, { ids: [id] }); 
            alert(data.message);
            if (data.success) {
                await loadItem(
                    "admin/users",
                    "user-list",
                    callback
                );
            }
        });
    });
}