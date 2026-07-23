<?php /** @var array $data */ ?>

<div class="home-page">
    <section class="home-hero">
        <div class="home-hero-content">
            <p class="home-hero-small">CHÀO MỪNG ĐẾN VỚI</p>

            <h1>HOTEL MANAGER</h1>

            <p class="home-hero-description">
                Không gian nghỉ dưỡng thoải mái, nhiều loại phòng phù hợp cho chuyến đi cá nhân, gia đình hoặc công tác.
            </p>

            <div class="home-hero-actions">
                <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-primary">Xem các loại phòng</a>
                <a href="<?php echo URLROOT; ?>/booking-lookup" class="btn btn-outline-light">Tra cứu đơn</a>
            </div>
        </div>
    </section>

    <section class="featured-room-section">
        <div class="featured-room-heading">
            <div>
                <p class="featured-room-small">PHÒNG NỔI BẬT</p>
                <h2>Các loại phòng dành cho bạn</h2>
                <p>
                    Một số loại phòng đang hoạt động tại khách sạn.
                    Chọn xem thêm để kiểm tra phòng trống theo ngày.
                </p>
            </div>
        </div>

        <div id="featured-room-list" class="row g-3">
            <div class="col-12">
                <div class="home-room-loading">
                    Đang tải các loại phòng nổi bật...
                </div>
            </div>
        </div>

        <div class="featured-room-more">
            <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-outline-primary">Xem thêm loại phòng</a>
        </div>
    </section>
</div>
