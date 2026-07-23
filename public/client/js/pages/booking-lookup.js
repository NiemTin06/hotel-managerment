import { API } from '../api/api.js';
import { escapeHtml, formatCurrency, formatDate } from '../helper/format.js';
const resultBox = document.querySelector('#booking-lookup-result');
const searchForm = document.querySelector('#booking-lookup-form');
const searchMessage = document.querySelector('#booking-lookup-message');
const bookingIdInput = document.querySelector('#lookup-booking-id');
const phoneInput = document.querySelector('#lookup-phone');

function getStatusName(status) {
    if (status === 'Pending') return 'Chờ xác nhận';
    if (status === 'Confirmed') return 'Đã xác nhận';
    if (status === 'CheckedIn') return 'Đang lưu trú';
    if (status === 'CheckedOut') return 'Đã trả phòng';
    if (status === 'Cancelled') return 'Đã hủy';
    return status;
}

function showBooking(booking) {
    let roomNumber = 'Chưa xếp phòng';

    if (booking.BOOKING_STATUS === 'Cancelled') {
        roomNumber = 'Đơn đã hủy';
    } else if (booking.ROOM_NUMBER) {
        roomNumber = 'Phòng ' + booking.ROOM_NUMBER;
    }

    resultBox.innerHTML = `
        <div class="lookup-booking-list">
            <article class="lookup-booking-card">
                <div class="lookup-booking-header">
                    <div>
                        <span>Mã đơn</span>
                        <strong>#${Number(booking.BOOKING_ID)}</strong>
                    </div>

                    <span class="lookup-status status-${
                        String(booking.BOOKING_STATUS).toLowerCase()
                    }">
                        ${escapeHtml(getStatusName(booking.BOOKING_STATUS))}
                    </span>
                </div>

                <div class="lookup-booking-content">
                    <div>
                        <span>Khách hàng</span>
                        <strong>
                            ${escapeHtml(booking.CUSTOMER_FULLNAME)}
                        </strong>
                    </div>

                    <div>
                        <span>Loại phòng</span>
                        <strong>
                            ${escapeHtml(booking.ROOMTYPE_NAME)}
                        </strong>
                    </div>

                    <div>
                        <span>Số phòng</span>
                        <strong>${escapeHtml(roomNumber)}</strong>
                    </div>

                    <div>
                        <span>Ngày nhận</span>
                        <strong>
                            ${formatDate(booking.BOOKING_CHECKIN)}
                        </strong>
                    </div>

                    <div>
                        <span>Ngày trả</span>
                        <strong>
                            ${formatDate(booking.BOOKING_CHECKOUT)}
                        </strong>
                    </div>

                    <div>
                        <span>Tổng tiền</span>
                        <strong class="lookup-price">
                            ${formatCurrency(booking.BOOKING_TOTAL_PRICE)}
                        </strong>
                    </div>

                    <div class="lookup-note">
                        <span>Ghi chú</span>
                        <strong>
                            ${escapeHtml(booking.BOOKING_NOTE || 'Không có')}
                        </strong>
                    </div>
                </div>
            </article>
        </div>
    `;
}

async function searchBooking() {
    searchMessage.textContent = '';

    try {
        const result = await API.post(
            'booking-lookup/search',
            new FormData(searchForm)
        );

        if (!result.success) {
            searchMessage.textContent = result.message;
            searchMessage.className =
                'lookup-message text-danger';

            resultBox.innerHTML = `
                <div class="lookup-empty">
                    Không có kết quả phù hợp.
                </div>
            `;
            return;
        }

        searchMessage.textContent = 'Tìm thấy booking.';
        searchMessage.className = 'lookup-message text-success';

        showBooking(result.booking);
    } catch (error) {
        searchMessage.textContent = error.message;
        searchMessage.className =
            'lookup-message text-danger';
    }
}

searchForm.addEventListener(
    'submit',
    function (event) {
        event.preventDefault();

        if (!searchForm.reportValidity()) {
            return;
        }

        searchBooking();
    }
);

const params = new URLSearchParams(
    window.location.search
);

bookingIdInput.value = params.get('booking_id') || '';
phoneInput.value = params.get('phone') || '';

if (bookingIdInput.value && phoneInput.value) {
    searchBooking();
}
