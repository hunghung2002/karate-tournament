<?php
// club-match.php
session_start();
include 'includes/db.php'; // tạo $conn

// helper
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// --- XỬ LÝ AJAX (ADD / EDIT / DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    $action = $_POST['ajax_action'];
    header('Content-Type: application/json; charset=utf-8');

    // --- ADD ---
    if ($action === 'add') {
        $name      = trim($_POST['name'] ?? '');
        $club      = trim($_POST['club'] ?? '');
        $weight    = trim($_POST['weight'] ?? '');
        $age_group = trim($_POST['age_group'] ?? '');
        $gender    = trim($_POST['gender'] ?? '');

        if ($name === '' || $club === '' || !is_numeric($weight) || $age_group === '' || !in_array($gender, ['Nam','Nữ'])) {
            echo json_encode(['status'=>'error','msg'=>'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.']);
            exit;
        }

        $weightVal = (float)$weight;
        $stmt = $conn->prepare("INSERT INTO athletes (name, club, weight, age_group, gender, points) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("ssdss", $name, $club, $weightVal, $age_group, $gender);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            // trả về dữ liệu vừa chèn để client có thể thêm vào DataTable mà không reload
            echo json_encode([
                'status'=>'ok',
                'msg'=>'Đã thêm vận động viên!',
                'athlete'=>[
                    'id'=>$newId,
                    'name'=>$name,
                    'club'=>$club,
                    'weight'=>$weightVal,
                    'age_group'=>$age_group,
                    'gender'=>$gender,
                    'points'=>0
                ]
            ]);
        } else {
            echo json_encode(['status'=>'error','msg'=>'Lỗi cơ sở dữ liệu: '.$stmt->error]);
        }
        $stmt->close();
        exit;
    }

    // --- EDIT ---
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $club = trim($_POST['club'] ?? '');
        $weight = trim($_POST['weight'] ?? '');
        $age_group = trim($_POST['age_group'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $points = intval($_POST['points'] ?? 0);

        if ($id<=0 || $name==='' || $club==='' || !is_numeric($weight) || $age_group==='' || !in_array($gender,['Nam','Nữ'])) {
            echo json_encode(['status'=>'error','msg'=>'Dữ liệu sửa không hợp lệ']);
            exit;
        }
        $weightVal = (float)$weight;
        $stmt = $conn->prepare("UPDATE athletes SET name=?, club=?, weight=?, age_group=?, gender=?, points=? WHERE id=?");
        $stmt->bind_param("ssdssii", $name, $club, $weightVal, $age_group, $gender, $points, $id);
        if ($stmt->execute()) {
            echo json_encode(['status'=>'ok','msg'=>'Cập nhật thành công', 'athlete'=>[
                'id'=>$id,'name'=>$name,'club'=>$club,'weight'=>$weightVal,'age_group'=>$age_group,'gender'=>$gender,'points'=>$points
            ]]);
        } else {
            echo json_encode(['status'=>'error','msg'=>'Lỗi DB: '.$stmt->error]);
        }
        $stmt->close();
        exit;
    }

    // --- DELETE ---
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status'=>'error','msg'=>'ID không hợp lệ']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM athletes WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['status'=>'ok','msg'=>'Xóa thành công']);
        } else {
            echo json_encode(['status'=>'error','msg'=>'Lỗi DB: '.$stmt->error]);
        }
        $stmt->close();
        exit;
    }

    echo json_encode(['status'=>'error','msg'=>'Hành động không hợp lệ']);
    exit;
}

// --- IMPORT CSV (form POST, non-AJAX) ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_csv']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (is_uploaded_file($file) && ($handle = fopen($file,"r")) !== false) {
        $rows = []; $first = fgetcsv($handle, 2000, ",");
        $isHeader = false;
        if ($first) {
            $s = strtolower(implode(',', array_map('trim',$first)));
            if (str_contains($s,'name') || str_contains($s,'club')) $isHeader = true;
            else $rows[] = $first;
        }
        while (($data = fgetcsv($handle,2000,","))!==false) {
            if (count(array_filter($data, fn($c)=>trim($c)!==''))===0) continue;
            $rows[] = $data;
        }
        fclose($handle);
        $inserted=0;$skipped=0;
        $stmt = $conn->prepare("INSERT INTO athletes (name, club, weight, age_group, gender, points) VALUES (?, ?, ?, ?, ?, 0)");
        foreach ($rows as $r) {
            $name = trim($r[0] ?? '');
            $club = trim($r[1] ?? '');
            $age_group = trim($r[2] ?? '');
            $weight = trim($r[3] ?? '');
            $gender = trim($r[4] ?? '');
            if ($name==='' || $club==='' || !is_numeric($weight) || !in_array($gender,['Nam','Nữ'])) { $skipped++; continue; }
            $w=(float)$weight;
            $stmt->bind_param("ssdss",$name,$club,$w,$age_group,$gender);
            if ($stmt->execute()) $inserted++; else $skipped++;
        }
        $stmt->close();
        $message = "Import xong. Đã chèn: $inserted ; Bỏ: $skipped";
    } else {
        $message = "Không thể đọc file CSV.";
    }
}

// --- EXPORT handled via GET ?export=csv or excel
if (isset($_GET['export']) && in_array($_GET['export'], ['csv','excel'])) {
    $type = $_GET['export'];
    $res = $conn->query("SELECT id,name,club,weight,age_group,gender,points FROM athletes ORDER BY id ASC");
    if ($type === 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=athletes.csv');
        $out = fopen('php://output','w');
        fputcsv($out,['ID','Tên','CLB','Hạng cân(kg)','Nhóm tuổi','Giới tính','Điểm']);
        while ($row = $res->fetch_assoc()) fputcsv($out, $row);
        fclose($out); exit;
    } else {
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=athletes.xls");
        echo "ID\tTên\tCLB\tHạng cân(kg)\tNhóm tuổi\tGiới tính\tĐiểm\n";
        while ($row = $res->fetch_assoc()) {
            echo "{$row['id']}\t{$row['name']}\t{$row['club']}\t{$row['weight']}\t{$row['age_group']}\t{$row['gender']}\t{$row['points']}\n";
        }
        exit;
    }
}

// --- Load athletes to render table
$athletes = [];
$res = $conn->query("SELECT id,name,club,weight,age_group,gender,points FROM athletes ORDER BY id DESC");
while ($r = $res->fetch_assoc()) $athletes[] = $r;

?>
<?php include 'includes/header.php'; ?>

<!-- Styles DataTables -->
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<main class="container py-4">
  <h1 class="mb-3">🥋 Quản lý Vận Động Viên</h1>

  <?php if ($message): ?><div class="alert alert-info"><?= h($message) ?></div><?php endif; ?>

  <div class="row g-4 mb-3">
    <!-- Add form -->
    <div class="col-lg-6">
      <div class="card p-3">
        <h5>Thêm VĐV</h5>
        <form id="formAdd" class="row g-2">
          <div class="col-md-6"><input name="name" class="form-control" placeholder="Tên VĐV" required></div>
          <div class="col-md-6"><input name="club" class="form-control" placeholder="CLB" required></div>
          <div class="col-md-4"><input name="weight" type="number" step="0.1" class="form-control" placeholder="Cân nặng (kg)" required></div>
          <div class="col-md-4"><input name="age_group" class="form-control" placeholder="Nhóm tuổi (VD: U18)" required></div>
          <div class="col-md-4">
            <select name="gender" class="form-select" required>
              <option value="">-- Giới tính --</option>
              <option value="Nam">Nam</option>
              <option value="Nữ">Nữ</option>
            </select>
          </div>
          <div class="col-12 text-end"><button class="btn btn-danger" type="submit">THÊM</button></div>
        </form>
      </div>
    </div>

    <!-- Import -->
    <div class="col-lg-6">
      <div class="card p-3">
        <h5>Import CSV</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="row g-2 align-items-center">
            <div class="col-md-8"><input type="file" name="csv_file" class="form-control" accept=".csv" required></div>
            <div class="col-md-4 text-end"><button type="submit" name="import_csv" class="btn btn-warning">IMPORT</button></div>
            <div class="col-12"><small class="text-muted">Định dạng: name,club,age_group,weight,gender</small></div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between mb-2">
    <h5>Danh sách VĐV</h5>
    <div>
      <a href="?export=csv" class="btn btn-outline-primary btn-sm">Xuất CSV</a>
      <a href="?export=excel" class="btn btn-outline-success btn-sm">Xuất Excel</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table id="athletesTable" class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr><th>ID</th><th>Tên</th><th>CLB</th><th>Cân nặng</th><th>Nhóm tuổi</th><th>Giới tính</th><th>Điểm</th><th>Hành động</th></tr>
        </thead>
        <tbody>
          <?php if (empty($athletes)): ?>
            <tr><td colspan="8" class="text-center">Chưa có VĐV</td></tr>
          <?php else: foreach ($athletes as $a): ?>
            <tr data-id="<?= h($a['id']) ?>">
              <td><?= h($a['id']) ?></td>
              <td><?= h($a['name']) ?></td>
              <td><?= h($a['club']) ?></td>
              <td><?= h($a['weight']) ?></td>
              <td><?= h($a['age_group']) ?></td>
              <td><?= h($a['gender']) ?></td>
              <td><?= h($a['points']) ?></td>
              <td>
                <button class="btn btn-sm btn-warning btn-edit"
                  data-id="<?= h($a['id']) ?>" data-name="<?= h($a['name']) ?>" data-club="<?= h($a['club']) ?>"
                  data-weight="<?= h($a['weight']) ?>" data-age_group="<?= h($a['age_group']) ?>" data-gender="<?= h($a['gender']) ?>"
                  data-points="<?= h($a['points']) ?>">Sửa</button>

                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= h($a['id']) ?>">Xóa</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formEdit">
        <div class="modal-header"><h5 class="modal-title">Sửa VĐV</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="mb-2"><input id="edit_name" name="name" class="form-control" required></div>
          <div class="mb-2"><input id="edit_club" name="club" class="form-control" required></div>
          <div class="mb-2"><input id="edit_weight" name="weight" type="number" step="0.1" class="form-control" required></div>
          <div class="mb-2"><input id="edit_age_group" name="age_group" class="form-control" required></div>
          <div class="mb-2">
            <select id="edit_gender" name="gender" class="form-select" required>
              <option>Nam</option><option>Nữ</option>
            </select>
          </div>
          <div class="mb-2"><input id="edit_points" name="points" type="number" class="form-control" required></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary w-100" type="submit">Lưu</button></div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Scripts: jQuery, bootstrap bundle, DataTables, SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- load our external JS (see next block) -->
<script src="assets/js/athletes.js"></script>
