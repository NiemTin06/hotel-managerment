<!-- Popup Thêm/Sửa Khách hàng -->
<div class="popup-container" id="popup">
    <div class="row justify-content-center">
        <div class="col">
            <div class="popup-content">
                <div class="card shadow-lg border-0 rounded-4 overlay">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 popup-title">Thêm Khách hàng mới</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" popup-form>
                            <div class="mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer-fullname" required placeholder="Nguyễn Văn A">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer-phone" required placeholder="09xx xxx xxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CCCD / Passport</label>
                                    <input type="text" class="form-control" name="customer-cccd" placeholder="12 chữ số...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="customer-email" placeholder="email@example.com">
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="btnClosePopup">Hủy</button>
                                <button type="submit" class="btn btn-primary btn-submit">Lưu lại</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>