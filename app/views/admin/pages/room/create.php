<?php
/** @var array $data */
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description']; ?></p>  
    </div>
    <form create-room>
        <div class="form-group">
            <label for="room-number">Số phòng</label>
            <input type="text"  id="room-number" name="room-number" required>
        </div>
         <div class="form-group">
            <label for="room-type">Loại phòng</label>
            <select id="room-type" name="room-type" required>
                <option value="">Chọn loại phòng</option>
            </select>
        </div>
        <div class="form-group">
            <label for="room-description">Mô tả</label>
            <textarea id="room-description" name="room-description" required></textarea>
        </div>
        <div class="form-group">
            <label for="room-status">Trạng thái</label>
            <select id="room-status" name="room-status" required>
                <option value="">Chọn trạng thái</option>
                <option value="available">Có sẵn</option>
                <option value="booked">Đã đặt trước</option>
                <option value="occupied">Đã cho thuê</option>
                <option value="maintenance">Đang bảo trì</option>
            </select>
        </div>
        <div class="form-group">
            <button type ="submit">Them phong</button>
        </div>
    </form>
</div>

