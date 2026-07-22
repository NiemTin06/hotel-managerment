<?php

// Khai báo kiểu type hint cho biết biến sẽ tồn tại
/** @var array $data */
/** @var string $tbodyId */
/** @var string $object */
/** @var array $status */
/** @var array $sortOptions */
/** @var array $statusOptions */
/** @var array $maxGuests */
/** @var array $bedTypes */
?>

<div class="popup-container" id="popup">

    <div class="row justify-content-center">
        <div class="col">
            <div class="popup-content">
            <div class="card shadow-lg border-0 rounded-4 overlay">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 popup-title">
                        Tạo loại phòng mới
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data" popup-form>
                        <div class="mb-3">
                            <label for="roomtype-name" class="form-label">
                                Tên loại phòng
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="roomtype-name"
                                name="roomtype-name"
                                placeholder="Ví dụ: Phòng Deluxe"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="roomtype-price" class="form-label">
                                    Giá mỗi đêm (VNĐ)
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="roomtype-price"
                                    name="roomtype-price"
                                    placeholder="500000"
                                    required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="roomtype-discount" class="form-label">
                                    Giảm giá (%)
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="roomtype-discount"
                                    name="roomtype-discount"
                                    min="0"
                                    max="100"
                                    value="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="roomtype-max-guests" class="form-label">
                                    Sức chưa (người)
                                </label>
                                <select id="roomtype-max-guests" name="roomtype-max-guests" class="form-select">
                                    <option value="" selected disabled> Chọn sưc chứa</option>
                                    <?php foreach ($maxGuests as $item): ?>
                                        <?php if ($item['value'] == '') continue; ?>
                                        <option value="<?= $item['value'] ?>">
                                            <?= $item['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="roomtype-bed-type" class="form-label">
                                    Loại giường
                                </label>

                               <select id="roomtype-bed-type" name="roomtype-bed-type" class="form-select">
                                    <option value="" selected disabled> Chọn giường</option>
                                    <?php foreach ($bedTypes as $item): ?>
                                         <?php if ($item['value'] == '') continue; ?>
                                        <option value="<?= $item['value'] ?>">
                                            <?= htmlspecialchars($item['label']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="roomtype-description" class="form-label">
                                Mô tả loại phòng
                            </label>

                            <textarea
                                class="form-control"
                                id="roomtype-description"
                                name="roomtype-description"
                                rows="3"
                                placeholder="Nhập mô tả loại phòng..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="roomtype-thumbnail" class="form-label">
                                Hình ảnh đại diện
                            </label>
                            <input
                                class="form-control"
                                type="file"
                                id="roomtype-thumbnail"
                                name="thumbnail"
                                accept="image/*">
                            <div class="thumbnail-preview">
                                <img id="preview-image"
                                    src=""
                                    alt="Preview">
                            </div>
                            <button
                                type="button"
                                class="btn btn-danger mt-2"
                                id="btn-remove-image"
                            >
                                Xóa ảnh
                            </button>
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                type="button"
                                class="btn btn-outline-secondary"
                                id="btnClosePopup">
                                Hủy
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary btn-submit">
                                Thêm loại phòng
                            </button>

                        </div>

                    </form>

                </div>

            </div>
            </div>
        </div>
    </div>

</div>