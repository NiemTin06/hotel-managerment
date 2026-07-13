import {checkboxMulti} from '../common/checkbox.js';
import { initDeleteRoomType } from './delete-room-type.js';
import { initDetailRoomType } from './detail-room-type.js';
import { handleFormRoomType } from './handle-form-room-type.js';

// import { initPopUp } from '../common/popup.js';

// import { checkboxMulti } from "../common/checkbox";

export function renderRoomType(roomstype, container) {
    const roomListContainer = document.querySelector(`#${container}`);
    if (!roomListContainer) return;

  const html = (Array.isArray(roomstype) ? roomstype : []).map((roomtype, index) => {
            // 1. Lấy đúng các trường từ bảng RoomType
            const price = Number(roomtype.ROOMTYPE_PRICE_PER_NIGHT ?? 0);
            const discount = Number(roomtype.ROOMTYPE_DISCOUNT_PERCENTAGE ?? 0);
            const finalPrice = price - (price * discount / 100);

            // 2. Xử lý hiển thị ảnh đại diện (ROOMTYPE_THUMBNAIL)
            const thumbnailHtml = 
                roomtype.ROOMTYPE_THUMBNAIL 
                ? `<img src="/hotel-manager/public/uploads/roomtypes/${roomtype.ROOMTYPE_THUMBNAIL}" alt="${roomtype.ROOMTYPE_NAME}">`
                : 
                `<span class="text-muted">Không có ảnh</span>`;
            // 3. Xử lý status
            const statusHtml = roomtype.ROOMTYPE_STATUS === "Active"
                ? `<span class="badge bg-success">Hoạt động</span>`
                : `<span class="badge bg-danger">Ngừng hoạt động</span>`;
            return `
                <tr>
                    <td>
                        <input type="checkbox" name="id" value="${roomtype.ROOMTYPE_ID}">
                    </td>
                    <th scope="row" class="align-middle">${index + 1}</th>
                    
                    <td class="align-middle"><strong>${roomtype.ROOMTYPE_NAME}</strong></td>
                    <td class="align-middle text-center thumbnail-img">${thumbnailHtml}</td>
                    
                    <td class="align-middle text-success fw-bold">${Number(finalPrice).toLocaleString("vi-VN")} VNĐ</td>
                    
                    <td class="align-middle text-secondary">${discount}%</td>
                    <td class="align-middle">${roomtype.ROOMTYPE_DESCRIPTION ?? "Không có mô tả"}</td>
                    <td class= "align-middle"> ${statusHtml}</td> 
                    
                    
                    <td class="align-middle">
                        <button class="btn btn-sm btn-warning  btn-update-popup" data-id="${roomtype.ROOMTYPE_ID}" update-room-type>Sửa</button>

                        <button class="btn btn-sm btn-danger" data-id="${roomtype.ROOMTYPE_ID}" delete-room-type>Xóa</button>

                        <button class="btn btn-sm btn-secondary" data-id="${roomtype.ROOMTYPE_ID}" detail-room-type>Chi tiết</button>
                    </td>
                </tr>
            `;
    }).join("");

    roomListContainer.innerHTML = html;

    // Reset lại nút check-all tổng về false sau khi re-render danh sách mới
    const checkBoxMulti = document.querySelector("[checkbox-multi]");
    if (checkBoxMulti) {
        checkBoxMulti.checked = false;
    }
    checkboxMulti();
    handleFormRoomType();
    initDetailRoomType(); 
    initDeleteRoomType();
}