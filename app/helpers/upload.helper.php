<?php

class UploadHelper {

    private static $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private static $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    private static $maxSize = 5 * 1024 * 1024; // 5MB

     /**
     * Xử lý upload 1 ảnh
     * 
     * @param array $file Mảng $_FILES['ten_input']
     * @param string $uploadDir Thư mục lưu ảnh (tính từ gốc project, ví dụ 'public/uploads/rooms/')
     * @return array ['success' => bool, 'fileName' => string|null, 'message' => string]
     */

    public static function uploadImage(array $file, string $uploadDir): array {
        // 1. Kiểm tra có file được gửi lên không
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'fileName' => null, 'message' => 'Dữ liệu file không hợp lệ.'];
        }

        // 2. Kiểm tra lỗi upload từ PHP
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'fileName' => null, 'message' => 'Chưa chọn file để upload.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'fileName' => null, 'message' => 'File vượt quá kích thước cho phép.'];
            default:
                return ['success' => false, 'fileName' => null, 'message' => 'Lỗi không xác định khi upload.'];
        }

        // 3. Kiểm tra kích thước file
        if ($file['size'] > self::$maxSize) {
            return ['success' => false, 'fileName' => null, 'message' => 'Kích thước file vượt quá 5MB.'];
        }

        // 4. Kiểm tra MIME type thật (dùng finfo, an toàn hơn kiểm tra đuôi file client gửi lên)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, self::$allowedTypes)) {
            return ['success' => false, 'fileName' => null, 'message' => 'Định dạng file không được hỗ trợ. Chỉ chấp nhận JPG, PNG, WEBP, GIF.'];
        }

        // 5. Kiểm tra đuôi file (thêm 1 lớp bảo vệ nữa)
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); # Ham cat chuoi lay duoi file
        if (!in_array($ext, self::$allowedExt)) {
            return ['success' => false, 'fileName' => null, 'message' => 'Đuôi file không hợp lệ.'];
        }

        // 6. Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 7. Đặt tên file mới để tránh trùng lặp / tránh lộ tên file gốc
        $newFileName = uniqid('img_', true) . '.' . $ext;
        $destination = rtrim($uploadDir, '/') . '/' . $newFileName;

        // 8. Di chuyển file từ thư mục tạm sang thư mục lưu trữ
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'fileName' => null, 'message' => 'Không thể lưu file lên server.'];
        }

        return ['success' => true, 'fileName' => $newFileName, 'message' => 'Upload thành công.'];
    }

    /**
     * Xoá 1 ảnh cũ (dùng khi update ảnh mới thay ảnh cũ)
     */
    public static function deleteImage(string $uploadDir, string $fileName): bool {
        $path = rtrim($uploadDir, '/') . '/' . $fileName;
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}