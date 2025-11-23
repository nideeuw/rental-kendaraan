<?php
class SopirController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    // =====================================================
    // LIST DATA
    // =====================================================
    public function list(): void
    {
        $sopir = $this->model->getAllSopir();
        include 'views/sopir/sopir_list.php';
    }

    // =====================================================
    // CREATE DATA
    // =====================================================
    public function create(): void
    {
        if ($_POST) {

            $data = [
                'nama_sopir'     => $_POST['nama_sopir'],
                'no_sim'           => $_POST['no_sim'],
                'tarif_harian'          => $_POST['tarif_harian'],
                'status_sopir'         => $_POST['status_sopir'],
            ];

            if ($this->model->createSopir($data)) {
                header("Location: index.php?action=sopir_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah data sopir";
            }
        }

        $tipe_list = $this->model->getAllTipe();
        include 'views/sopir/sopir_form.php';
    }

    // =====================================================
    // EDIT DATA
    // =====================================================
    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {

            $data = [
                'nama_sopir'     => $_POST['nama_sopir'],
                'no_sim'           => $_POST['no_sim'],
                'tarif_harian'          => $_POST['tarif_harian'],
                'status_sopir'         => $_POST['status_sopir'],
            ];

            if ($this->model->updateSopir($id, $data)) {
                header("Location: index.php?action=sopir_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate data sopir";
            }
        }

        $sopir = $this->model->getSopirById($id);
        $tipe_list = $this->model->getAllSopir();
        include 'views/sopir/sopir_form.php';
    }

    // =====================================================
    // DELETE DATA
    // =====================================================
    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deleteSopir($id)) {
            header("Location: index.php?action=sopir_list&message=deleted");
        } else {
            header("Location: index.php?action=sopir_list&message=delete_error");
        }
        exit();
    }

    // =====================================================
    // SEARCH DATA
    // =====================================================
    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $sopir = $this->model->searchSopir($_GET['keyword']);
        } else {
            $sopir = $this->model->getAllSopir();
        }

        include 'views/sopir/sopir_list.php';
    }
}
