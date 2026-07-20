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
                        Tạo phòng mới
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data" popup-form>
                        <div class="mb-3">
                            <label for="room-number" class="form-label">
                                Số phòng
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="room-number"
                                name="room-number"
                                placeholder="Ví dụ: 101"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="room-roomtype" class="form-label">
                                Loại phòng
                            </label>
                            <select
                                class="form-select"
                                id="room-roomtype"
                                name="room-roomtype">

                                <option value="" selected disabled>
                                    Chọn loại phòng
                                </option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="room-description" class="form-label">
                                Mô tả phòng
                            </label>

                            <textarea
                                class="form-control"
                                id="room-description"
                                name="room-description"
                                rows="3"
                                placeholder="Nhập mô tả loại phòng..."></textarea>
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
                                Thêm phòng
                            </button>

                        </div>

                    </form>

                </div>

            </div>
            </div>
        </div>
    </div>

</div>