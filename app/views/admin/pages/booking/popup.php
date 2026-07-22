<!-- Popup Tạo đơn đặt phòng -->
<div class="popup-container" id="popup">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="popup-content">
                <div class="card shadow-lg border-0 rounded-4 overlay">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 popup-title">Tạo đơn đặt phòng mới</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" popup-form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Chọn Khách hàng <span class="text-danger">*</span></label>
                                    <select class="form-select" name="booking-customer" id="select-customer" required>
                                        <option value="">-- Đang tải danh sách... --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Chọn Loại phòng <span class="text-danger">*</span></label>
                                    <select class="form-select" name="booking-roomtype" id="select-roomtype" required>
                                        <option value="">-- Đang tải danh sách... --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ngày Check-in <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="booking-checkin" id="input-checkin" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày Check-out <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="booking-checkout" id="input-checkout" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái đơn</label>
                                    <select class="form-select" name="booking-status">
                                        <option value="Pending">Chờ xác nhận (Pending)</option>
                                        <option value="Confirmed">Đã xác nhận / Cọc (Confirmed)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tổng giá tiền (VNĐ)</label>
                                    <input type="number" class="form-control fw-bold text-danger" name="booking-price" id="input-price" value="0">
                                    <small class="text-muted" id="price-hint">Hệ thống tự động tính khi chọn ngày</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú yêu cầu đặc biệt</label>
                                <textarea class="form-control" name="booking-note" rows="2" placeholder="Ví dụ: Cần tầng cao, phòng yên tĩnh..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="btnClosePopup">Hủy</button>
                                <button type="submit" class="btn btn-primary btn-submit">Lưu đơn</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>