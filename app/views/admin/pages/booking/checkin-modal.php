<!-- Popup Lễ tân Check-in xếp phòng -->
<div class="popup-container" id="popup-checkin">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="popup-content">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">🔑 Lễ tân Check-in - Gán số phòng</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Đơn đặt phòng: <b id="ci-booking-id" class="text-dark"></b></p>
                        <p class="text-muted">Khách hàng: <b id="ci-customer-name" class="text-primary"></b></p>
                        <p class="text-muted">Loại phòng yêu cầu: <b id="ci-roomtype-name" class="text-danger"></b></p>
                        
                        <hr>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chọn phòng vật lý đang trống:</label>
                            <select class="form-select form-select-lg border-success" id="select-available-room">
                                <option value="">-- Đang tìm phòng trống... --</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" id="btn-close-checkin">Hủy</button>
                            <button type="button" class="btn btn-success fw-bold px-4" id="btn-confirm-checkin">Xác nhận Check-in</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>