<?php /** @var array $data */ ?>

<div class="booking-page">
    <div class="page-title">
        <div>
            <h1><?php echo htmlspecialchars($data['title']); ?></h1>
            <p><?php echo htmlspecialchars($data['description']); ?></p>
        </div>

        <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-outline-primary">Chọn lại phòng</a>
    </div>

    <div id="booking-message"></div>

    <form id="booking-form">
        <input type="hidden" id="booking-room-type-id" name="room-type-id">

        <input type="hidden" id="booking-checkin-input" name="booking-checkin">

        <input type="hidden" id="booking-checkout-input" name="booking-checkout">

        <div class="booking-box">
            <h2>Loại phòng đã chọn</h2>
            <div id="selected-room-list">
                <div class="empty-text">
                    Đang tải thông tin loại phòng...
                </div>
            </div>
        </div>

        <div class="booking-box">
            <h2>Thông tin đặt phòng</h2>

            <div class="booking-inline-field">
                <label for="customer-fullname">Họ tên <span class="required-mark">*</span></label>

                <input type="text" id="customer-fullname" name="customer-fullname" class="form-control"
                maxlength="100"
                placeholder="Nhập họ tên người đặt" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-phone">Số điện thoại <span class="required-mark">*</span></label>

                <input type="tel" id="customer-phone" name="customer-phone" class="form-control"
                    inputmode="numeric"
                    pattern="0[0-9]{9}"
                    maxlength="10"
                    placeholder="Ví dụ: 0373737650" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-cccd">CCCD <span class="required-mark">*</span></label>
                <input type="text" id="customer-cccd" name="customer-cccd" class="form-control"
                    inputmode="numeric"
                    pattern="[0-9]{12}"
                    minlength="12"
                    maxlength="12"
                    placeholder="Nhập đúng 12 chữ số" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-email">Email</label>

                <input type="email" id="customer-email" name="customer-email" class="form-control" maxlength="100">
            </div>

            <div class="booking-stay-row">
                <div>
                    <span>Ngày nhận:</span>
                    <strong id="booking-checkin"></strong>
                </div>

                <div>
                    <span>Ngày trả:</span>
                    <strong id="booking-checkout"></strong>
                </div>

                <div>
                    <span>Thời gian: </span>
                    <strong id="booking-night-count"></strong>
                </div>
            </div>
        </div>

            <div class="booking-note-field">
                <label for="booking-note">Ghi chú</label>

                <textarea id="booking-note" name="booking-note" class="form-control" rows="3"
                    placeholder="Không bắt buộc"
                ></textarea>
            </div>

            <p class="guest-note">
                Vui lòng nhập chính xác thông tin! Khách sạn sẽ dùng thông tin trên để liên hệ xác nhận đơn.
            </p>

            <div class="total-box">
                <div>
                    <span>Tổng tiền:</span>
                    <strong id="total-price">0 đ</strong>
                </div>

                <button id="btn-booking" type="submit" class="btn btn-success" disabled>
                    Gửi yêu cầu đặt phòng
                </button>
            </div>
        </div>
    </form>
</div>
