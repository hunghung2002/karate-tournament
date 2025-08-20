$(document).ready(function () {
  // Khởi tạo DataTable
  var table = $('#athletesTable').DataTable({
    ajax: "fetch-athletes.php", // file PHP trả về JSON danh sách VĐV
    columns: [
      { data: "id" },
      { data: "name" },
      { data: "club" },
      { data: "weight" },
      { data: "age_group" },
      { data: "gender" },
      { data: "points" },
      { data: "actions" }
    ]
  });

  // ======================
  // 1. Thêm VĐV
  // ======================
  $('#formAdd').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: "club-match.php",
      type: "POST",
      data: $(this).serialize() + "&action=addAthlete",
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          Swal.fire("Thành công", res.message, "success");
          $('#formAdd')[0].reset();
          table.ajax.reload();
        } else {
          Swal.fire("Lỗi", res.message, "error");
        }
      },
      error: function () {
        Swal.fire("Lỗi", "Không thể gửi yêu cầu!", "error");
      }
    });
  });

  // ======================
  // 2. Import CSV
  // ======================
  $('#formImport').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("action", "importCSV");

    $.ajax({
      url: "club-match.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          Swal.fire("Thành công", res.message, "success");
          $('#formImport')[0].reset();
          table.ajax.reload();
        } else {
          Swal.fire("Lỗi", res.message, "error");
        }
      },
      error: function () {
        Swal.fire("Lỗi", "Không thể import CSV!", "error");
      }
    });
  });

  // ======================
  // 3. Xuất CSV
  // ======================
  $('#btnExportCSV').on('click', function () {
    window.location.href = "export-csv.php";
  });

  // ======================
  // 4. Xuất Excel
  // ======================
  $('#btnExportExcel').on('click', function () {
    window.location.href = "export-excel.php";
  });

  // ======================
  // 5. Xóa VĐV
  // ======================
  $(document).on('click', '.deleteBtn', function () {
    var id = $(this).data("id");
    Swal.fire({
      title: "Bạn có chắc muốn xóa?",
      text: "Hành động này không thể hoàn tác!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xóa",
      cancelButtonText: "Hủy"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "club-match.php",
          type: "POST",
          data: { action: "deleteAthlete", id: id },
          dataType: "json",
          success: function (res) {
            if (res.status === "success") {
              Swal.fire("Đã xóa", res.message, "success");
              table.ajax.reload();
            } else {
              Swal.fire("Lỗi", res.message, "error");
            }
          },
          error: function () {
            Swal.fire("Lỗi", "Không thể xóa VĐV!", "error");
          }
        });
      }
    });
  });
});
