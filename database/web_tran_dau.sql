CREATE DATABASE IF NOT EXISTS web_tran_dau;
USE web_tran_dau;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE athletes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    club VARCHAR(100),
    age INT,
    weight INT,
    points INT DEFAULT 0
);

CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    athlete_id INT,
    type VARCHAR(50),
    result VARCHAR(50),
    FOREIGN KEY (athlete_id) REFERENCES athletes(id)
);

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tài khoản quản trị (Admin)

-- Sau khi import database, hãy tạo tài khoản admin bằng lệnh SQL sau trong phpMyAdmin hoặc MySQL CLI:

INSERT INTO users (username, password) VALUES ('admin', 'admin123');

-- Đăng nhập bằng tài khoản này tại `login.php`.

-- Cấp quyền cho tài khoản khác

-- Sau khi đăng nhập bằng tài khoản admin, vào trang `dashboard.php` để xem danh sách người dùng.
-- Tại đây, admin có thể cấp quyền quản trị cho tài khoản khác bằng cách cập nhật trường `role` trong bảng `users` (bạn cần thêm trường này vào database):

ALTER TABLE users ADD COLUMN can_view_ranking TINYINT(1) DEFAULT 0;

-- TINYINT(1): Kiểu dữ liệu boolean, lưu 0 hoặc 1.
-- DEFAULT 0: Mặc định là không cho phép.