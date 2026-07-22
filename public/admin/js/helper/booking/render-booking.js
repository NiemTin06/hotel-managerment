import { checkboxMulti } from '../common/checkbox.js';
import { syncForm } from '../common/url.js';
import { initDeleteBooking } from './delete-booking.js';
import { initDetailBooking } from './detail-booking.js';
import { handleFormBooking } from './handle-form-booking.js';

export function renderBooking(bookings, container) {
    const listContainer = document.querySelector(`#${container}`) || document.querySelector(`.${container}`);
    if (!listContainer) return;

    // 1. Nếu không có dữ liệu, hiển thị thông báo trống thân thiện
    if (!Array.isArray(bookings) || bookings.length === 0) {
        listContainer.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    📭 Chưa có đơn đặt phòng nào trong hệ thống.
                </td>
            </tr>
        `;
        return;
    }

    // 2. Từ điển màu sắc và nhãn cho trạng thái đơn (Dịch tiếng Việt)
    const statusMap = {
        Pending: `<span class="badge bg-warning text-dark">Chờ xác nhận</span>`,
        Confirmed: `<span class="badge bg-info text-white">Đã xác nhận / Cọc</span>`,
        CheckedIn: `<span class="badge bg-primary">Đang ở (Checked-in)</span>`,
        CheckedOut: `<span class="badge bg-success">Đã trả phòng</span>`,
        Cancelled: `<span class="badge bg-danger">Đã hủy</span>`
    };

    // 3. Render danh sách các dòng trong bảng
    const html = bookings.map((item) => {
        // Định dạng tiền sang chuẩn VNĐ (VD: 1.500.000 ₫)
        const money = new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND' 
        }).format(item.BOOKING_TOTAL_PRICE || 0);

        // Badge hiển thị phòng vật lý (Nếu đã xếp phòng thì màu xanh, chưa xếp thì màu xám)
        const roomBadge = item.ROOM_NUMBER 
            ? `<span class="badge bg-success fs-6 mt-1">P.${item.ROOM_NUMBER}</span>` 
            : `<span class="badge bg-secondary mt-1">Chưa xếp phòng</span>`;

        // Nút thao tác nhanh của Lễ tân tùy theo trạng thái đơn
        let quickActionBtn = "";
        if (item.BOOKING_STATUS === 'Confirmed' || item.BOOKING_STATUS === 'Pending') {
            quickActionBtn = `<button type="button" class="btn btn-sm btn-success fw-bold" data-id="${item.BOOKING_ID}" data-type="${item.BOOKING_ROOMTYPE_ID}" data-name="${item.CUSTOMER_FULLNAME || 'Khách'}" data-typename="${item.ROOMTYPE_NAME}" btn-open-checkin title="Xếp phòng vào ở">🔑 Check-in</button>`;
        } else if (item.BOOKING_STATUS === 'CheckedIn') {
            quickActionBtn = `<button type="button" class="btn btn-sm btn-dark fw-bold" data-id="${item.BOOKING_ID}" data-room="${item.BOOKING_ROOM_ID}" btn-checkout title="Thanh toán trả phòng">🏁 Check-out</button>`;
        }

        return `
            <tr>
                <td class="align-middle"><input type="checkbox" name="id" value="${item.BOOKING_ID}"></td>
                <td class="align-middle fw-bold">#${item.BOOKING_ID}</td>
                <td class="align-middle">
                    <b class="text-dark">${item.CUSTOMER_FULLNAME || 'Khách vãng lai'}</b><br>
                    <small class="text-primary fw-semibold">${item.CUSTOMER_PHONE || '---'}</small>
                </td>
                <td class="align-middle">
                    <span class="fw-bold text-dark">${item.ROOMTYPE_NAME || '---'}</span><br>
                    ${roomBadge}
                </td>
                <td class="align-middle">
                    <small class="text-muted">IN:</small> <b class="text-success">${item.BOOKING_CHECKIN}</b><br>
                    <small class="text-muted">OUT:</small> <b class="text-danger">${item.BOOKING_CHECKOUT}</b>
                </td>
                <td class="align-middle text-danger fw-bold fs-6">${money}</td>
                <td class="align-middle">${statusMap[item.BOOKING_STATUS] || item.BOOKING_STATUS}</td>
                <td class="align-middle">
                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                        ${quickActionBtn}
                        <button type="button" class="btn btn-sm btn-info text-white" data-id="${item.BOOKING_ID}" detail-booking title="Xem chi tiết">Xem</button>
                        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold" data-id="${item.BOOKING_ID}" update-booking title="Chỉnh sửa đơn">Sửa</button>
                        <button type="button" class="btn btn-sm btn-danger" data-id="${item.BOOKING_ID}" delete-booking title="Xóa đơn">Xóa</button>
                    </div>
                </td>
            </tr>
        `;
    }).join("");

    listContainer.innerHTML = html;

    // =========================================================================
    // 4. KÍCH HOẠT LẠI CÁC SỰ KIỆN SAU KHI VẼ LẠI BẢNG (RE-BIND EVENTS)
    // =========================================================================
    const checkBoxAll = document.querySelector("[checkbox-multi]");
    if (checkBoxAll) checkBoxAll.checked = false;
    
    const filterForm = document.querySelector("[filter-form]");
    if (filterForm) syncForm(filterForm);
    
    checkboxMulti();
    initDetailBooking();
    initDeleteBooking();
    
    // Gọi lại handleFormBooking để nhận diện và gắn sự kiện cho các nút "Sửa" vừa mới tạo ra
    handleFormBooking();
}