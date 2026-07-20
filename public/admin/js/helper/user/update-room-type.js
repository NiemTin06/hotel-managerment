import { API } from "../../api/api.js";


export async function bindUpdateButton(buttonUpdate, form) {
    console.log(form);
    console.log(buttonUpdate);
    form.reset();
    const roomTypeId = buttonUpdate.dataset.id;
    const roomType = await API.get(`admin/rooms-type/${roomTypeId}`);
    form.dataset.id = roomTypeId;
    
    form.elements["roomtype-name"].value = roomType.ROOMTYPE_NAME;
    form.elements["roomtype-price"].value = roomType.ROOMTYPE_PRICE_PER_NIGHT;
    form.elements["roomtype-discount"].value = roomType.ROOMTYPE_DISCOUNT_PERCENTAGE;
    form.elements["roomtype-description"].value = roomType.ROOMTYPE_DESCRIPTION;
    form.elements["roomtype-max-guests"].value = roomType.ROOMTYPE_MAX_GUESTS;
    form.elements["roomtype-bed-type"].value = roomType.ROOMTYPE_BED_TYPE;

    const previewImg = document.getElementById("preview-image");
    const previewBox = document.querySelector(".thumbnail-preview"); 
    if (roomType.ROOMTYPE_THUMBNAIL) {
        previewBox.classList.add("show");
        previewImg.src = `${APP_URLROOT}/public/uploads/roomtypes/${roomType.ROOMTYPE_THUMBNAIL}`;
    } else {
        previewImg.src = "/images/no-image.png";
        previewBox.classList.remove("show");
    }
}