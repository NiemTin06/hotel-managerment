<?php /** @var array $data */ ?>

<div class="home-page">
    <section id="home-banner" class="home-banner">
        <div id="home-banner-list" class="home-banner-list"></div>
        <div class="home-banner-cover"></div>

        <div class="home-banner-content">
            <p class="home-welcome">CHÀO MỪNG ĐẾN VỚI</p>

            <h1>NEKO HOTEL</h1>

            <p>
                Không gian nghỉ dưỡng thoải mái, tiện nghi và phù hợp
                cho chuyến đi cá nhân, gia đình hoặc công tác.
            </p>

            <div class="home-banner-actions">
                <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-primary">
                    Xem các loại phòng
                </a>
            </div>
        </div>

        <button id="home-banner-prev" type="button" class="home-banner-button home-banner-prev"><i class="bi bi-chevron-left"></i></button>

        <button id="home-banner-next" type="button" class="home-banner-button home-banner-next"><i class="bi bi-chevron-right"></i></button>

        <div id="home-banner-dots" class="home-banner-dots"></div>
    </section>

    <section class="home-section home-about">
        <div class="home-about-image">
            <img src="<?php echo URLROOT; ?>/public/client/img/hoboibuoitoi.png" alt="Không gian khách sạn">
        </div>

        <article class="home-about-content">
            <p class="home-small-title">GIỚI THIỆU KHÁCH SẠN</p>

            <h2>Nơi nghỉ dưỡng dành cho bạn</h2>

            <p>
                Neko Hotel mang đến không gian nghỉ ngơi thoải mái,
                sạch sẽ và tiện nghi. Khách sạn có nhiều loại phòng phù hợp
                với khách đi công tác, du lịch cá nhân hoặc gia đình.
            </p>

            <p>
                Khách hàng có thể linh hoạt kiểm tra lịch để chọn phòng phù hợp nhu cầu, xem giá,
                gửi yêu cầu đặt phòng và theo dõi lịch sử trong tài khoản.
            </p>

            <div class="home-about-info">
                <div>
                    <strong>24/7</strong>
                    <span>Hỗ trợ khách hàng</span>
                </div>

                <div>
                    <strong>Tiện nghi</strong>
                    <span>Không gian thoải mái</span>
                </div>

                <div>
                    <strong>Nhanh chóng</strong>
                    <span>Đặt phòng trực tuyến</span>
                </div>
            </div>

            <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-primary">
                Khám phá phòng
            </a>
        </article>
    </section>

    <section class="home-section featured-room-section">
        <div class="home-section-heading">
            <div>
                <p class="home-small-title">PHÒNG NỔI BẬT</p>
                <h2>Các loại phòng dành cho bạn</h2>

                <p>
                    Một số loại phòng đang hoạt động và có ưu đãi tại khách sạn.
                </p>
            </div>

            <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-outline-primary">
                Xem tất cả
            </a>
        </div>

        <div id="featured-room-list" class="row g-4">
            <div class="col-12">
                <div class="home-room-message">
                    Đang tải các loại phòng nổi bật...
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-feedback">
        <div class="home-section-heading home-feedback-heading">
            <div>
                <p class="home-small-title">Ý KIẾN KHÁCH HÀNG</p>
                <h2>Khách hàng nói gì về chúng tôi?</h2>

                <p>
                    Một số đánh giá của khách hàng sau khi sử dụng dịch vụ.
                </p>
            </div>
        </div>

        <div class="home-feedback-list">
            <article class="home-feedback-card">
                <div class="home-feedback-stars">★★★★★</div>

                <p>
                    “Phòng sạch sẽ, nhân viên thân thiện và thủ tục nhận phòng nhanh.”
                </p>

                <div class="home-feedback-user">
                    <span>MN</span>

                    <div>
                        <strong>Minh Ngọc</strong>
                        <small>Khách du lịch</small>
                    </div>
                </div>
            </article>

            <article class="home-feedback-card">
                <div class="home-feedback-stars">★★★★★</div>

                <p>
                    “Không gian yên tĩnh, giá phòng hợp lý và đặt phòng rất dễ dàng.”
                </p>

                <div class="home-feedback-user">
                    <span>HT</span>

                    <div>
                        <strong>Hoàng Tuấn</strong>
                        <small>Khách công tác</small>
                    </div>
                </div>
            </article>

            <article class="home-feedback-card">
                <div class="home-feedback-stars">★★★★★</div>

                <p>
                    “Gia đình tôi có một kỳ nghỉ thoải mái. Phòng rộng và đầy đủ tiện nghi.”
                </p>

                <div class="home-feedback-user">
                    <span>TL</span>

                    <div>
                        <strong>Thu Lan</strong>
                        <small>Khách gia đình</small>
                    </div>
                </div>
            </article>
        </div>
    </section>
</div>