<?php /** @var array $data */ ?>

<div class="booking-lookup-page">
    <?php
    $pageEyebrow = 'TRA CỨU';
    $pageActionUrl = URLROOT . '/rooms';
    $pageActionText = 'Đặt phòng mới';
    require __DIR__ . '/../../components/page-heading.php';
    ?>

    <section class="lookup-panel">
        <?php
        $sectionTitle = 'Tra cứu đơn đặt phòng';
        $sectionText = 'Nhập mã đơn và số điện thoại đã dùng khi đặt phòng.';
        require __DIR__ . '/../../components/section-heading.php';
        ?>

        <form id="booking-lookup-form" class="booking-search-bar">
            <div class="booking-search-field booking-code-field">
                <label for="lookup-booking-id">Mã đơn</label>
                <input type="number" id="lookup-booking-id" name="booking-id" class="form-control" min="1" step="1" placeholder="Nhập mã đơn" required>
            </div>

            <div class="booking-search-field">
                <label for="lookup-phone">Số điện thoại</label>

                <input type="tel" id="lookup-phone" name="customer-phone" class="form-control" 
                    inputmode="numeric"
                    pattern="0[0-9]{9}"
                    maxlength="10"
                    placeholder="Nhập số điện thoại"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Tra cứu</button>
        </form>

        <p id="booking-lookup-message" class="lookup-message"></p>

        <div id="booking-lookup-result">
            <div class="lookup-empty">
                Chưa có kết quả tra cứu.
            </div>
        </div>
    </section>

    <div class="lookup-notice">
        <strong>Lưu ý:</strong>
        <span>
            Muốn thay đổi thông tin hoặc hủy đơn, vui lòng liên hệ khách sạn qua hotline 0900 000 000.
        </span>
    </div>
</div>
