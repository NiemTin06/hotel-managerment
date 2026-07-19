import { API } from "../../api/api.js";
import { loadItem } from "../common/load-item.js";

export function initDeleteRoom(callback) {
    const buttons = document.querySelectorAll("[delete-room]");
    buttons.forEach(button => {
        button.addEventListener("click", async ()=>{
            const id = button.dataset.id;
            const confirmDelete = confirm("Bạn có chắc muốn xóa phòng này?");
            if (!confirmDelete) return;
            console.log(id)
            const data = await API.delete(`admin/rooms/delete`, { ids: [id]}); 
            await loadItem(
                "admin/rooms",
                "room-list",
                callback
            );
        })
    });
}