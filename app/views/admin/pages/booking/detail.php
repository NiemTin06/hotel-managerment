<!-- Popup Chi tiết Đơn đặt phòng -->
<div class="popup-container" id="popup-detail">
    <div class="popup-content">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📋 Chi tiết Đơn đặt phòng</h5>
            </div>
            <div class="card-body p-4">
                <p><b>Mã đơn:</b> <span id="dt-id" class="badge bg-dark fs-6"></span></p>
                <p><b>Khách hàng:</b> <span id="dt-customer"></span> (<span id="dt-phone" class="text-primary fw-bold"></span>)</p>
                <p><b>Loại phòng:</b> <span id="dt-roomtype"></span></p>
                <p><b>Phòng thực tế:</b> <span id="dt-room" class="badge bg-success fs-6">Chưa xếp phòng</span></p>
                <p><b>Thời gian:</b> Từ <b id="dt-checkin"></b> đến <b id="dt-checkout"></b></p>
                <p><b>Trạng thái:</b> <span id="dt-status"></span></p>
                <p><b>Tổng giá trị:</b> <span id="dt-price" class="text-danger fw-bold fs-5"></span></p>
                <p><b>Ghi chú:</b> <span id="dt-note" class="text-muted italic"></span></p>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-secondary" id="btn-close-detail">Đóng</button>
            </div>
        </div>
    </div>
</div>