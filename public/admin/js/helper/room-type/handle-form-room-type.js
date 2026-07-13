import { bindUpdateButton } from "./update-room-type.js";
import { loadItem } from "../common/load-item.js";
import { initDetailRoomType } from "./detail-room-type.js";
import { API } from "../../api/api.js";

export function handleFormRoomType () {
    // pop up create item 
    const form = document.querySelector("[popup-form]");
    if (!form) return;
    const popup = document.getElementById("popup");

    const popupContainer = document.querySelector(".popup-container")
    const btnCreatePopup = document.querySelector("#btnCreatePopup");
    const btnClosePopup = document.querySelector("#btnClosePopup")
    const btnUpdatePopup = document.querySelectorAll("[update-room-type]");
    
    const previewInput = document.querySelector('#roomtype-thumbnail')
    const previewImg = document.querySelector("#preview-image");
    const previewBox = document.querySelector(".thumbnail-preview"); 
    const removeBtn = document.getElementById("btn-remove-image");
    
    // Preview image 
    previewInput.addEventListener("change", () => {
        const file = previewInput.files[0];
        if (!file) {
            previewImg.src = "";
            previewBox.classList.remove("show"); // không chọn file -> ẩn lại
            return;
        }
        previewImg.src = URL.createObjectURL(file);
        previewBox.classList.add("show");
    })

    // Remove image
    removeBtn.addEventListener("click", () => {
        previewInput.value = "";
        previewImg.src = "";
        previewBox.classList.remove("show");
    });
    
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
            previewImg.src = "/images/no-image.png";
            previewBox.classList.remove("show");
            popupContainer.classList.add("show");
            const button = document.querySelector(".btn-submit")
            const title = document.querySelector(".popup-title")
            
            if (title) {
                title.textContent = "Tạo loại phòng mới";
            }

            if (button) {
                button.textContent = "Thêm loại phòng";
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
                console.log("ok")
                console.log(button);
                console.log(title);
                title.innerHTML = "Cập nhật loại phòng"
                button.innerHTML = "Cập nhật"
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
            const data = id
                  ? await API.post(`admin/rooms-type/update/${id}`, formData)
                 : await API.post(`admin/rooms-type/create`, formData);
            if (!data.success) {
                alert(data.message);
                return;
            } 
            alert(data.message);
            form.reset();
            delete form.dataset.id;
            previewImg.src = "/images/no-image.png";
            previewBox.classList.remove("show");
            popupContainer.classList.remove("show");
            await loadItem("admin/rooms-type", "room-type-list");


        });
    }
}
