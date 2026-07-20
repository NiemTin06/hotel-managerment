import { API } from "../../api/api.js";

export function initDetailRoomType() {
    const buttons = document.querySelectorAll("[detail-room-type]");
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
            const room = await API.get(`admin/rooms-type/${id}`);
            console.log(room)
            if(!room){
                alert("Không tìm thấy dữ liệu.");
                return;
            }

            document.querySelector("#detail-name").textContent =
                room.ROOMTYPE_NAME;

            document.querySelector("#detail-price").textContent =
                Number(room.ROOMTYPE_PRICE_PER_NIGHT)
                .toLocaleString("vi-VN")+" VNĐ";

            document.querySelector("#detail-discount").textContent =
                room.ROOMTYPE_DISCOUNT_PERCENTAGE+" %";

            document.querySelector("#detail-max-guests").textContent =
                room.ROOMTYPE_MAX_GUESTS;

            document.querySelector("#detail-bed-type").textContent =
                room.ROOMTYPE_BED_TYPE;

            document.querySelector("#detail-status").textContent =
                room.ROOMTYPE_STATUS;

            document.querySelector("#detail-description").textContent =
                room.ROOMTYPE_DESCRIPTION || "Không có mô tả.";

            const img=document.querySelector("#detail-thumbnail");

            if(room.ROOMTYPE_THUMBNAIL){

                img.src=`${APP_URLROOT}/public/uploads/roomtypes/${room.ROOMTYPE_THUMBNAIL}`;

            }else{

                img.src="/images/no-image.png";

            }

            popup.classList.add("show");

        });

    });


}