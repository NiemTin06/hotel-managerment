<?php
session_start();
?>

    <div class="index-login-signup">
        <h4>SIGN UP</h4>
        <p>Don't have an account yet? Sign up here!</p>
        <div id="demo" style="margin-bottom: 15px; font-weight: bold;"></div>
        <form id="signupForm" onsubmit="return false;">
            <input type="text" name="uid" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <input type="password" name="pwdRepeat" placeholder="Repeat Password">
            <input type="text" name="email" placeholder="E-mail">
            <br>
            <!-- <button type="submit" name="submit">SIGN UP</button> -->
             <button type="button" onclick="loadDoc()">SIGN UP</button>
        </form>
    </div>
    <script>
        // 1. Khởi tạo đối tượng AJAX đời đầu giống hệt ảnh bạn gửi 👑
    function loadDoc() {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            const messageBox = document.getElementById("demo");
            try {
                const data = JSON.parse(this.responseText);
                
                if (data.status === 'error') {
                    messageBox.style.color = 'red';
                    messageBox.innerHTML = data.message; 
                } else if (data.status === 'success') {
                    messageBox.style.color = 'green';
                    messageBox.innerHTML = data.message; 
                    document.getElementById('signupForm').reset(); // Xóa sạch form khi thành công
                }
            } catch (e) {
                // Nếu PHP có lỗi cú pháp hoặc báo lỗi nghiêm trọng, nó sẽ quăng text vào đây để bạn debug
                messageBox.style.color = 'orange';
                messageBox.innerHTML = this.responseText;
            }
        };
        
        // Gom dữ liệu ngay tại thời điểm người dùng bấm nút
        const formData = new FormData(document.getElementById('signupForm'));
        
        // ĐÃ SỬA: Đồng bộ trỏ về đúng route nhận dữ liệu POST của bạn là /signupPost
        xhttp.open("POST", "<?php echo URLROOT; ?>/signupPost");
        
        // Thực hiện bắn dữ liệu đi
        xhttp.send(formData);
    }
</script>
<!-- </body>
</html> -->