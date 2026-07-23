import { API } from '../api/api.js';
import { escapeHtml, formatCurrency, getBedName } from '../helper/format.js';

const roomList = document.querySelector(
    '#client-room-type-list'
);

const resultCount = document.querySelector(
    '#room-result-count'
);

const filterForm = document.querySelector(
    '[filter-form]'
);

const roomTypeSelect = document.querySelector(
    '#room-type'
);

const checkinInput = document.querySelector(
    '#room-checkin'
);

const checkoutInput = document.querySelector(
    '#room-checkout'
);

const sortSelect = document.querySelector(
    '#sort-by'
);

const message = document.querySelector(
    '#room-filter-message'
);

const urlParams = new URLSearchParams(
    window.location.search
);

let checkin = urlParams.get('checkin') || '';
let checkout = urlParams.get('checkout') || '';
let selectedRoomTypeId =
    urlParams.get('room-type') || '';
let selectedSort =
    urlParams.get('sort-by') || '';

let checkedDate =
    checkin !== '' && checkout !== '';

let loadedRoomTypeOptions = false;
let currentRoomTypes = [];

function getToday() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(
        today.getMonth() + 1
    ).padStart(2, '0');
    const day = String(
        today.getDate()
    ).padStart(2, '0');

    return year + '-' + month + '-' + day;
}

function loadRoomTypeOptions(roomTypes) {
    if (loadedRoomTypeOptions) {
        return;
    }

    roomTypes.forEach(function (roomType) {
        const option = document.createElement('option');

        option.value = roomType.ROOMTYPE_ID;
        option.textContent = roomType.ROOMTYPE_NAME;

        roomTypeSelect.appendChild(option);
    });

    roomTypeSelect.value = selectedRoomTypeId;
    loadedRoomTypeOptions = true;
}

function showRoomTypes(roomTypes) {
    currentRoomTypes = roomTypes;

    let html = '';
    let availableTypeCount = 0;

    roomTypes.forEach(function (roomType) {
        const roomCount = Number(
            roomType.AVAILABLE_ROOM_COUNT || 0
        );

        const canBook =
            checkedDate && roomCount > 0;

        if (canBook) {
            availableTypeCount++;
        }

        let image = `
            <div class="client-room-image no-image">
                Chưa có ảnh
            </div>
        `;

        if (roomType.ROOMTYPE_THUMBNAIL) {
            image = `
                <img
                    class="client-room-image"
                    src="${APP_URLROOT}/public/uploads/roomtypes/${encodeURIComponent(roomType.ROOMTYPE_THUMBNAIL)}"
                    alt="${escapeHtml(roomType.ROOMTYPE_NAME)}"
                >
            `;
        }

        let oldPrice = '';

        if (
            Number(
                roomType.ROOMTYPE_DISCOUNT_PERCENTAGE
            ) > 0
        ) {
            oldPrice = `
                <span class="old-price">
                    ${formatCurrency(roomType.ROOMTYPE_PRICE_PER_NIGHT)}
                </span>
            `;
        }

        let roomStatus = 'Chọn ngày để kiểm tra';
        let statusClass = 'not-checked';

        if (checkedDate && roomCount > 0) {
            roomStatus = `Còn ${roomCount} phòng`;
            statusClass = '';
        }

        if (checkedDate && roomCount <= 0) {
            roomStatus = 'Hết phòng';
            statusClass = 'is-empty';
        }

        html += `
            <div class="col-12 col-md-6 col-lg-4">
                <article class="client-room-card">
                    <div class="room-image-box">
                        ${image}

                        <span class="room-card-status ${statusClass}">
                            ${roomStatus}
                        </span>
                    </div>

                    <div class="client-room-body">
                        <h2>
                            ${escapeHtml(roomType.ROOMTYPE_NAME)}
                        </h2>

                        <div class="room-info-box">
                            <p>
                                <strong>Sức chứa:</strong>
                                Tối đa
                                ${Number(roomType.ROOMTYPE_MAX_GUESTS)}
                                khách
                            </p>

                            <p>
                                <strong>Loại giường:</strong>
                                ${
                                    escapeHtml(
                                        getBedName(
                                            roomType.ROOMTYPE_BED_TYPE
                                        )
                                    )
                                }
                            </p>

                            <p>
                                <strong>Khuyến mãi:</strong>
                                ${
                                    Number(
                                        roomType
                                            .ROOMTYPE_DISCOUNT_PERCENTAGE
                                    )
                                }%
                            </p>
                        </div>

                        <p class="room-description">
                            ${
                                escapeHtml(
                                    roomType.ROOMTYPE_DESCRIPTION
                                    || 'Loại phòng chưa có mô tả.'
                                )
                            }
                        </p>

                        <div class="room-price-box">
                            <div class="room-original-price">
                                ${oldPrice}
                            </div>

                            <div class="room-sale-price">
                                <strong>
                                    ${formatCurrency(roomType.PRICE_AFTER_DISCOUNT)}
                                </strong>

                                <small>/ đêm</small>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn btn-success btn-select-room-type"
                            data-id="${roomType.ROOMTYPE_ID}"
                            ${canBook ? '' : 'disabled'}
                        >
                            ${
                                canBook
                                    ? 'Đặt loại phòng này'
                                    : 'Chưa thể đặt'
                            }
                        </button>
                    </div>
                </article>
            </div>
        `;
    });

    if (roomTypes.length === 0) {
        html = `
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    Không tìm thấy loại phòng phù hợp.
                </div>
            </div>
        `;
    }

    roomList.innerHTML = html;

    if (checkedDate) {
        resultCount.textContent =
            availableTypeCount + ' loại phòng còn trống';
    } else {
        resultCount.textContent =
            roomTypes.length + ' loại phòng phù hợp';
    }
}

async function loadRoomTypes() {
    const params = new URLSearchParams();

    if (selectedRoomTypeId !== '') {
        params.set('room-type', selectedRoomTypeId);
    }

    if (selectedSort !== '') {
        params.set('sort-by', selectedSort);
    }

    if (checkedDate) {
        params.set('checkin', checkin);
        params.set('checkout', checkout);
    }

    try {
        const result = await API.get(
            'rooms/client/data?' + params.toString()
        );

        if (!result.success) {
            checkedDate = false;

            message.textContent = result.message;
            message.className =
                'room-filter-message text-danger';

            return;
        }

        loadRoomTypeOptions(
            result.room_type_options || []
        );

        showRoomTypes(result.room_types || []);

        if (checkedDate) {
            message.textContent =
                'Đã kiểm tra phòng trống.';

            message.className =
                'room-filter-message text-success';
        }
    } catch (error) {
        checkedDate = false;

        message.textContent = error.message;
        message.className =
            'room-filter-message text-danger';
    }
}

function fillFilterFromUrl() {
    roomTypeSelect.value = selectedRoomTypeId;
    checkinInput.value = checkin;
    checkoutInput.value = checkout;
    sortSelect.value = selectedSort;

    checkinInput.min = getToday();
    checkoutInput.min = checkin || getToday();
}

filterForm.addEventListener(
    'submit',
    function (event) {
        const newCheckin = checkinInput.value;
        const newCheckout = checkoutInput.value;

        if (
            newCheckin === ''
            || newCheckout === ''
            || newCheckin < getToday()
            || newCheckout <= newCheckin
        ) {
            event.preventDefault();

            message.textContent =
                'Vui lòng chọn ngày nhận và ngày trả hợp lệ.';

            message.className =
                'room-filter-message text-danger';
        }
    }
);

function markFilterChanged() {
    checkedDate = false;

    message.textContent =
        'Bấm Tìm phòng để kiểm tra lại phòng trống.';

    message.className = 'room-filter-message';

    if (currentRoomTypes.length > 0) {
        showRoomTypes(currentRoomTypes);
    }
}

checkinInput.addEventListener(
    'change',
    function () {
        checkoutInput.min =
            checkinInput.value || getToday();

        markFilterChanged();
    }
);

checkoutInput.addEventListener(
    'change',
    markFilterChanged
);

roomTypeSelect.addEventListener(
    'change',
    markFilterChanged
);

sortSelect.addEventListener(
    'change',
    markFilterChanged
);

roomList.addEventListener(
    'click',
    function (event) {
        const button = event.target.closest(
            '.btn-select-room-type'
        );

        if (!button || !checkedDate) {
            return;
        }

        const params = new URLSearchParams({
            room_type_id: button.dataset.id,
            checkin: checkin,
            checkout: checkout
        });

        window.location.href =
            APP_URLROOT
            + '/bookings?'
            + params.toString();
    }
);

fillFilterFromUrl();
loadRoomTypes();
