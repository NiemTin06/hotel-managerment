import { API } from '../api/api.js';
import { escapeHtml, formatCurrency, getBedName } from '../helper/format.js';

const roomList = document.querySelector('#featured-room-list');
const bannerList = document.querySelector('#home-banner-list');
const bannerDots = document.querySelector('#home-banner-dots');
const previousButton = document.querySelector('#home-banner-prev');
const nextButton = document.querySelector('#home-banner-next');

let bannerImages = [];
let bannerIndex = 0;
let bannerTimer = null;

function createBanner(roomTypes) {
    bannerImages = [
        APP_URLROOT + '/public/client/img/hoboibuoitoi.png',
        APP_URLROOT + '/public/client/img/home/dlg-hotel-danang-facilities8.jpg',
        APP_URLROOT + '/public/client/img/home/quayletan.jpeg',
        APP_URLROOT + '/public/client/img/home/POOL-DSC_9973.jpg',
        APP_URLROOT + '/public/client/img/home/Bar-des-amis-cocktail.jpg'
    ];

    bannerList.innerHTML = bannerImages.map(function (imageUrl) {
        return `
            <div
                class="home-banner-slide"
                style="background-image: url('${imageUrl}')"
            ></div>
        `;
    }).join('');

    bannerDots.innerHTML = bannerImages.map(function (_, index) {
        return `<button type="button" class="home-banner-dot" data-index="${index}"></button>
        `;
    }).join('');

    bannerIndex = 0;
    showBanner();
    startBanner();
}

function showBanner() {
    if (bannerIndex < 0) bannerIndex = bannerImages.length - 1;

    if (bannerIndex >= bannerImages.length) bannerIndex = 0;

    bannerList.style.transform = `translateX(-${bannerIndex * 100}%)`;

    const dots = bannerDots.querySelectorAll('.home-banner-dot');

    dots.forEach(function (dot, index) {
        dot.classList.toggle('active', index === bannerIndex);
    });
}

function startBanner() {
    clearInterval(bannerTimer);

    if (bannerImages.length <= 1) return;

    bannerTimer = setInterval(function () {
        bannerIndex++;
        showBanner();
    }, 5000);
}

previousButton.addEventListener('click', function () {
    bannerIndex--;
    showBanner();
    startBanner();
});

nextButton.addEventListener('click', function () {
    bannerIndex++;
    showBanner();
    startBanner();
});

bannerDots.addEventListener('click', function (event) {
    const dot = event.target.closest('.home-banner-dot');
    if (!dot) return;
    bannerIndex = Number(dot.dataset.index);
    showBanner();
    startBanner();
});

function showRooms(roomTypes) {
    if (roomTypes.length === 0) {
        roomList.innerHTML = `
            <div class="col-12">
                <div class="alert alert-secondary text-center">
                    Chưa có loại phòng nổi bật.
                </div>
            </div>
        `;
        return;
    }

    let html = '';

    roomTypes.slice(0, 3).forEach(function (roomType) {
        const discount = Number(
            roomType.ROOMTYPE_DISCOUNT_PERCENTAGE || 0
        );

        let image = `
            <div class="room-image room-image-empty">
                Chưa có ảnh
            </div>
        `;

        if (roomType.ROOMTYPE_THUMBNAIL) {
            image = `
                <img
                    class="room-image"
                    src="${APP_URLROOT}/public/uploads/roomtypes/${encodeURIComponent(roomType.ROOMTYPE_THUMBNAIL)}"
                    alt="${escapeHtml(roomType.ROOMTYPE_NAME)}"
                >
            `;
        }

        let oldPrice = '';

        if (discount > 0) {
            oldPrice = `
                <span class="old-price">
                    ${formatCurrency(roomType.ROOMTYPE_PRICE_PER_NIGHT)}
                </span>
            `;
        }

        html += `
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card room-type-card h-100 overflow-hidden">
                    <div class="position-relative room-image-box">
                        ${image}

                        <span class="badge position-absolute top-0 end-0 m-2 text-bg-light">
                            Phòng nổi bật
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h2 class="h5">
                            ${escapeHtml(roomType.ROOMTYPE_NAME)}
                        </h2>

                        <div class="small bg-light border rounded p-2 mb-2">
                            <p class="mb-1">
                                <strong>Sức chứa:</strong>
                                Tối đa ${Number(roomType.ROOMTYPE_MAX_GUESTS)} khách
                            </p>

                            <p class="mb-1">
                                <strong>Loại giường:</strong>
                                ${escapeHtml(
                                    getBedName(roomType.ROOMTYPE_BED_TYPE)
                                )}
                            </p>

                            <p class="mb-0">
                                <strong>Khuyến mãi:</strong>
                                ${discount}%
                            </p>
                        </div>

                        <p class="small text-muted room-card-description">
                            ${escapeHtml(
                                roomType.ROOMTYPE_DESCRIPTION
                                || 'Loại phòng chưa có mô tả.'
                            )}
                        </p>

                        <div class="d-flex justify-content-between align-items-end mt-auto pt-2 mb-3 border-top">
                            <div>
                                ${oldPrice}
                            </div>

                            <div class="text-danger text-end">
                                <strong class="h5">
                                    ${formatCurrency(
                                        roomType.PRICE_AFTER_DISCOUNT
                                    )}
                                </strong>

                                <small>/ đêm</small>
                            </div>
                        </div>

                        <a
                            href="${APP_URLROOT}/rooms?room-type=${Number(roomType.ROOMTYPE_ID)}"
                            class="btn btn-success w-100 btn-select-room-type"
                        >
                            Xem và đặt phòng
                        </a>
                    </div>
                </article>
            </div>
        `;
    });

    roomList.innerHTML = html;
}

async function loadHome() {
    createBanner();
    try {
        const result = await API.get('home/data');

        if (!result.success) {
            throw new Error(result.message);
        }

        const roomTypes = result.room_types || [];

        showRooms(roomTypes);
    } catch (error) {
        roomList.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    ${escapeHtml(error.message)}
                </div>
            </div>
        `;
    }
}

loadHome();