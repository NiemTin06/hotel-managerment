import {checkboxMulti} from '../common/checkbox.js';
import { syncForm } from '../common/url.js';
import { initDeleteRoom } from './delete-room.js';
import { initDetailRoom } from './detail-room.js';
import { handleFormRoom } from './handle-form-room.js'
// import { initPopUp } from '../common/popup.js';

// import { checkboxMulti } from "../common/checkbox";

export function renderRoom(rooms, container, roomstype) {
    const roomListContainer = document.querySelector(`#${container}`);
    if (!roomListContainer) return;

    const html = (Array.isArray(rooms) ? rooms : []).map((room, index) => {
        // 1. Xử lý status
        const statusMap = {
            Available: `<span class="badge bg-success">Còn trống</span>`,
            Booked: `<span class="badge bg-warning text-dark">Đã đặt</span>`,
            Occupied: `<span class="badge bg-primary">Đang sử dụng</span>`,
            Maintenance: `<span class="badge bg-danger">Bảo trì</span>`,
        };

        const statusHtml = statusMap[room.ROOM_STATUS] ??
        `<span class="badge bg-secondary">Không xác định</span>`;
                return `
                <tr>
                    <td>
                        <input type="checkbox" name="id" value="${room.ROOM_ID}">
                    </td>
                    <th scope="row" class="align-middle">${index + 1}</th>
                    
                    <td class="align-middle"><strong>${room.ROOM_NUMBER}</strong></td>
                    <td class="align-middle">${room.ROOM_DESCRIPTION ?? "Không có mô tả"}</td>
                    <td class= "align-middle"> ${statusHtml}</td> 
                    <td class="align-middle">
                        <button class="btn btn-sm btn-warning  btn-update-popup" data-id="${room.ROOM_ID}" update-room>Sửa</button>
                        <button class="btn btn-sm btn-danger" data-id="${room.ROOM_ID}" delete-room>Xóa</button>

                        <button class="btn btn-sm btn-secondary" data-id="${room.ROOM_ID}" detail-room>Chi tiết</button>
                    </td>
                </tr>
            `;
    }).join("");

    roomListContainer.innerHTML = html;

    const roomTypeOptionCreate = document.querySelector(".popup-container .form-select")
    const roomTypeFilter = document.querySelector("#room-type");
    const options = (Array.isArray(roomstype) ? roomstype : []).map((roomtype, index) => {
                return `
                <option value=${roomtype.ROOMTYPE_ID}> ${index + 1}. ${roomtype.ROOMTYPE_NAME} - SL${roomtype.ROOMTYPE_MAX_GUESTS} - Loại giường: ${roomtype.ROOMTYPE_BED_TYPE}</option>
            `;
    }).join("");

    const optionFilter = (Array.isArray(roomstype) ? roomstype : []).map((roomtype, index) => {
                return `
                <option value=" ${roomtype.ROOMTYPE_ID}"> ${roomtype.ROOMTYPE_NAME} </option>
            `;
    }).join("");

    roomTypeOptionCreate.innerHTML = options;
    roomTypeFilter.innerHTML = `<option value="" selected> Mặc định</option>`
    roomTypeFilter.innerHTML += optionFilter;

    // Reset lại nút check-all tổng về false sau khi re-render danh sách mới
    const checkBoxMulti = document.querySelector("[checkbox-multi]");
    if (checkBoxMulti) {
        checkBoxMulti.checked = false;
    }
    const form = document.querySelector("[filter-form]");
    syncForm(form);
    checkboxMulti();
    handleFormRoom("phòng");
    initDetailRoom(); 
    initDeleteRoom(renderRoom);
}