import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";
import { getQueryString } from "../common/url.js";

export function initDeleteCustomer(callback) {
    const buttons = document.querySelectorAll("[delete-customer]");
    buttons.forEach(button => {
        button.addEventListener("click", async () => {
            const id = button.dataset.id;
            const confirmDelete = confirm("Bạn có chắc muốn xóa khách hàng này?");
            if (!confirmDelete) return;
            
            const data = await API.delete(`admin/customers/delete`, { ids: [id] }); 
            alert(data.message);
            if (data.success) {
                const param = getQueryString();
                await loadItem(
                    "admin/customers",
                    "customer-list",
                    callback,
                    param
                );
            }
        });
    });
}