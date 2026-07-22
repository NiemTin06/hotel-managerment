import { checkboxMulti } from '../common/checkbox.js';
import { syncForm } from '../common/url.js';
import { initDeleteCustomer } from './delete-customer.js';
import { initDetailCustomer } from './detail-customer.js';
import { handleFormCustomer } from './handle-form-customer.js';

export function renderCustomer(customers, container) {
    const listContainer = document.querySelector(`#${container}`);
    if (!listContainer) return;

    const html = (Array.isArray(customers) ? customers : []).map((item, index) => {
        // Format tiền VNĐ
        const formattedMoney = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.TONG_CHI_TIEU || 0);
        
        // Tạo Badge phân loại khách hàng
        let badgeVIP = "";
        if (item.SO_LAN_DAT >= 3 || item.TONG_CHI_TIEU > 10000000) {
            badgeVIP = `<span class="badge bg-warning text-dark ms-1">VIP ⭐</span>`;
        } else if (item.SO_LAN_DAT > 1) {
            badgeVIP = `<span class="badge bg-info text-white ms-1">Khách quen</span>`;
        } else if (item.SO_LAN_DAT == 1) {
            badgeVIP = `<span class="badge bg-secondary ms-1">1 lần</span>`;
        } else {
            badgeVIP = `<span class="badge bg-light text-muted ms-1">Chưa ở</span>`;
        }

        return `
            <tr>
                <td>
                    <input type="checkbox" name="id" value="${item.CUSTOMER_ID}">
                </td>
                <th scope="row" class="align-middle">${index + 1}</th>
                
                <td class="align-middle"><strong>${item.CUSTOMER_FULLNAME}</strong> ${badgeVIP}</td>
                <td class="align-middle"><a href="tel:${item.CUSTOMER_PHONE}" class="text-decoration-none fw-bold">${item.CUSTOMER_PHONE}</a></td>
                <td class="align-middle">${item.CUSTOMER_CCCD || "<i class='text-muted'>Chưa cập nhật</i>"}</td> 
                <td class="align-middle">
                    <div><small class="text-muted">Đã đặt:</small> <b>${item.SO_LAN_DAT}</b> đơn</div>
                    <div><small class="text-muted">Chi tiêu:</small> <b class="text-danger">${formattedMoney}</b></div>
                </td>
                <td class="align-middle">
                    <button class="btn btn-sm btn-info text-white" data-id="${item.CUSTOMER_ID}" detail-customer>Chi tiết</button>
                    <button class="btn btn-sm btn-warning btn-update-popup" data-id="${item.CUSTOMER_ID}" update-customer>Sửa</button>
                    <button class="btn btn-sm btn-danger" data-id="${item.CUSTOMER_ID}" delete-customer>Xóa</button>
                </td>
            </tr>
        `;
    }).join("");

    listContainer.innerHTML = html;

    // Reset lại nút check-all tổng về false sau khi re-render danh sách mới
    const checkBoxMulti = document.querySelector("[checkbox-multi]");
    if (checkBoxMulti) {
        checkBoxMulti.checked = false;
    }
    const form = document.querySelector("[filter-form]");
    syncForm(form);
    checkboxMulti();
    handleFormCustomer("khách hàng");
    initDetailCustomer(); 
    initDeleteCustomer(renderCustomer);
}