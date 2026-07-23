<?php
/** @var array $data */
/** @var string $tbodyId */
/** @var string $object */
/** @var array $status */
/** @var array $sortOptions */
/** @var array $statusOptions */
?>

<div class="popup-container" id="popup">

    <div class="row justify-content-center">
        <div class="col">
            <div class="popup-content">
            <div class="card shadow-lg border-0 rounded-4 overlay">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 popup-title">
                        Tạo tài khoản mới
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data" popup-form>
                        
                        <div class="mb-3">
                            <label for="user-username" class="form-label">
                                Tên tài khoản (Username) <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="user-username"
                                name="username"
                                placeholder="Ví dụ: letan_a"
                                required autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="user-email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                id="user-email"
                                name="email"
                                placeholder="Ví dụ: letan@hotel.com"
                                required autocomplete="off">
                        </div>

                        <!-- KHU VỰC NHẬP MẬT KHẨU (JS sẽ tự động ẩn đi khi bấm nút Sửa) -->
                        <div class="mb-3" id="div-password-input">
                            <label for="user-password" class="form-label">
                                Mật khẩu khởi tạo
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                id="user-password"
                                name="password"
                                placeholder="Mặc định: 123456 (Nếu để trống)" autocomplete="new-password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="user-phone" class="form-label">
                                Số điện thoại
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="user-phone"
                                name="phone"
                                placeholder="Ví dụ: 0901234567">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="user-role" class="form-label">
                                    Quyền hạn
                                </label>
                                <select
                                    class="form-select"
                                    id="user-role"
                                    name="role">
                                    <option value="Staff">Staff (Lễ tân)</option>
                                    <option value="Admin">Admin (Quản lý)</option>
                                    <option value="Customer">Customer (khách hàng)</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="user-status" class="form-label">
                                    Trạng thái
                                </label>
                                <select
                                    class="form-select"
                                    id="user-status"
                                    name="status">
                                    <option value="Active">Active (Hoạt động)</option>
                                    <option value="Inactive">Inactive (Khóa)</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a
                                type="button"
                                class="btn btn-outline-secondary"
                                id="btnClosePopup">
                                Hủy
                            </a>
                            <button
                                type="submit"
                                class="btn btn-primary btn-submit">
                                Thêm tài khoản
                            </button>
                        </div>

                    </form>

                </div>

            </div>
            </div>
        </div>
    </div>

</div>