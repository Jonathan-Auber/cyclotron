<?php

namespace controllers;

use models\ProductsRepository;
use models\ReportingRepository;
use utils\Render;

class UsersController extends Controller
{
    protected $modelName = \models\UsersRepository::class;
    protected $product;
    protected $reporting;

    public function __construct()
    {
        parent::__construct();
        $this->reporting = new ReportingRepository();
        $this->product = new ProductsRepository();
    }


    public function index()
    {
        if (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "boss") {
            $this->adminDashboard();
        } elseif (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "seller") {
            $this->sellerDashboard();
        } else {
            $pageTitle = "Login";
            $indexTitle = "Espace de connexion";
            Render::render("index", compact("pageTitle", "indexTitle"));
        }
    }

    public function adminDashboard()
    {
        $alerts = $this->product->getAlert();
        $pageTitle = "Dashboard";
        Render::render("adminDashboard", compact("pageTitle", "alerts"));
    }

    public function sellerDashboard()
    {
        $pageTitle = "Dashboard";
        Render::render("sellerDashboardDashboard", compact("pageTitle"));
    }

    public function chartsView()
    {
        if (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "boss") {
            $this->adminView();
        } elseif (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "seller") {
            $this->sellerView();
        }
    }

    public function sellerView()
    {
        $salesByMonth = $this->reporting->salesBy("MONTH", "AND i.user_id = ?", $_SESSION['id']);
        extract($salesByMonth);
        $monthSales = $period;
        $totalByMonth = $totalByPeriod;

        $salesByYear = $this->reporting->salesBy("YEAR", "AND i.user_id = ?", $_SESSION['id']);
        extract($salesByYear);
        $yearSales = $period;
        $totalByYear = $totalByPeriod;

        $pageTitle = "Seller";
        Render::render("sellerView", compact("pageTitle", "monthSales", "totalByMonth", "yearSales", "totalByYear"));
    }

    public function adminView()
    {
        $salesByDay = $this->reporting->salesBy("DATE");
        extract($salesByDay);
        $daySales = $period;
        $totalByDay = $totalByPeriod;

        $salesByYear = $this->reporting->salesBy("YEAR");
        extract($salesByYear);
        $yearSales = $period;
        $totalByYear = $totalByPeriod;

        $productByMonthWithVAT = $this->reporting->productByMonthWithVAT();
        extract($productByMonthWithVAT);
        $productData = $results;

        $pageTitle = "Admin";
        Render::render("adminView", compact("pageTitle", "daySales", "totalByDay", "yearSales", "totalByYear", "productData", "productsVAT", "totalVAT"));
    }

    public function salesView()
    {
        $this->session->isAdmin();
        $users = $this->model->findAll();
        $pageTitle = "Ventes des vendeurs";
        Render::render("salesView", compact("pageTitle", "users"));
    }

    public function salesBySeller(int $userId)
    {
        $this->session->isAdmin();
        $user = $this->model->find($userId);
        $results = $this->reporting->salesBySeller($userId);
        extract($results);
        $pageTitle = "Ventes des vendeurs";
        Render::render("salesBySeller", compact("pageTitle", "user", "resultByMonth", "resultByYear"));
    }

    public function login()
    {
        $this->model->login();
        header("Location: /cyclotron");
    }

    public function logout()
    {
        $this->model->logout();
        $pageTitle = "Déconnexion";
        $errorCode = "200";
        $errorDescription = "Vous êtes bien déconnecté";
        Render::render("error", compact("errorCode", "errorDescription", "pageTitle"));
    }
}
