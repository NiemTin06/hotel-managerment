import { API } from "../../api/api.js";


export async function bindUpdateButton(buttonUpdate, form) {
    console.log(form);
    console.log(buttonUpdate);
    form.reset();
    const roomNumber = buttonUpdate.dataset.id;
    const room = await API.get(`admin/rooms/${roomNumber}`);
    form.dataset.id = roomNumber;
    
    form.elements["room-number"].value = room.ROOM_NUMBER;
    form.elements["room-roomtype"].value = room.ROOM_ROOMTYPE_ID;
    form.elements["room-description"].value = room.ROOM_DESCRIPTION;

}