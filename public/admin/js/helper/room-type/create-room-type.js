import { API } from "../../api/api.js";
import { createItem } from "../common/create-item.js";
import { loadItem } from "../common/load-item.js";

// create-room-type.js
export function createRoomType() {
    const form = document.querySelector("[create-form]");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = await createItem("admin/rooms-type", formData);

        if (data?.success) {
            alert(data.message ?? "Thêm loại phòng thành công.");
            form.reset();
            await loadItem("admin/rooms-type", "room-type-list");
        } else {
            alert(data?.message ?? "Có lỗi xảy ra khi thêm loại phòng.");
        }
    });
}