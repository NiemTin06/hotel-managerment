import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";

export function initDeleteRoomType() {
    const buttons = document.querySelectorAll("[delete-room-type]");
    buttons.forEach(button => {
        button.addEventListener("click", async ()=>{
            const id = button.dataset.id;
            const confirmDelete = confirm("Bạn có chắc muốn xóa loại loại phòng này?");
            if (!confirmDelete) return;
            const data = await API.delete(`admin/rooms-type/delete`, { ids: [id]}); 
            await loadItem(
                "admin/rooms-type",
                "room-type-list"
            );
        })
    });
}