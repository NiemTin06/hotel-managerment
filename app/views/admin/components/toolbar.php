<?php
/** @var array $data */
/** @var array $status */
?>

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
                                <option value="" disabled selected> Chọn trạng thái</option>
                                <?php foreach ($status as $item): ?>
                                    <option value="<?= $item["value"] ?>">
                                        <?= $item["label"] ?>
                                    </option>
                                <?php endforeach; ?>
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
                    <button 
                        class="btn btn-primary ms-3" 
                        id ="btnCreatePopup" 
                    >
                        Thêm phòng mới</a>
                </div>
            </div>
        </div>
    </div>