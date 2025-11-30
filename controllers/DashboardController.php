<?php
class DashboardController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function index(): void
    {
        $stats = $this->model->getStatistics();
        $statistikPendapatan = $this->model->getStatistikPendapatan();
        $recentRentals = $this->model->getRecentRentals(5);

        include 'views/dashboard.php';
    }
}