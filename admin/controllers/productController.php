<?php

function productList() {
    $titleBar = 'Products';
    $view     = 'product/product-list';
    $list     = getProducts();
    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function productBin() {
    $titleBar = 'Products';
    $view     = 'product/product-bin';
    $list     = getProducts();
    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function createProduct() {
    $titleBar = 'Products';
    $view     = 'product/product-create';
    $listCategory = selectStatusActive('tbl_categories');
    
    if (isset($_POST['btnPublish'])) {
        $data = [
            "id_category" => $_POST["productCategory"] ?? null,
            "name"        => $_POST["productName"] ?? null,
            "description" => $_POST["productDescription"] ?? null,
            "price"       => $_POST["productPrice"] ?? null,
            "discount"    => $_POST["productDiscount"] ?? null,
            "instock"     => $_POST["productInstock"] ?? null,
        ];

        // Xử lý file
        if ($_FILES['productThumbnail']['error'] === UPLOAD_ERR_OK) {
            $data['thumbnail'] = upload_file($_FILES['productThumbnail'], 'uploads/product/');
        }

        // Validate
        $errors = validateCreateProduct($data);
        if (!empty($errors)) {
            $_SESSION["errors"] = $errors;
            $_SESSION["data"]   = $data ;
            header('Location: ?act=create-product');
            exit();
        }

        insert('tbl_products', $data);
        $_SESSION["success"]='';
    }
    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function updateProduct($id) {
    $show = selectOne('tbl_products', $id);

    if (empty($show)) {
        page404();
    }

    $titleBar = 'Products';
    $view     = 'product/product-update';
    $listCategory = selectStatusActive('tbl_categories');
    
    if (isset($_POST['btnSave'])) {
        $data = [
            "id_category" => $_POST["productCategory"] ?? null,
            "name"        => $_POST["productName"] ?? null,
            "description" => $_POST["productDescription"] ?? null,
            "price"       => $_POST["productPrice"] ?? null,
            "discount"    => $_POST["productDiscount"] ?? null,
            "instock"     => $_POST["productInstock"] ?? null,
        ];

        // Xử lý file
        $img = $_FILES['productThumbnail'] ?? null;
        if ($img['error'] === UPLOAD_ERR_OK) { // Kiểm tra xem có lỗi khi upload không
            $data['thumbnail'] = upload_file($img, 'uploads/product/');
            // Xóa hình ảnh cũ nếu tồn tại
            if (!empty($_POST['img-current']) && file_exists(PATH_UPLOAD . $_POST['img-current'])) {
                unlink(PATH_UPLOAD . $_POST['img-current']);
            }
        } else {
            // Nếu người dùng không upload hình ảnh mới, giữ nguyên đường dẫn của hình ảnh hiện tại
            $data['thumbnail'] = $_POST['img-current'] ?? null;
        }

        // Validate
        $errors = validateUpdateProduct($data);
        if (!empty($errors)) {
            $_SESSION["errors"] = $errors;
            header('Location: ?act=update-product&id=' . $id);
            exit();
        } else {
            update('tbl_products', $id, $data);
            $_SESSION["success"]='';
        }
        
        header('Location: ?act=product-list');
        exit();
    }
    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function updateStatusProduct($id, $value) {
    updateStatus('tbl_products', $id, $value);
    if ($value == 1) {
        header('Location: ?act=product-bin');
    } else {
        header('Location: ?act=product-list');
    }
    $_SESSION["success"]='';
    exit();
}

function addGallery($id) {
    $titleBar    = 'Products';
    $view        = 'product/product-gallery';
    $show        = selectOne('tbl_products', $id);
    $gallery     = getGallery('tbl_gallery', $id);

    if (isset($_POST['btnAddGallery'])) {

        $data = [
            "id_product" => $id ?? null,
        ];

        // Xử lý file
        if ($_FILES['productGallery']['error'] === UPLOAD_ERR_OK) {
            $data['url'] = upload_file($_FILES['productGallery'], 'uploads/product/');
        }

        // Validate
        $errors = validateGallery($data);
        if (!empty($errors)) {
            $_SESSION["errors"] = $errors;
            header('Location: ?act=add-gallery&id=' . $id);
            exit();
        }

        insert('tbl_gallery', $data);
        $_SESSION["success"]='';
        header('Location: ?act=add-gallery&id=' . $id);
        exit();
    }
    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateGallery($data) {
    // Check if product gallery is not uploaded
    if ($_FILES['productGallery']['size'] == 0) {
        $errors['productGallery'] = 'Please upload an image.';
    }
    return $errors;
}

function deleteImage($id, $back) {
    delete('tbl_gallery', $id);
    header('Location: ?act=add-gallery&id=' . $back);
    exit();
}

function validateCreateProduct($data) {
    $errors = [];

    // Validate name
    if (empty($data['name'])) {
        $errors['productName'] = 'This field is required.';
    } elseif (strlen($data['name']) > 50) {
        $errors['productName'] = 'Please enter between 1 and 50 characters.';
    }

    // Validate description
    if (empty($data['name'])) {
        $errors['productDescription'] = 'This field is required.';
    } elseif (strlen($data['name']) > 255) {
        $errors['productDescription'] = 'Please enter between 1 and 255 characters.';
    }

    // Check if product thumbnail is not uploaded
    if ($_FILES['productThumbnail']['size'] == 0) {
        $errors['productThumbnail'] = 'Please upload an image.';
    }

    // Validate category ID
    if (empty($data['id_category'])) {
        $errors['productCategory'] = 'Please select a category.';
    } elseif (!is_numeric($data['id_category']) || $data['id_category'] <= 0) {
        $errors['productCategory'] = 'Invalid category ID.';
    }

    // Validate instock quantity
    if (empty($data['instock'])) {
        $errors['productInstock'] = 'This field is required.';
    } elseif (!is_numeric($data['instock']) || $data['instock'] < 0) {
        $errors['productInstock'] = 'Quantity must be a positive numeric value.';
    }

    // Validate price
    if (empty($data['price'])) {
        $errors['productPrice'] = 'This field is required.';
    } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
        $errors['productPrice'] = 'Price must be a positive numeric value.';
    } elseif ($data['price'] > 999999999) {
        $errors['productPrice'] = 'Price must be less than or equal to 999999999.';
    }

    // Validate discount percent
    if (empty($data['discount'])) {
        $errors['productDiscount'] = 'This field is required.';
    } elseif (!is_numeric($data['discount']) || $data['discount'] < 0 || $data['discount'] > 100) {
        $errors['productDiscount'] = 'Discount percent must be a numeric value between 0 and 100.';
    }

    return $errors;
}

function validateUpdateProduct($data) {
    $errors = [];

    // Validate name
    if (empty($data['name'])) {
        $errors['productName'] = 'This field is required.';
    } elseif (strlen($data['name']) > 50) {
        $errors['productName'] = 'Please enter between 1 and 50 characters.';
    }

    // Validate description
    if (empty($data['description'])) {
        $errors['productDescription'] = 'This field is required.';
    } elseif (strlen($data['description']) > 255) {
        $errors['productDescription'] = 'Please enter between 1 and 255 characters.';
    }

    // Validate category ID
    if (empty($data['id_category'])) {
        $errors['productCategory'] = 'Please select a category.';
    } elseif (!is_numeric($data['id_category']) || $data['id_category'] <= 0) {
        $errors['productCategory'] = 'Invalid category ID.';
    }

    // Validate instock quantity
    if (empty($data['instock'])) {
        $errors['productInstock'] = 'This field is required.';
    } elseif (!is_numeric($data['instock']) || $data['instock'] < 0) {
        $errors['productInstock'] = 'Quantity must be a positive numeric value.';
    }

    // Validate price
    if (empty($data['price'])) {
        $errors['productPrice'] = 'This field is required.';
    } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
        $errors['productPrice'] = 'Price must be a positive numeric value.';
    } elseif ($data['price'] > 999999999) {
        $errors['productPrice'] = 'Price must be less than or equal to 999999999.';
    }

    // Validate discount percent
    if (empty($data['discount'])) {
        $errors['productDiscount'] = 'This field is required.';
    } elseif (!is_numeric($data['discount']) || $data['discount'] < 0 || $data['discount'] > 100) {
        $errors['productDiscount'] = 'Discount percent must be a numeric value between 0 and 100.';
    }

    return $errors;
}