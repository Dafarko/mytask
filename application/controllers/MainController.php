<?php

class MainController extends Controller
{

    function __construct()
    {
        $this -> model = new Model();
        $this -> view = new View();
    }
	function actionIndex()
	{
        $data = $this -> model -> selectMany();
		$this -> view -> generate('MainView.php', 'TemplateView.php');
	}
    public function show($id){
        $class['action'] = 'Show';
        $this -> model = new CarsModel();

        $data['articles'] = $this->CarsModel->getOneArticles(intval($id));

        $this -> view -> generate('MainView.php', 'TemplateView.php');
    }

    public function admin(){
        $class['action'] = 'Admin';


        $this -> model = new CarsModel();

        $data['artAdmin'] = $this->CarsModel->getArticles();

        $this -> view -> generate('MainView.php', 'TemplateView.php');
    }

    public function delete($id){

        if (!$id)
        {
            Route::ErrorPage404();
        }

        $this->model = new CarsModel();
        if($this->CarModel->deleteArticles($id) == FALSE){
            redirect('articles/admin');
            $this->session->set_flashdata('Вы успешно удалилии информацию');
        }
        else{
            Route::ErrorPage404();
        }
    }

    public function add(){

        $class['action'] = 'Add';
        $name['action'] = 'add';


        $this -> model = new CarsModel() ;
        $this -> CarsModel->addArticles();

        $this->form_validation->set_rules($this->rules);
        if ($this->form_validation->run() == FALSE)
        {
            $this -> view -> generate('MainView.php', 'TemplateView.php');
        }
        else
        {
            $this->view -> generate('success.php');
        }
    }

    public function edit(){



    }
}