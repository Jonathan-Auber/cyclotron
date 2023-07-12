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
            $indexTitle = "Espace de connexion";
            Render::render("index", compact("pageTitle", "indexTitle"));
        }
    }

    public function dashboard()
    {
        $alerts = $this->product->getAlert();
        $employees = $this->reporting->sellerRanking();
        $yearRevenues = $this->reporting->salesBy("YEAR");
        $monthRevenues = $this->reporting->salesBy("MONTH");
        $pageTitle = "Dashboard";
        Render::render("dashboard", compact("pageTitle", "alerts", "employees", "yearRevenues", "monthRevenues"));
    }

    public function chartsView()
    {
        if (isset($_SESSION['id'], $_SESSION["status"]) && $_SESSION['status'] === "boss") {
            $this->adminReporting();
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

    public function adminReporting()
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

        $pageTitle = "Reporting";
        Render::render("adminReporting", compact("pageTitle", "daySales", "totalByDay", "yearSales", "totalByYear", "productData", "productsVAT", "totalVAT"));
    }

    public function displayEmployees()
    {
        $this->session->isAdmin();
        $users = $this->model->findAll();
        $pageTitle = "Ventes des vendeurs";
        Render::render("employees", compact("pageTitle", "users"));
    }

    public function generatePassword(?int $employeeId = null)
    {
        $password = $this->functions->generatePassword();
        $this->session->setFlashMessage("Le mot de passe a bien été générer, n'oubliez pas de le noter dans un endroit sûr !");
        $this->employeesManagement($employeeId, $password);
    }

    public function employeesManagement(?int $employeeId = null, ?string $password = null)
    {
        $this->session->isAdmin();
        if ($employeeId) {
            $employee = $this->model->find($employeeId);
            $pageTitle = "Editer un employé";
            Render::render("employeesManagement", compact("pageTitle", "employee"));
        } else {
            $pageTitle = "Créer un nouvel employé";
            Render::render("employeesManagement", compact("pageTitle", "password"));
        }
    }



    public function createOrUpdateEmployee(?int $employeeId = null)
    {
        var_dump($_POST);
        var_dump($_POST["employee_status"]);

        // if ($employeeId) {
        //     $this->model->updateCustomer($employeeId);
        // } else {
        $this->model->insertEmployee();
        // }
        // $employees = $this->model->findAll();
        // header("Location:/cyclotron/Users/employeesManagement");

        header("Location:/cyclotron/Users/displayEmployees");
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
