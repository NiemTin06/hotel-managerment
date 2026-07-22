import { bindUpdateButton } from "./update-room-type.js";
import { loadItem } from "../common/load-item.js";
import { initDetailRoomType } from "./detail-room-type.js";
import { API } from "../../api/api.js";
import { renderRoomType } from "./render-room-type.js";
import { getQueryString } from "../common/url.js";

export function handleFormRoomType() {
    const form = document.querySelector("[popup-form]");
    if (!form) return;

    const popup = document.getElementById("popup");
    const popupContainer = document.querySelector(".popup-container");
    const btnCreatePopup = document.querySelector("#btnCreatePopup");
    const btnClosePopup = document.querySelector("#btnClosePopup");
    
    const previewInput = document.querySelector('#roomtype-thumbnail');
    const previewImg = document.querySelector("#preview-image");
    const previewBox = document.querySelector(".thumbnail-preview"); 
    const removeBtn = document.getElementById("btn-remove-image");

    // =========================================================================
    // 1. NHÓM SỰ KIỆN CỐ ĐỊNH (Chỉ gắn 1 lần duy nhất trong đời trang web)
    // =========================================================================
    if (!form.dataset.staticBound) {
        form.dataset.staticBound = "true";

        // --- Xử lý Preview ảnh ---
        if (previewInput && previewImg && previewBox) {
            previewInput.addEventListener("change", () => {
                const file = previewInput.files[0];
                if (!file) {
                    previewImg.src = "";
                    previewBox.classList.remove("show");
                } else {
                    previewImg.src = URL.createObjectURL(file);
                    previewBox.classList.add("show");
                }
            });
        }

        // --- Xử lý xóa ảnh preview ---
        if (removeBtn && previewInput && previewImg && previewBox) {
            removeBtn.addEventListener("click", () => {
                previewInput.value = "";
                previewImg.src = "";
                previewBox.classList.remove("show");
            });
        }

        // --- Xử lý đóng Popup ---
        if (btnClosePopup && popupContainer) {
            btnClosePopup.addEventListener("click", () => {
                popupContainer.classList.remove("show");
            });
        }
        if (popup && popupContainer) {
            popup.addEventListener("click", (e) => {
                if (e.target === popup) {
                    popupContainer.classList.remove("show");
                }
            });
        }

        // --- Xử lý mở Popup Tạo mới ---
        if (btnCreatePopup && popupContainer) {
            btnCreatePopup.addEventListener("click", () => {
                form.reset();
                delete form.dataset.id;      
                
                if (previewImg) previewImg.src = "/images/no-image.png";
                if (previewBox) previewBox.classList.remove("show");
                
                const button = document.querySelector(".btn-submit");
                const title = document.querySelector(".popup-title");
                if (title) title.textContent = "Tạo loại phòng mới";
                if (button) button.textContent = "Thêm loại phòng";

                popupContainer.classList.add("show");
            });
        }

        // --- Xử lý Submit Form (Create / Update) ---
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = form.dataset.id;    
            
            const url = id ? `admin/rooms-type/update/${id}` : `admin/rooms-type/create`;
            const data = await API.post(url, formData);
            
            alert(data.message || (data.success ? "Thao tác thành công!" : "Có lỗi xảy ra."));
            if (!data.success) return; 
            
            form.reset();
            delete form.dataset.id;
            if (previewImg) previewImg.src = "/images/no-image.png";
            if (previewBox) previewBox.classList.remove("show");
            if (popupContainer) popupContainer.classList.remove("show");
            
            const param = getQueryString();
            await loadItem("admin/rooms-type", "room-type-list", renderRoomType, param);
        });
    }

    // =========================================================================
    // 2. NHÓM SỰ KIỆN ĐỘNG (Phải chạy lại mỗi khi bảng vẽ lại nút Sửa mới)
    // =========================================================================
    const btnUpdatePopup = document.querySelectorAll("[update-room-type]");
    if (btnUpdatePopup.length > 0 && popupContainer) {
        btnUpdatePopup.forEach(btnUpdate => {
            btnUpdate.addEventListener("click", async () => {
                await bindUpdateButton(btnUpdate, form);
                
                const button = document.querySelector(".btn-submit");
                const title = document.querySelector(".popup-title");
                if (title) title.textContent = "Cập nhật loại phòng";
                if (button) button.textContent = "Cập nhật";

                popupContainer.classList.add("show");
            });
        });
    }
}