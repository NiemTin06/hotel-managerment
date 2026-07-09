<div class="card mb-3">
    <div class="card-header">
        <h5>Bộ lọc và Tìm kiếm</h5>
    </div>
    <div class="card-body">
        <form 
            class="row g-3"
            filter-form
            action =""
        >
            <div class="col-6"></div>
            <div class="col-6 d-flex justify-content-end">
                <div class="input-group">
                    <input type="text" name = "search" class="form-control" placeholder="Tìm kiếm theo tên phòng">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
            <div class="col-3">
                <label for="sort-by" class="form-label">Sắp xếp theo</label>
                <select id="sort-by" name = "sort-by" class="form-select">
                    <option value="">Mặc định</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                    <option value="room_number_asc">Số phòng A-Z</option>
                    <option value="room_number_desc">Số phòng Z-A</option>
                </select>
            </div>
            <div class="col-3">
                <label for="room-type" class="form-label">Loại phòng</label>
                <select id="room-type" name ="room-type" class="form-select">
                    <option value="">Tất cả</option>
                </select>
            </div>
            <div class="col-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select id="status" name ="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="Available">Còn trống</option>
                    <option value="Booked">Đã đặt</option>
                    <option value="Occupied">Đang sử dụng</option>
                    <option value="Maintenance">Bảo trì</option>
                    <option value="Deleted">Ngừng hoạt động</option>
                </select>
            </div>
            <div class="col-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>     
    </div>
</div>