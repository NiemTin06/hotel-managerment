import { API } from '../api/api.js';
import { escapeHtml, formatCurrency, formatDate, getBedName } from '../helper/format.js';

const form = document.querySelector('#booking-form');
const selectedRoom = document.querySelector('#selected-room-list');
const roomTypeIdInput = document.querySelector('#booking-room-type-id');
const checkinInput = document.querySelector('#booking-checkin-input');
const checkoutInput = document.querySelector('#booking-checkout-input');
const checkinText = document.querySelector('#booking-checkin');
const checkoutText = document.querySelector('#booking-checkout');
const nightText = document.querySelector('#booking-night-count');
const totalText = document.querySelector('#total-price');
const bookingButton = document.querySelector('#btn-booking');
const messageBox = document.querySelector('#booking-message');

let roomType = null;
let checkin = '';
let checkout = '';

function getNightCount() {
    if (!checkin || !checkout) return 0;

    const start = new Date(checkin + 'T00:00:00');
    const end = new Date(checkout + 'T00:00:00');

    return Math.max(0,Math.round((end.getTime() - start.getTime())/ 86400000));
}


function showMessage(text, type = 'danger') {
    messageBox.innerHTML = `
        <div class="alert alert-${type}">
            ${escapeHtml(text)}
        </div>
    `;
}

function clearMessage() {
    messageBox.innerHTML = '';
}

function showSelectedRoom() {
    roomTypeIdInput.value = '';
    totalText.textContent = '0 đ';
    bookingButton.disabled = true;

    if (!roomType) {
        selectedRoom.innerHTML = `
            <div class="empty-text">
                Bạn chưa chọn loại phòng hoặc loại phòng đã chọn
                không còn hoạt động.
                <a href="${APP_URLROOT}/rooms">
                    Quay lại danh sách phòng
                </a>
            </div>
        `;

        return;
    }

    const roomCount = Number(roomType.AVAILABLE_ROOM_COUNT || 0);
    const nightCount = getNightCount();
    const price = Number(roomType.PRICE_AFTER_DISCOUNT || 0);
    const originalPrice = Number(roomType.ROOMTYPE_PRICE_PER_NIGHT || 0);
    const discount = Number(roomType.ROOMTYPE_DISCOUNT_PERCENTAGE || 0);
    const total = price * nightCount;

    roomTypeIdInput.value = roomType.ROOMTYPE_ID;
    nightText.textContent = nightCount > 0 ? nightCount + ' đêm' : '';
    totalText.textContent = nightCount > 0 ? formatCurrency(total) : '';

    let oldPrice = '';

    if (discount > 0) {
        oldPrice = `
            <span class="selected-room-old-price">
                ${formatCurrency(originalPrice)}
            </span>
        `;
    }

    selectedRoom.innerHTML = `
        <article class="selected-room-card">
            <div class="selected-room-main">
                <div class="selected-room-heading">
                    <div>
                        <h3>${escapeHtml(roomType.ROOMTYPE_NAME)}</h3>

                        <p>
                            ${
                                escapeHtml(roomType.ROOMTYPE_DESCRIPTION || 'Loại phòng chưa có mô tả.')
                            }
                        </p>
                    </div>

                    <span class="booking-availability-text 
                        ${
                            roomCount > 0 ? 'available-booking-room' : 'unavailable-booking-room'
                        }">
                        ${
                            roomCount > 0 ? `Còn ${roomCount} phòng`: 'Đã hết phòng'
                        }
                    </span>
                </div>

                <div class="selected-room-detail">
                    <div>
                        <span>Sức chứa</span>
                        <strong>
                            Tối đa
                            ${Number(roomType.ROOMTYPE_MAX_GUESTS || 0)}
                            khách
                        </strong>
                    </div>

                    <div>
                        <span>Loại giường</span>
                        <strong>
                            ${escapeHtml(
                                getBedName(roomType.ROOMTYPE_BED_TYPE)
                            )}
                        </strong>
                    </div>

                    <div>
                        <span>Khuyến mãi</span>
                        <strong>${discount}%</strong>
                    </div>
                </div>
            </div>

            <div class="selected-room-price-box">
                ${oldPrice}

                <strong>
                    ${formatCurrency(price)}
                </strong>

                <small>/ đêm</small>
            </div>
        </article>
    `;
    bookingButton.disabled = roomCount <= 0 || nightCount <= 0;
}

async function loadBooking(roomTypeId = '') {
    const params = new URLSearchParams({
        room_type_id: roomTypeId,
        checkin: checkin,
        checkout: checkout
    });

    clearMessage();

    try {
        const data = await API.get(
            'bookings/data?' + params.toString()
        );

        if (!data.success) {
            showMessage(data.message);
            showSelectedRoom();
            return;
        }

        roomType = data.selected_room_type || null;
        checkin = data.checkin || '';
        checkout = data.checkout || '';

        checkinInput.value = checkin;
        checkoutInput.value = checkout;
        checkinText.textContent = formatDate(checkin);
        checkoutText.textContent = formatDate(checkout);
        showSelectedRoom();

    } catch (error) {
        roomType = null;
        showSelectedRoom();
        showMessage(error.message);
    }
}

const urlParams = new URLSearchParams(
    window.location.search
);

checkin = urlParams.get('checkin') || '';
checkout = urlParams.get('checkout') || '';

loadBooking(urlParams.get('room_type_id') || '');

form.addEventListener(
    'submit',
    async function (event) {
        event.preventDefault();
        clearMessage();

        if (!roomType) {
            showMessage('Vui lòng quay lại danh sách phòng để chọn loại phòng.');
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        const phoneInput = document.querySelector('#customer-phone');
        const cccdInput = document.querySelector('#customer-cccd');

        const phone = phoneInput.value.trim().replace(/[\s.\-]/g, '');
        const cccd = cccdInput.value.trim();

        if (!/^0[0-9]{9}$/.test(phone)) {
            showMessage('Số điện thoại không hợp lệ.');
            return;
        }

        if (!/^[0-9]{12}$/.test(cccd)) {
            showMessage('CCCD phải gồm đúng 12 chữ số.');
            return;
        }

        phoneInput.value = phone;
        cccdInput.value = cccd;
        bookingButton.disabled = true;

        try {
            const result = await API.post(
                'bookings/process',
                new FormData(form)
            );

            if (!result.success) {
                showMessage(result.message);
                return;
            }

            const lookupParams = new URLSearchParams({
                booking_id: result.booking_id,
                phone: result.guest_phone
            });

            window.location.href = APP_URLROOT + '/booking-lookup?' + lookupParams.toString();
        } catch (error) {
            showMessage(error.message);
        } finally {
            if (roomType) {
                bookingButton.disabled = Number(roomType.AVAILABLE_ROOM_COUNT) <= 0;
            }
        }
    }
);
