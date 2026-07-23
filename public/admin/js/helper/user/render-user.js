import { checkboxMulti } from '../common/checkbox.js';
import { syncForm } from '../common/url.js';
import { initDeleteUser } from './delete-user.js';
import { initDetailUser } from './detail-user.js';
import { handleFormUser } from './handle-form-user.js';
import { initResetPassUser } from './reset-pass-user.js';

export function renderUser(users, container) {
    const userListContainer = document.querySelector(`#${container}`);
    if (!userListContainer) return;

    const html = (Array.isArray(users) ? users : []).map((user, index) => {
        const statusMap = {
            Active: `<span class="badge bg-success">Hoạt động</span>`,
            Inactive: `<span class="badge bg-secondary">Đã khóa</span>`,
        };
        const statusHtml = statusMap[user.USER_STATUS] ?? `<span class="badge bg-dark">Không xác định</span>`;

        const roleMap = {
            Admin: `<span class="badge bg-danger fw-bold">Admin</span>`,
            Staff: `<span class="badge bg-info text-dark fw-bold">Staff</span>`,
            Customer:`<span class = "badge text-bg-light fw-bold">Customer</span>`,
        };
        const roleHtml = roleMap[user.USER_ROLE] ?? `<span class="badge bg-light text-dark">Staff</span>`;

        return `
            <tr>
                <td>
                    <input type="checkbox" name="id" value="${user.USER_ID}">
                </td>
                <th scope="row" class="align-middle">${index + 1}</th>
                
                <td class="align-middle fw-bold text-primary">${user.USER_USERNAME}</td>
                <td class="align-middle">${user.USER_EMAIL}</td>
                <td class="align-middle">${user.USER_PHONE ?? "---"}</td>
                <td class="align-middle">${roleHtml}</td> 
                <td class="align-middle">${statusHtml}</td> 
                <td class="align-middle text-center">
                    <button class="btn btn-sm btn-warning fw-bold" data-id="${user.USER_ID}" reset-pass title="Reset về 123456">🔑 Reset</button>
                    <button class="btn btn-sm btn-secondary" data-id="${user.USER_ID}" detail-user>Chi tiết</button>
                    <button class="btn btn-sm btn-info text-white btn-update-popup" data-id="${user.USER_ID}" update-user>Sửa</button>
                    <button class="btn btn-sm btn-danger" data-id="${user.USER_ID}" delete-user>Xóa</button>
                </td>
            </tr>
        `;
    }).join("");

    userListContainer.innerHTML = html;

    const checkBoxMulti = document.querySelector("[checkbox-multi]");
    if (checkBoxMulti) {
        checkBoxMulti.checked = false;
    }
    
    const form = document.querySelector("[filter-form]");
    syncForm(form);
    checkboxMulti();
    handleFormUser("tài khoản");
    initDetailUser(); 
    initDeleteUser(renderUser);
    initResetPassUser();
}