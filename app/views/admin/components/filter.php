<div class="card mb-3">
    <div class="card-header">
        <h5>Bộ lọc và Tìm kiếm</h5>
    </div>
    <div class="card-body">
        <form
            class="row g-3"
            filter-form
            action="">
            <div class="col-6"></div>
            <div class="col-6 d-flex justify-content-end">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên phòng">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
            <div class="col-3">
                <?php if (!empty($sortOptions)): ?>
                    <label for="sort-by" class="form-label">Sắp xếp theo</label>
                    <select id="sort-by" name="sort-by" class="form-select">
                        <?php foreach ($sortOptions as $value => $label): ?>
                            <option value="<?= $value ?>"><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-3">
                <?php if (!empty($statusOptions)): ?>
                    <label for="status" class="form-label">Trạng thái</label>
                    <select id="status" name="status" class="form-select">
                        <?php foreach ($statusOptions as $value => $label): ?>
                            <option value="<?= $value ?>"><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-3">
                <?php if (!empty($bedTypes)): ?>
                    <label for="roomtype-bed-type" class="form-label">Loại giường</label>

                    <select id="roomtype-bed-type" name="roomtype-bed-type" class="form-select">
                        <?php foreach ($bedTypes as $item): ?>
                            <option value="<?= $item['value'] ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-3">
                <?php if (!empty($maxGuests)): ?>
                    <label for="roomtype-max-guests" class="form-label">Sức chứa</label>
                    <select id="roomtype-max-guests" name="roomtype-max-guests" class="form-select">
                        <?php foreach ($maxGuests as $item): ?>
                            <option value="<?= $item['value'] ?>">
                                <?= $item['label'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>