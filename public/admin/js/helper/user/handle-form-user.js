import { bindUpdateButton } from "./update-user.js";
import { loadItem } from "../common/load-item.js";
import { API } from "../../api/api.js";
import { renderUser } from "./render-user.js";
import { getQueryString } from "../common/url.js";

export function handleFormUser(label) {
    const form = document.querySelector("[popup-form]");
    if (!form) return;
    const popup = document.getElementById("popup");

    const popupContainer = document.querySelector(".popup-container");
    const btnCreatePopup = document.querySelector("#btnCreatePopup");
    const btnClosePopup = document.querySelector("#btnClosePopup");
    const btnUpdatePopup = document.querySelectorAll("[update-user]");
    const divPassword = document.querySelector("#div-password-input");
    
    if (btnClosePopup && popup) {
        btnClosePopup.addEventListener("click", () => {
            popupContainer.classList.remove("show");
        });
        popup.addEventListener("click", (e) => {
            if (e.target === popup) {
                popupContainer.classList.remove("show");
            }
        });
    }
   
    if (btnCreatePopup) {
        btnCreatePopup.addEventListener("click", () => {
            form.reset();
            delete form.dataset.id;     
            
            // Hiện lại ô mật khẩu khi tạo mới
            if (divPassword) {
                divPassword.style.display = "block";
            }

            popupContainer.classList.add("show");
            const button = document.querySelector(".btn-submit");
            const title = document.querySelector(".popup-title");
            if (title) title.textContent = `Tạo ${label} mới`;
            if (button) button.textContent = `Thêm ${label}`;
        });
    }

    if (btnUpdatePopup) {
        btnUpdatePopup.forEach(btnUpdate => {
            btnUpdate.addEventListener("click", async () => {
                await bindUpdateButton(btnUpdate, form);
                popupContainer.classList.add("show");
                const button = document.querySelector(".btn-submit");
                const title = document.querySelector(".popup-title");
                if (title) title.textContent = `Cập nhật ${label}`;
                if (button) button.textContent = `Cập nhật`;
            }); 
        });
    }
    
    if (!form.dataset.bound) {
        form.dataset.bound = "true";
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = form.dataset.id;    
            const data = id
                ? await API.post(`admin/users/update/${id}`, formData)
                : await API.post(`admin/users/create`, formData);
            if (!data.success) {
                alert(data.message);
                return;
            }
            alert(data.message);
            form.reset();
            delete form.dataset.id;
            popupContainer.classList.remove("show");
            const param = getQueryString();
            await loadItem("admin/users", "user-list", renderUser, param);
        });
    }
}