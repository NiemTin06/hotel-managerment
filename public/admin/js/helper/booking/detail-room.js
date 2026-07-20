import { API } from "../../api/api.js";

export function initDetailRoom() {
    const buttons = document.querySelectorAll("[detail-room]");
    const popup = document.querySelector("#popup-detail");
    const btnClose = document.querySelector("#btn-close-detail");
     const popupContainer = document.querySelector("#popup-detail")
    if (btnClose && popup){
        btnClose.addEventListener("click", () => {
            popupContainer.classList.remove("show");
        });
        popup.addEventListener("click", (e)=>{
            if (e.target === popup) {
                popupContainer.classList.remove("show");
            }
        });
    }
    buttons.forEach(button => {
        button.addEventListener("click", async ()=>{
            const id = button.dataset.id;
            const room = await API.get(`admin/rooms/${id}`);
            if(!room){
                alert("Không tìm thấy dữ liệu.");
                return;
            }
           document.querySelector("#detail-room-number").textContent =
                room.ROOM_NUMBER;
            document.querySelector("#detail-room-status").textContent =
                room.ROOM_STATUS;
            document.querySelector("#detail-room-description").textContent =
                room.ROOM_DESCRIPTION || "Không có mô tả.";
            document.querySelector("#detail-roomtype-name").textContent =
                room.ROOMTYPE_NAME || "Không xác định";
            popup.classList.add("show");


        });

    });


}