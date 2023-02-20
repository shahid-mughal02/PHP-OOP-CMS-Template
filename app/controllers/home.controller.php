<?php

class Home extends Controller
{
    public function index()
    {
        // $User = $this->load_model("User");
        // $user_data = $User->check_login();

        $data['page_title'] = "Home";
        $this->view("index", $data);
    }
}
