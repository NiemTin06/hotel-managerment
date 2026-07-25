<div class="card mb-3">
    <div class="card-header py-2">
        <h6 class="mb-0">Bộ lọc và tìm kiếm</h6>
    </div>

    <div class="card-body py-2">
        <form class="row g-2 align-items-end" method="get" action="<?php echo URLROOT; ?>/rooms" filter-form>
            <div class="col-md-6 col-lg">
                <label for="room-type" class="form-label small mb-1">Loại phòng</label>
                <select id="room-type" name="room-type" class="form-select form-select-sm">
                    <option value="">Tất cả loại phòng</option>
                </select>
            </div>

            <div class="col-md-6 col-lg">
                <label for="room-checkin" class="form-label small mb-1">Ngày nhận</label>
                <input type="date" id="room-checkin" name="checkin" class="form-control form-control-sm">
            </div>

            <div class="col-md-6 col-lg">
                <label for="room-checkout" class="form-label small mb-1">Ngày trả</label>
                <input type="date" id="room-checkout" name="checkout" class="form-control form-control-sm">
            </div>

            <div class="col-md-6 col-lg">
                <label for="sort-by" class="form-label small mb-1">Sắp xếp</label>
                <select id="sort-by" name="sort-by" class="form-select form-select-sm">
                    <option value="">Mặc định</option>
                    <option value="price_asc">Giá thấp đến cao</option>
                    <option value="price_desc">Giá cao đến thấp</option>
                    <option value="discount_desc">Khuyến mãi</option>
                </select>
            </div>

            <div class="col-md-6 col-lg-auto">
                <button type="submit" class="btn btn-primary btn-sm w-100">Tìm phòng</button>
            </div>
        </form>

        <p id="room-filter-message" class="small text-muted mt-2 mb-0">Chọn ngày nhận và ngày trả để kiểm tra phòng trống.</p>
    </div>
</div>
