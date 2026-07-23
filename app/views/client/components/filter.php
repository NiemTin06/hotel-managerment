<div class="room-filter-card">
    <div class="room-filter-header">
        <h2>Bộ lọc và Tìm kiếm</h2>
    </div>

    <div class="room-filter-body">
        <form class="room-filter-form" method="get" action="<?php echo URLROOT; ?>/rooms" filter-form>
            <div class="room-filter-field">
                <label for="room-type">Loại phòng</label>
                <select id="room-type" name="room-type" class="form-select">
                    <option value="">Tất cả loại phòng</option>
                </select>
            </div>

            <div class="room-filter-field">
                <label for="room-checkin">Ngày nhận</label>
                <input type="date" id="room-checkin" name="checkin" class="form-control">
            </div>

            <div class="room-filter-field">
                <label for="room-checkout">Ngày trả</label>
                <input type="date" id="room-checkout" name="checkout" class="form-control">
            </div>

            <div class="room-filter-field">
                <label for="sort-by">Sắp xếp</label>
                <select id="sort-by" name="sort-by" class="form-select">
                    <option value="">Mặc định</option>
                    <option value="price_asc">Giá thấp đến cao</option>
                    <option value="price_desc">Giá cao đến thấp</option>
                    <option value="discount_desc">Khuyến mãi</option>
                </select>
            </div>

            <div class="room-filter-action">
                <button type="submit" class="btn btn-primary">Tìm phòng</button>
            </div>
        </form>

        <p id="room-filter-message" class="room-filter-message">
            Chọn ngày nhận và ngày trả để kiểm tra phòng trống.
        </p>
    </div>
</div>
