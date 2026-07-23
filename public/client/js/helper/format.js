export function escapeHtml(value = '') {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

export function formatCurrency(value = 0) {
    const number = Number(value);

    if (Number.isNaN(number)) {
        return '0 đ';
    }

    return number.toLocaleString('vi-VN') + ' đ';
}

export function formatDate(date, emptyText = '') {
    if (!date) return emptyText;

    const parts = String(date).slice(0, 10).split('-');

    if (parts.length !== 3) return emptyText;

    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

export function getBedName(bedType) {
    if (bedType === 'singleBed') return 'Giường đơn';
    if (bedType === 'doubleBed') return 'Giường đôi';
    if (bedType === 'queenBed') return 'Giường Queen';
    if (bedType === 'kingBed') return 'Giường King';
    return bedType || 'Chưa cập nhật';
}
