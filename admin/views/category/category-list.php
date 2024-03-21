<?php
// Kiểm tra xem có thông báo thành công từ session không?
if (isset($_SESSION["success"])) {
    // Hiển thị toast
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelector(".bs-toast").classList.add("show");
                setTimeout(function() {
                    document.querySelector(".bs-toast").classList.remove("show");
                }, 3000);
            });
        </script>';

    // Xóa thông báo thành công từ session
    unset($_SESSION["success"]);
}
?>
<div class="content-wrapper">

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            List of Categories
        </h4>

        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="card-header row gy-3">
                <label class="col-12 col-sm-3">
                    <input type="search" class="form-control" placeholder="Search Category">
                </label>
                <div class="col">
                    <a href="?act=add-category" class="btn btn-secondary add-new btn-primary float-end" type="button">
                        <i class="bx bx-plus me-0 me-sm-1"></i>
                        Add Category
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>iD</th>
                            <th>Name</th>
                            <th>
                                <span class="float-end">
                                    Actions
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <?php foreach($list as $item) : ?>
                        <tr>
                            <td>
                                <?= $item['id'] ?>
                            </td>
                            <td>
                                <?= $item['name_category'] ?>
                            </td>
                            <td>
                                <div class="float-end">
                                    <a href="?act=update-category&id=<?= $item['id'] ?>" class="btn btn-success p-2">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <button onclick="openModal(<?= $item['id'] ?>)" class="btn btn-danger p-2">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>