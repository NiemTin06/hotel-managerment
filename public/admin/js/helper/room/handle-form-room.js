import { bindUpdateButton } from "./update-room.js";
import { loadItem } from "../common/load-item.js";
import { initDetailRoom } from "./detail-room.js";
import { API } from "../../api/api.js";
import { renderRoom } from "./render-room.js";
import { getQueryString } from "../common/url.js";


export function handleFormRoom ( label ) {
    // pop up create item 
    const form = document.querySelector("[popup-form]");
    if (!form) return;
    const popup = document.getElementById("popup");

    const popupContainer = document.querySelector(".popup-container")
    const btnCreatePopup = document.querySelector("#btnCreatePopup");
    const btnClosePopup = document.querySelector("#btnClosePopup")
    const btnUpdatePopup = document.querySelectorAll("[update-room]");
    
    // Close popup
    if (btnClosePopup && popup){
        btnClosePopup.addEventListener("click", () => {
            popupContainer.classList.remove("show");
        });
        popup.addEventListener("click", (e)=>{
            if (e.target === popup) {
                popupContainer.classList.remove("show");
            }
        });
    }
   
    //Create popup
    if (btnCreatePopup){
        btnCreatePopup.addEventListener("click", () => {
            form.reset();
            delete form.dataset.id;     
            popupContainer.classList.add("show");
            const button = document.querySelector(".btn-submit")
            const title = document.querySelector(".popup-title")
            if (title) {
                title.textContent = `Tạo ${label} mới`;
            }

            if (button) {
                button.textContent = `Thêm ${label}`;
            }
        });
    }

    //Update button
    if (btnUpdatePopup){
        console.log("ok")
        btnUpdatePopup.forEach(btnUpdate => {
            btnUpdate.addEventListener("click", async () =>{
                await bindUpdateButton(btnUpdate, form);
                popupContainer.classList.add("show");
                const button = document.querySelector(".btn-submit")
                const title = document.querySelector(".popup-title")
                    if (title) {
                        title.textContent = `Cập nhật ${label}`;
                    }

                    if (button) {
                        button.textContent = `Cập nhật`;
                    }
            }) 
        })
    }
    
    // Submit
    if (!form.dataset.bound) {           // guard chống gắn trùng
        form.dataset.bound = "true";
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = form.dataset.id;    
            console.log(id);
            const data = id
                  ? await API.post(`admin/rooms/update/${id}`, formData)
                 : await API.post(`admin/rooms/create`, formData);
            if (!data.success) {
                alert(data.message);
                return;
            }
            alert(data.message);
            form.reset();
            delete form.dataset.id;
            popupContainer.classList.remove("show");
            const param = getQueryString();
            await loadItem("admin/rooms", "room-list", renderRoom, param);
        });
    }
}
