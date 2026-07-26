import { API } from '../api/api.js';
import { formatCurrency, formatDate, escapeHtml } from '../helper/format.js'

let bookingSnapshot = '';
let bookingTimer = null;

document.addEventListener('DOMContentLoaded', () => {
    initAccountForm();
    loadBookingHistory();

    bookingTimer = setInterval(loadBookingHistory, 10000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) loadBookingHistory();
    });
});

window.addEventListener('beforeunload', () => {
    if (bookingTimer) clearInterval(bookingTimer);
});

function initAccountForm() {
    const form = document.querySelector('#accountForm');
    const messageBox = document.querySelector('#account-message');

    if (!form || !messageBox) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const oldButtonContent = submitButton?.innerHTML;

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Đang cập nhật...';
        }

        try {
            const data = await API.post('my-account/update', new FormData(form));

            if (!data.success) {
                showMessage(messageBox, data.message || 'Cập nhật thông tin thất bại.', 'danger');
                return;
            }

            showMessage(messageBox, data.message, 'success');

            const passwordInput = form.querySelector('#new-password');
            const headerName = document.querySelector('.client-user-text');

            if (passwordInput) passwordInput.value = '';
            if (headerName && data.data?.fullname) headerName.textContent = data.data.fullname;
        } catch (error) {
            showMessage(messageBox, error.message || 'Không thể kết nối đến server.', 'danger');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = oldButtonContent;
            }
        }
    });
}

async function loadBookingHistory() {
    const table = document.querySelector('#booking-table');
    const tableBody = document.querySelector('#booking-history');
    const emptyMessage = document.querySelector('#booking-empty');

    if (!table || !tableBody || !emptyMessage) return;

    try {
        const response = await API.get('my-account/history');
        const bookings = Array.isArray(response?.data) ? response.data : [];
        const nextSnapshot = JSON.stringify(bookings);

        if (nextSnapshot === bookingSnapshot) return;
        bookingSnapshot = nextSnapshot;

        if (bookings.length === 0) {
            table.classList.add('d-none');
            emptyMessage.classList.remove('d-none');
            tableBody.innerHTML = '';
            return;
        }

        emptyMessage.classList.add('d-none');
        table.classList.remove('d-none');
        renderBookingHistory(bookings, tableBody);
    } catch (error) {
        console.error('Không thể tải lịch sử đặt phòng:', error);
    }
}

function renderBookingHistory(bookings, tableBody) {
    const statusMap = {
        Pending: ['Chờ xác nhận', 'text-bg-warning'],
        Confirmed: ['Đã xác nhận', 'text-bg-primary'],
        CheckedIn: ['Đã nhận phòng', 'text-bg-success'],
        CheckedOut: ['Đã trả phòng', 'text-bg-secondary'],
        Cancelled: ['Đã hủy', 'text-bg-danger']
    };

    tableBody.innerHTML = bookings.map((booking) => {
        const [statusName, statusClass] = statusMap[booking.BOOKING_STATUS]
            || [booking.BOOKING_STATUS || '', 'text-bg-secondary'];

        return `
            <tr>
                <td>#${Number(booking.BOOKING_ID)}</td>
                <td>${escapeHtml(booking.ROOMTYPE_NAME || '')}</td>
                <td>${escapeHtml(booking.ROOM_NUMBER || 'Chưa xếp phòng')}</td>
                <td>${formatDate(booking.BOOKING_CHECKIN)}</td>
                <td>${formatDate(booking.BOOKING_CHECKOUT)}</td>
                <td class="text-danger fw-bold">${formatCurrency(booking.BOOKING_TOTAL_PRICE)} đ</td>
                <td><span class="badge ${statusClass}">${escapeHtml(statusName)}</span></td>
            </tr>
        `;
    }).join('');
}

function showMessage(element, message, type) {
    element.className = `alert alert-${type}`;
    element.textContent = message;
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
