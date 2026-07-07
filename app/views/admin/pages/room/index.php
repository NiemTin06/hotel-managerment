<?php
/** @var array $data */
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description']; ?></p>  
    </div>
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
    <div class="card mb-3">
        <div class="card-header ">
            <h5>Quản lý phòng</h5>    
        </div>
        <div class="card-body">
            <div class="row align-items-end g-2">    
                <div class="col-6">
                    <form
                        action = "<?= URLROOT ?>/admin/rooms/change-multi"
                        method="post"
                        form-change-multi
                        class = "d-flex gap-2 align-items-end"
                    >
                        <div class="form-group">
                            <label for="bulk-status" class="form-label mb-2">
                                Cập nhật trạng thái
                            </label>
                            <select id="bulk-status" class="form-select" name ="status">
                                <option value="">Giữ nguyên</option>
                                <option value="Available">Còn trống</option>
                                <option value="Booked">Đã đặt</option>
                                <option value="Occupied">Đang sử dụng</option>
                                <option value="Maintenance">Bảo trì</option>
                                <option value="Deleted">Ngừng hoạt động</option>
                            </select>
                        </div>
                        <div class = "form-group">
                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Áp dụng
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="<?= URLROOT ?>/admin/rooms/add" class="btn btn-primary ms-3">Thêm phòng mới</a>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">
                        <input type="checkbox" checkbox-multi>
                    </th>
                    <th scope="col">STT</th>
                    <th scope="col">Tên phòng</th>
                    <th scope="col">Loại phòng</th>
                    <th scope="col">Giá phòng</th>
                    <th scope="col">Giảm giá</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Hành động</th>
                </tr>
            </thead>
            <tbody id="room-list"></tbody>
        </table>
    </div>
</div>
