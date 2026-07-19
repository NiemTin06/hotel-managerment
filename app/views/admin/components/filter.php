<div class="card mb-3">
    <div class="card-header">
        <h5>Bộ lọc và Tìm kiếm</h5>
    </div>
    <div class="card-body">
        <form class="row g-3" filter-form action="">
            <!-- Tìm kiếm -->
            <div class="col-12 d-flex justify-content-end">
                <div class="input-group" style="max-width: 420px;">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Tìm kiếm theo tên phòng">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Tìm kiếm
                    </button>
                </div>
            </div>

            <?php if (!empty($sortOptions)): ?>
                <div class="col-md-4 col-lg-3">
                    <label for="sort-by" class="form-label">Sắp xếp theo</label>
                    <select id="sort-by" name="sort-by" class="form-select">
                        <?php foreach ($sortOptions as $value => $label): ?>
                            <option value="<?= $value ?>">
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($statusOptions)): ?>
                <div class="col-md-4 col-lg-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select id="status" name="status" class="form-select">
                        <?php foreach ($statusOptions as $value => $label): ?>
                            <option value="<?= $value ?>">
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($filterTypeRoom)): ?>
                <div class="col-md-4 col-lg-3">
                    <label for="room-type" class="form-label">Loại phòng</label>
                    <select id="room-type" name="room-type" class="form-select">

                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($bedTypes)): ?>
                <div class="col-md-4 col-lg-3">
                    <label for="roomtype-bed-type" class="form-label">Loại giường</label>

                    <select id="roomtype-bed-type"
                        name="roomtype-bed-type"
                        class="form-select">
                        <?php foreach ($bedTypes as $item): ?>
                            <option value="<?= $item['value'] ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($maxGuests)): ?>
                <div class="col-md-4 col-lg-3">
                    <label for="roomtype-max-guests" class="form-label">Sức chứa</label>

                    <select id="roomtype-max-guests"
                        name="roomtype-max-guests"
                        class="form-select">
                        <?php foreach ($maxGuests as $item): ?>
                            <option value="<?= $item['value'] ?>">
                                <?= $item['label'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Nút lọc -->
            <div class="col-12 d-flex justify-content-end">
                <button
                    type="submit"
                    class="btn btn-primary px-4">
                    Lọc
                </button>
            </div>

        </form>
    </div>
</div>