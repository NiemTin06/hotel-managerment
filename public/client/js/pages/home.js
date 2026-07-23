import { API } from '../api/api.js';
import { escapeHtml, formatCurrency, getBedName } from '../helper/format.js';
const roomList = document.querySelector(
    '#featured-room-list'
);

function showRooms(roomTypes) {
    if (roomTypes.length === 0) {
        roomList.innerHTML = `
            <div class="col-12">
                <div class="home-room-empty">
                    Chưa có loại phòng nổi bật.
                </div>
            </div>
        `;
        return;
    }

    let html = '';

    roomTypes.slice(0, 3).forEach(function (roomType) {
        let image = `
            <div class="home-room-image home-room-no-image">
                Chưa có ảnh
            </div>
        `;

        if (roomType.ROOMTYPE_THUMBNAIL) {
            image = `
                <img
                    class="home-room-image"
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
                <span class="home-room-old-price">
                    ${formatCurrency(roomType.ROOMTYPE_PRICE_PER_NIGHT)}
                </span>
            `;
        }

        html += `
            <div class="col-12 col-md-6 col-lg-4">
                <article class="home-room-card">
                    <div class="home-room-image-box">
                        ${image}
                    </div>

                    <div class="home-room-body">
                        <h3>
                            ${escapeHtml(roomType.ROOMTYPE_NAME)}
                        </h3>

                        <p class="home-room-description">
                            ${
                                escapeHtml(
                                    roomType.ROOMTYPE_DESCRIPTION
                                    || 'Loại phòng chưa có mô tả.'
                                )
                            }
                        </p>

                        <p class="home-room-short-info">
                            Tối đa
                            ${Number(roomType.ROOMTYPE_MAX_GUESTS)}
                            khách ·
                            ${
                                escapeHtml(
                                    getBedName(
                                        roomType.ROOMTYPE_BED_TYPE
                                    )
                                )
                            }
                        </p>

                        <div class="home-room-price">
                            ${oldPrice}

                            <span class="home-room-new-price">
                                ${formatCurrency(roomType.PRICE_AFTER_DISCOUNT)}
                                <small>/ đêm</small>
                            </span>
                        </div>
                    </div>
                </article>
            </div>
        `;
    });

    roomList.innerHTML = html;
}

async function loadFeaturedRooms() {
    try {
        const result = await API.get('home/data');

        if (!result.success) {
            throw new Error(result.message);
        }

        showRooms(result.room_types || []);
    } catch (error) {
        roomList.innerHTML = `
            <div class="col-12">
                <div class="home-room-empty">
                    ${escapeHtml(error.message)}
                </div>
            </div>
        `;
    }
}

loadFeaturedRooms();
