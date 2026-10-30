# WEBSITE QUẢN LÝ HỌC SINH – BẢN NỘP ĐỒ ÁN
## Trường Tiểu học Sông Đốc Phân Hiệu 2

### 1. Thông tin đề tài
- **Đề tài:** Xây dựng website quản lý hồ sơ học sinh hỗ trợ công tác giáo vụ.
- **Đơn vị:** Trường Tiểu học Sông Đốc Phân Hiệu 2.
- **Sinh viên:** Thái Thị Trúc Ly – MSSV 7730200005.
- **Lớp:** Đại học Công nghệ thông tin – Khóa 7.
- **Thời gian thực tập:** 01/10/2026 – 01/11/2026.
- **Công nghệ:** PHP 8+, MySQL/MariaDB, PDO, HTML5, CSS3, JavaScript.

### 2. Mục đích
Hệ thống mô phỏng và hỗ trợ các nghiệp vụ giáo vụ thường xuyên: quản lý hồ sơ học sinh, lớp học, giáo viên, chuyên cần, đánh giá, hồ sơ giấy tờ và thông báo; đồng thời hỗ trợ tra cứu và thống kê dữ liệu học sinh.

### 3. Kiến trúc hệ thống
- **Frontend:** HTML/CSS/JavaScript.
- **Backend:** PHP xử lý đăng nhập, phân quyền, CRUD và nghiệp vụ.
- **Database:** MySQL/MariaDB.
- **Kết nối:** PDO với prepared statements.
- `data/data.json` chỉ là **nguồn dữ liệu mẫu** để chương trình cài đặt ban đầu; dữ liệu vận hành sau khi cài đặt được lưu trong MySQL.

### 4. Cấu trúc database
Các bảng chính:
- `users`: tài khoản và phân quyền.
- `teachers`: giáo viên.
- `classes`: lớp học.
- `students`: hồ sơ học sinh mở rộng.
- `student_documents`: hồ sơ, giấy tờ của học sinh.
- `attendance`: chuyên cần.
- `subjects`: môn học.
- `assessments`: đánh giá học sinh.
- `announcements`: thông báo.

### 5. Dữ liệu mẫu
- **544 học sinh**.
- **20 lớp học**.
- **31 giáo viên**.
- **7 cán bộ, nhân viên** được thể hiện trong bộ dữ liệu mẫu tham khảo.
- Hiệu trưởng: Trần Văn Phương.
- Phó Hiệu trưởng: Ngô Văn Say.
- Năm học: 2026–2027.

### 6. Yêu cầu môi trường
- Windows 10/11.
- XAMPP có Apache, PHP và MySQL/MariaDB.
- Khuyến nghị PHP 8.0 trở lên.
- MySQL/MariaDB chạy cổng mặc định 3306.

### 7. Cách cài đặt bằng XAMPP
**Bước 1.** Giải nén thư mục `website_quan_ly_hoc_sinh` vào:

`C:\xampp\htdocs\`

Cấu trúc đúng:

`C:\xampp\htdocs\website_quan_ly_hoc_sinh\index.php`

**Bước 2.** Mở XAMPP Control Panel và bấm **Start** cho:
- Apache
- MySQL

**Bước 3.** Mở trình duyệt:

`http://localhost/website_quan_ly_hoc_sinh/install.php`

Hệ thống sẽ tự:
- tạo database `quan_ly_hoc_sinh`;
- tạo các bảng;
- tạo tài khoản demo;
- nạp dữ liệu mẫu 544 học sinh, 20 lớp, 31 giáo viên và 07 cán bộ, nhân viên.

**Bước 4.** Sau khi báo **Cài đặt thành công**, mở:

`http://localhost/website_quan_ly_hoc_sinh/`

### 8. Tài khoản demo
| Vai trò | Tên đăng nhập | Mật khẩu |
|---|---|---|
| Quản trị viên | `admin` | `admin123` |
| Giáo viên | `giaovien` | `123456` |
| Phụ huynh | `phuhuynh` | `123456` |

### 9. Các chức năng có thể demo
1. Đăng nhập và phân quyền.
2. Dashboard tổng quan.
3. Tra cứu, lọc học sinh.
4. Nhập hồ sơ học sinh với nhiều nhóm thông tin nhân thân, địa chỉ, cha/mẹ/người giám hộ, học tập và chính sách.
5. Sửa và xóa hồ sơ học sinh.
6. Quản lý lớp học.
7. Quản lý cán bộ, giáo viên và nhân viên.
8. Theo dõi chuyên cần.
9. Đánh giá học sinh theo môn/giai đoạn.
10. Quản lý hồ sơ giấy tờ.
11. Quản lý thông báo.
12. Dữ liệu sau khi thao tác được lưu và đọc lại từ MySQL.

### 10. Kịch bản demo đề nghị khi bảo vệ
1. Đăng nhập `admin / admin123`.
2. Mở Dashboard và giải thích các chỉ số.
3. Vào **Hồ sơ học sinh** → tìm kiếm/lọc.
4. Bấm **Nhập hồ sơ học sinh** → nhập một học sinh kiểm thử.
5. Lưu → tìm lại hồ sơ.
6. Sửa một thông tin → lưu → kiểm tra kết quả.
7. Mở phpMyAdmin → database `quan_ly_hoc_sinh` → bảng `students` để chứng minh dữ liệu được lưu trong MySQL.
8. Thử các module lớp, giáo viên, chuyên cần, đánh giá và hồ sơ giấy tờ.

### 11. Lưu ý quan trọng
- `install.php` dùng để **cài đặt và nạp lại dữ liệu mẫu**. Không chạy lại file này sau khi đã nhập dữ liệu thực tế nếu không muốn dữ liệu hiện có bị xóa.
- Không mở trực tiếp file PHP bằng `file:///...`; phải chạy qua Apache bằng địa chỉ `http://localhost/...`.
- Nếu MySQL không dùng tài khoản `root` không mật khẩu, sửa các hằng `DB_USER`, `DB_PASS`, `DB_PORT` trong `config.php`.
- Trước khi nộp chính thức, cần kiểm tra lại số liệu, tên trường, thông tin sinh viên và đối chiếu project với báo cáo, ERD và hướng dẫn của cơ sở đào tạo.

## Xóa hồ sơ học sinh

Tài khoản `admin` có quyền xóa hồ sơ học sinh. Chức năng xóa được thực hiện bằng `POST` và có hộp thoại xác nhận. Khi xóa, hệ thống đồng thời xóa các dữ liệu nghiệp vụ liên quan của học sinh trong `student_documents`, `attendance` và `assessments`, sau đó mới xóa bản ghi tại `students` trong cùng một giao dịch MySQL.

> Khuyến nghị khi demo: chỉ xóa hồ sơ học sinh kiểm thử. Không xóa dữ liệu mẫu đang dùng để minh họa cho báo cáo.


## Dữ liệu nhân sự mẫu
- 31 giáo viên.
- 07 nhân viên.
- Tổng cộng 38 cán bộ, giáo viên và nhân viên trong danh mục nhân sự mẫu.
- Nhân viên mẫu được đánh dấu loại nhân sự `Nhân viên`; giáo viên chủ nhiệm chỉ được chọn từ nhóm `Giáo viên`.


### Tương thích database cũ
Nếu database `quan_ly_hoc_sinh` đã tồn tại từ phiên bản trước, `install.php` tự kiểm tra và bổ sung cột `teachers.personnel_type` trước khi nạp 31 giáo viên và 07 nhân viên. Không cần xóa database thủ công.
