import {checkboxMulti} from '../common/checkbox.js';

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
            // 3. Khớp chính xác với cấu trúc $tableHeader gồm 8 cột của bạn
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
                    
                    
                    <td class="align-middle">
                        <button class="btn btn-sm btn-warning" data-id="${roomtype.ROOMTYPE_ID}">Sửa</button>
                        <button class="btn btn-sm btn-danger" data-id="${roomtype.ROOMTYPE_ID}">Xóa</button>
                        <button class="btn btn-sm btn-secondary" data-id="${roomtype.ROOMTYPE_ID}">Chi tiết</button>
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
}