<?php

namespace controllers;

use models\ProductsRepository;
use models\ReportingRepository;
use utils\MyFunctions;
use utils\Render;

class UsersController extends Controller
{
    protected $modelName = \models\UsersRepository::class;
    protected MyFunctions $functions;
    protected $product;
    protected $reporting;

    public function __construct()
    {
        parent::__construct();
        $this->reporting = new ReportingRepository();
        $this->product = new ProductsRepository();
        $this->functions = new MyFunctions();
    }


    public function index()
    {
        if (isset($_SESSION['id'], $_SESSION["status"])) {
            $this->dashboard();
        } else {
            $pageTitle = "Login";
            Render::render("index", compact("pageTitle"));
        }
    }

    public function dashboard()
    {
        $this->session->isConnected();

        $alerts = $this->product->getAlert();
        $employees = $this->reporting->sellerRanking();
        $yearRevenues = $this->reporting->salesBy("YEAR");
        $monthRevenues = $this->reporting->salesBy("MONTH");

        $pageTitle = "Dashboard";
        Render::render("dashboard", compact("pageTitle", "alerts", "employees", "yearRevenues", "monthRevenues"));
    }

    public function chartsView()
    {
        if (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "1") {
            $this->adminReporting();
        } elseif (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "2") {
            $this->sellerReporting();
        } else {
            header("Location: /cyclotron");
        }
    }

    public function sellerReporting()
    {
        $this->session->isSeller();

        $salesByMonth = $this->reporting->salesBy("MONTH", "AND i.user_id  = :userId", $_SESSION['id']);
        extract($salesByMonth);
        $monthSales = $period;
        $totalByMonth = $totalByPeriod;

        $salesByYear = $this->reporting->salesBy("YEAR", "AND i.user_id = :userId ", $_SESSION['id']);
        extract($salesByYear);
        $yearSales = $period;
        $totalByYear = $totalByPeriod;

        $pageTitle = "Seller";
        Render::render("sellerReporting", compact("pageTitle", "monthSales", "totalByMonth", "yearSales", "totalByYear"));
    }

    public function adminReporting()
    {
        $this->session->isAdmin();

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

        $pageTitle = "Reporting";
        Render::render("adminReporting", compact("pageTitle", "daySales", "totalByDay", "yearSales", "totalByYear", "productData", "productsVAT", "totalVAT"));
    }

    public function displayEmployees()
    {
        $this->session->isAdmin();
        $users = $this->model->findAll();
        $pageTitle = "Liste des employés";
        Render::render("employees", compact("pageTitle", "users"));
    }

    public function generatePassword()
    {
        $password = $this->functions->generatePassword();
        $this->session->setFlashMessage("Le mot de passe a bien été générer, n'oubliez pas de le noter dans un endroit sûr !");
        $this->employeesManagement($password);
    }

    public function employeesManagement()
    {
        $this->session->isAdmin();

        $pageTitle = "Création d'employé";
        Render::render("employeesManagement", compact("pageTitle"));
    }



    public function createEmployee()
    {
        $this->session->isAdmin();

        $this->model->insertEmployee();

        header("Location: /cyclotron/Users/displayEmployees");
    }


    public function deleteEmployee(int $employeeId)
    {
        $this->session->isAdmin();

        $this->model->deleteEmployee($employeeId);

        header("Location: /cyclotron/Users/displayEmployees");
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
        header("Location: /cyclotron");
    }
}
