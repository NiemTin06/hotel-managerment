<?php
/** @var array $data */
?>

<div class="create-popup-container" id="createPopup">

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="popup-content">
            <div class="card shadow-lg border-0 rounded-4 overlay">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        Tạo loại phòng mới
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data" create-form>

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

                                <input
                                    type="number"
                                    class="form-control"
                                    id="roomtype-max-guests"
                                    name="roomtype-max-guests"
                                    placeholder="2"
                                    required>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="roomtype-bed-type" class="form-label">
                                    Loại giường
                                </label>

                               <select id="roomtype-bed-type" name="roomtype-bed-type" class="form-select">
                                    <option value="" selected disabled> Chọn giường</option>
                                    <option value="oneSingleBed">1 Giường đơn</option>
                                    <option value="twoSingleBeds">2 Giường đơn</option>
                                    <option value="threeSingleBeds">3 Giường đơn</option>
                                    <option value="oneDoubleBed">1 Giường đôi</option>
                                    <option value="twoDoubleBeds">2 Giường đôi</option>
                                    <option value="threeDoubleBeds">3 Giường đôi</option>
                                    <option value="oneQueenBed">1 Giường Queen</option>
                                    <option value="oneKingBed">1 Giường King</option>
                                    <option value="oneQueenOneKing">1 Giường Queen + 1 Giường King</option>
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
                                class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
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