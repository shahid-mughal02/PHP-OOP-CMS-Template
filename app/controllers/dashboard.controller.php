<?php

class Dashboard extends Controller
{
    public function index()
    {
        // $User = $this->load_model("User");
        // $user_data = $User->check_login(true, ["admin"]);

        $data['page_title'] = "Dashboard";
        $this->view("dashboard/index", $data);
    }

    public function settings()
    {
        $data['page_title'] = "Settings";

        $this->view("dashboard/settings", $data);
    }
}
