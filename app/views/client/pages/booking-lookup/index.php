<?php /** @var array $data */ ?>

<div class="booking-lookup-page">
    <div class="client-page-heading">
        <div>
            <h1><?php echo htmlspecialchars($data['title']); ?></h1>
            <p><?php echo htmlspecialchars($data['description']); ?></p>
        </div>

        <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-primary">Đặt phòng mới</a>
    </div>

    <section class="lookup-panel">
        <div class="lookup-panel-heading">
            <h2>Tra cứu đơn đặt phòng</h2>
            <p>
                Nhập mã đơn và số điện thoại đã dùng khi đặt phòng.
            </p>
        </div>

        <form id="booking-lookup-form" class="booking-search-bar">
            <div class="booking-search-field booking-code-field">
                <label for="lookup-booking-id">Mã đơn</label>
                <input type="number" id="lookup-booking-id" name="booking-id" class="form-control"
                    min="1"
                    step="1"
                    placeholder="Nhập mã đơn" required>
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
