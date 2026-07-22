<!-- Popup Chi tiết Khách hàng -->
<div class="popup-container" id="popup-detail">
    <div class="popup-content">
        <div class="card shadow-lg">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Hồ sơ chi tiết khách hàng</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Họ và tên:</div>
                    <div class="col-sm-8 fw-bold fs-5" id="detail-fullname"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Số điện thoại:</div>
                    <div class="col-sm-8 fw-bold text-primary" id="detail-phone"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">CCCD/Passport:</div>
                    <div class="col-sm-8" id="detail-cccd"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Email:</div>
                    <div class="col-sm-8" id="detail-email"></div>
                </div>
                <hr>
                <div class="row text-center bg-light p-3 rounded">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">TỔNG SỐ ĐƠN ĐẶT</small>
                        <span class="fs-4 fw-bold text-success" id="detail-bookings">0</span> <small>lần</small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">TỔNG CHI TIÊU</small>
                        <span class="fs-4 fw-bold text-danger" id="detail-spent">0</span> <small>VNĐ</small>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-secondary" id="btn-close-detail">Đóng</button>
            </div>
        </div>
    </div>
</div>