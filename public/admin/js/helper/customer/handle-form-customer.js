import { bindUpdateButton } from "./update-customer.js";
import { loadItem } from "../common/load-item.js";
import { API } from "../../api/api.js";
import { renderCustomer } from "./render-customer.js";
import { getQueryString } from "../common/url.js";

export function handleFormCustomer(label = "khách hàng") {
    const form = document.querySelector("[popup-form]");
    if (!form) return;
    const popupContainer = document.querySelector("#popup");
    const btnCreatePopup = document.querySelector("#btnCreatePopup");
    const btnClosePopup = document.querySelector("#btnClosePopup");
    const btnUpdatePopup = document.querySelectorAll("[update-customer]");
    
    // Close popup
    if (btnClosePopup && popupContainer) {
        btnClosePopup.addEventListener("click", () => {
            popupContainer.classList.remove("show");
        });
        popupContainer.addEventListener("click", (e) => {
            if (e.target === popupContainer) {
                popupContainer.classList.remove("show");
            }
        });
    }
   
    // Create popup
    if (btnCreatePopup) {
        btnCreatePopup.addEventListener("click", () => {
            form.reset();
            delete form.dataset.id;     
            popupContainer.classList.add("show");
            const button = document.querySelector(".btn-submit");
            const title = document.querySelector(".popup-title");
            if (title) {
                title.textContent = `Tạo ${label} mới`;
            }
            if (button) {
                button.textContent = `Thêm ${label}`;
            }
        });
    }

    // Update button
    if (btnUpdatePopup) {
        btnUpdatePopup.forEach(btnUpdate => {
            btnUpdate.addEventListener("click", async () => {
                await bindUpdateButton(btnUpdate, form);
                popupContainer.classList.add("show");
                const button = document.querySelector(".btn-submit");
                const title = document.querySelector(".popup-title");
                if (title) {
                    title.textContent = `Cập nhật ${label}`;
                }
                if (button) {
                    button.textContent = `Cập nhật`;
                }
            }); 
        });
    }
    
    // Submit (Dùng Dataset bound để chống gắn trùng Event)
    if (!form.dataset.bound) {
        form.dataset.bound = "true";
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = form.dataset.id;    
            const data = id
                  ? await API.post(`admin/customers/update/${id}`, formData)
                  : await API.post(`admin/customers/create`, formData);
            if (!data.success) {
                alert(data.message);
                return;
            }
            alert(data.message);
            form.reset();
            delete form.dataset.id;
            popupContainer.classList.remove("show");
            const param = getQueryString();
            await loadItem("admin/customers", "customer-list", renderCustomer, param);
        });
    }
}