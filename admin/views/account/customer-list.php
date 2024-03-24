<?php require_once 'show-toast.php'; ?>
<div class="content-wrapper">

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            List of Customers
        </h4>

        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="card-header row gy-3">
                <div class="col-12 col-sm-3">
                    <input type="search" class="form-control" name="search" placeholder="Search Customer">
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="?act=customer-bin" class="btn btn-outline-dark" type="button">
                            <i class="bx bx-trash me-1"></i>
                            Recycle Bin
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-2">Name</th>
                            <th class="col-3">Email</th>
                            <th class="col-3">Address</th>
                            <th class="col-2">Phone</th>
                            <th class="col-1">Status</th>
                            <th class="col-1">
                                <span class="float-end">
                                    Actions
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <?php foreach ($list as $item) : ?>
                            <?php if ($item['role'] == 0) : ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['email'] ?></td>
                                    <td><?= $item['address'] ?></td>
                                    <td><?= $item['phone'] ?></td>
                                    <td>
                                        <span class="badge bg-label-success">
                                            <?php
                                            if ($item['status'] == 1) {
                                                echo 'Active';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="float-end">
                                            <button onclick="openModalStatus(<?= $item['id'] ?>, 0, 'customer', 'Move to Bin?', 'You can find it in the recycle bin.')" class="btn btn-danger p-2">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
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