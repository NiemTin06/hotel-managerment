const roomCtx = document.getElementById("roomChart");

if (roomCtx) {
    new Chart(roomCtx, {
        type: "doughnut",
        data: {
            labels: [
                "Trống",
                "Đã đặt",
                "Đang sử dụng",
                "Bảo trì"
            ],
            datasets: [{
                data: [35, 20, 40, 5],
                backgroundColor: [
                    "#10b981", // Green tươi hơn
                    "#f59e0b", // Amber/Yellow hiện đại
                    "#3b82f6", // Blue pastel/modern
                    "#ef4444"  // Red dịu mắt
                ],
                borderWidth: 3,             // Khoảng cách giữa các phần
                borderColor: "#ffffff",     // Viền trắng tách khối
                hoverOffset: 8              // Hiệu ứng nhô ra khi hover
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "75%",                   // Làm vòng doughnut mỏng và thanh lịch hơn
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        usePointStyle: true, // Đổi ô vuông màu thành chấm tròn
                        pointStyle: "circle",
                        padding: 20,
                        font: {
                            size: 13,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    usePointStyle: true
                }
            }
        }
    });
}

const bookingCtx = document.getElementById("bookingChart");

if (bookingCtx) {
    new Chart(bookingCtx, {
        type: "bar",
        data: {
            labels: [
                "T1",
                "T2",
                "T3",
                "T4",
                "T5",
                "T6"
            ],
            datasets: [{
                label: "Lượt đặt phòng",
                data: [10, 20, 18, 25, 30, 28],
                backgroundColor: "#3b82f6",
                hoverBackgroundColor: "#2563eb", // Tối lại một chút khi hover
                borderRadius: 8,                 // Bo góc trên đầu cột
                borderSkipped: false,
                barThickness: 24                 // Chiều rộng cột vừa vặn, không bị phình to
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Ẩn legend nếu chỉ có 1 dataset cho gọn
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Ẩn lưới dọc cho thoáng
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    border: {
                        dash: [5, 5] // Đường lưới ngang dạng nét đứt
                    },
                    grid: {
                        color: "#f1f5f9" // Màu lưới mờ nhẹ
                    },
                    beginAtZero: true
                }
            }
        }
    });
}