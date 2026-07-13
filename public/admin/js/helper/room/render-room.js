import {checkboxMulti} from '../common/checkbox.js';
import { initDeleteRoomType } from '../room-type/delete-room-type.js';

export function renderRooms(rooms) {
    const roomListContainer = document.querySelector("#room-list");
    if (!roomListContainer) return;
    const html = (Array.isArray(rooms) ? rooms : []).map((room, index) => {
          const price = Number(room.ROOM_PRICE_PER_NIGHT)
            const discount = Number(room.ROOM_DISCOUNT_PERCENTAGE);
            const finalPrice = price - (price * discount / 100);
            return `
                <tr>
                    <th>
                        <input type="checkbox" name = 'id'value="${room.ROOM_ID}">
                    </th>
                    <th scope="row">${index + 1}</th>
                    <td>${room.ROOM_NUMBER}</td>
                    <td>${room.ROOM_ROOMTYPE_ID}</td>
                    <td>${Number(finalPrice).toLocaleString("vi-VN")} VNĐ</td>
                    <td>${room.ROOM_DISCOUNT_PERCENTAGE}%</td>
                    <td>${
                        {
                            Available: '<span class="badge bg-success">Còn trống</span>',
                            Booked: '<span class="badge bg-warning text-dark">Đã đặt</span>',
                            Occupied: '<span class="badge bg-danger">Đang sử dụng</span>',
                            Maintenance: '<span class="badge bg-secondary">Bảo trì</span>',
                            Deleted: '<span class="badge bg-dark">Ngừng hoạt động</span>'
                        }[room.ROOM_STATUS] ?? room.ROOM_STATUS
                    }</td>
                    <td>${room.ROOM_DESCRIPTION ?? "Không có mô tả"}</td>
                    <td>
                        <span class="text-muted">Không có ảnh</span>
                    </td>
                    <td>
                        <button class="btn btn-sm 
                        btn-warning">Sửa</button>
                        <button class="btn btn-sm btn-danger">Xóa</button>
                        <button class="btn btn-sm btn-secondary">chi tiết</button>
                    </td>
                </tr>
            `
    }).join("");

    roomListContainer.innerHTML = html;

    const checkBoxMulti = document.querySelector("[checkbox-multi]");
    if (checkBoxMulti) {
        checkBoxMulti.checked = false;
    }

    checkboxMulti();

}