<?php
require_once 'config.php';
$ok=false;$error='';
try{
    $server=new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';charset='.DB_CHARSET,DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $server->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Tạo cấu trúc database từ database.sql.
    $sql=file_get_contents(__DIR__.'/database.sql');
    $sql=preg_replace('/CREATE DATABASE.*?;|USE .*?;/is','',$sql);
    foreach(array_filter(array_map('trim',preg_split('/;\s*(?:\r?\n|$)/',$sql))) as $st){
        if($st) db()->exec($st);
    }

    $pdo=db();
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    foreach(['student_documents','announcements','assessments','attendance','students','classes','teachers','subjects','users'] as $t){
        $pdo->exec("TRUNCATE TABLE `$t`");
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    // Tương thích với database cũ: bổ sung personnel_type nếu bảng teachers
    // được tạo từ phiên bản trước chưa có cột này.
    $col=$pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='teachers' AND COLUMN_NAME='personnel_type'");
    $col->execute();
    if((int)$col->fetchColumn()===0){
        $pdo->exec("ALTER TABLE teachers ADD COLUMN personnel_type VARCHAR(30) NOT NULL DEFAULT 'Giáo viên' AFTER position_name");
    }

    $seed=json_decode(file_get_contents(__DIR__.'/data/data.json'),true);
    if(!is_array($seed)) throw new RuntimeException('Không đọc được data/data.json');

    // Tài khoản demo.
    $users=[
        ['admin','admin123','Quản trị viên','admin'],
        ['giaovien','123456','Nguyễn Thị Giáo Viên','teacher'],
        ['phuhuynh','123456','Phụ huynh mẫu','parent']
    ];
    foreach($users as $u){
        q('INSERT INTO users(username,password,full_name,role) VALUES(?,?,?,?)',[
            $u[0],password_hash($u[1],PASSWORD_DEFAULT),$u[2],$u[3]
        ]);
    }

    // Nạp toàn bộ 31 giáo viên từ bộ dữ liệu mẫu.
    $teacherMap=[];
    foreach($seed['teachers']??[] as $t){
        q('INSERT INTO teachers(code,full_name,gender,dob,phone,email,subject,address,qualification,position_name,personnel_type) VALUES(?,?,?,?,?,?,?,?,?,?,?)',[
            $t['code']??'',
            $t['full_name']??'',
            $t['gender']??null,
            $t['dob']??null,
            $t['phone']??null,
            $t['email']??null,
            $t['subject']??null,
            $t['address']??null,
            $t['qualification']??null,
            $t['position_name']??null,
            $t['personnel_type']??'Giáo viên'
        ]);
        $teacherMap[(int)($t['id']??0)]=(int)$pdo->lastInsertId();
    }

    // Nạp 20 lớp theo dữ liệu mẫu, liên kết giáo viên chủ nhiệm nếu có.
    $classMap=[];
    foreach($seed['classes']??[] as $c){
        $oldTeacherId=(int)($c['teacher_id']??0);
        $newTeacherId=$teacherMap[$oldTeacherId]??null;
        q('INSERT INTO classes(code,name,grade,school_year,teacher_id,room,session_name,capacity) VALUES(?,?,?,?,?,?,?,?)',[
            $c['code']??'',
            $c['name']??'',
            (int)($c['grade']??0),
            $c['school_year']??SCHOOL_YEAR,
            $newTeacherId,
            $c['room']??null,
            $c['session_name']??'Cả ngày',
            (int)($c['capacity']??35)
        ]);
        $classMap[(int)($c['id']??0)]=(int)$pdo->lastInsertId();
    }

    // Nạp danh mục môn học.
    foreach($seed['subjects']??[] as $s){
        $name=is_array($s)?($s['name']??''):trim((string)$s);
        if($name!=='') q('INSERT INTO subjects(name) VALUES(?)',[$name]);
    }

    // Nạp 544 học sinh và liên kết đúng lớp.
    foreach($seed['students']??[] as $s){
        $oldClassId=(int)($s['class_id']??0);
        $classId=$classMap[$oldClassId]??null;
        q('INSERT INTO students(code,full_name,dob,gender,place_of_birth,nationality,current_address,father_name,father_phone,mother_name,mother_phone,class_id,school_year,status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[
            $s['code']??'',
            $s['full_name']??'',
            !empty($s['dob'])?$s['dob']:null,
            $s['gender']??null,
            $s['place_of_birth']??null,
            $s['nationality']??'Việt Nam',
            $s['address']??'',
            $s['parent_name']??'',
            $s['parent_phone']??'',
            $s['parent_name']??'',
            $s['parent_phone']??'',
            $classId,
            SCHOOL_YEAR,
            $s['status']??'Đang học'
        ]);
    }

    // Nạp thông báo mẫu.
    foreach($seed['announcements']??[] as $a){
        q('INSERT INTO announcements(title,content,created_at,created_by) VALUES(?,?,?,?)',[
            $a['title']??'Thông báo hệ thống',
            $a['content']??'',
            $a['created_at']??date('Y-m-d H:i:s'),
            1
        ]);
    }

    $ok=true;
}catch(Throwable $e){
    $error=$e->getMessage();
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Cài đặt hệ thống</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-page"><div class="login-card">
<?php if($ok): ?>
<div class="brand-mark">✓</div><h1>Cài đặt thành công</h1>
<p>Database MySQL đã được tạo và dữ liệu mẫu đã được nạp.</p>
<div class="demo-box"><strong>Tài khoản demo</strong><br>admin / admin123<br>giaovien / 123456<br>phuhuynh / 123456</div>
<p>Đã nạp: <strong><?=count($seed['students']??[])?></strong> học sinh, <strong><?=count($seed['classes']??[])?></strong> lớp, <strong><?=count(array_filter($seed['teachers']??[],fn($x)=>(($x['personnel_type']??'Giáo viên')==='Giáo viên')))?></strong> giáo viên và <strong><?=count(array_filter($seed['teachers']??[],fn($x)=>(($x['personnel_type']??'Giáo viên')==='Nhân viên')))?></strong> nhân viên.</p>
<a class="btn primary full" href="login.php">Đăng nhập hệ thống</a>
<?php else: ?>
<h1>Cài đặt chưa hoàn tất</h1><div class="alert danger"><?=e($error)?></div><p>Bật MySQL trong XAMPP rồi thử lại.</p><a class="btn full" href="install.php">Thử lại</a>
<?php endif; ?>
</div></body></html>
