<?php


class Users extends BaseCRUD {
    private $table = 'users';

    public function createUser($data) {
        return $this->create($this->table, $data);
    }

    public function getUser($id) {
        return $this->read($this->table, "id = $id");
    }

    public function updateUser($id, $data) {
        return $this->update($this->table, $data, "id = $id");
    }

    public function deleteUser($id) {
        return $this->delete($this->table, "id = $id");
    }
}

class Activities extends BaseCRUD {
    private $table = 'activities';

    public function createActivity($data) {
        return $this->create($this->table, $data);
    }

    public function getActivity($id) {
        return $this->read($this->table, "id = $id");
    }

    public function updateActivity($id, $data) {
        return $this->update($this->table, $data, "id = $id");
    }

    public function deleteActivity($id) {
        return $this->delete($this->table, "id = $id");
    }
}


class Reservations extends BaseCRUD {
    private $table = 'reservations';

    public function createReservation($data) {
        return $this->create($this->table, $data);
    }

    public function getReservation($id) {
        return $this->read($this->table, "user_id = $id");
    }

    public function updateReservation($id, $data) {
        return $this->update($this->table, $data, "id = $id");
    }

    public function deleteReservation($id) {
        return $this->delete($this->table, "id = $id");
    }
}


class Logs extends BaseCRUD {
    private $table = 'logs';

    public function createLog($data) {
        return $this->create($this->table, $data);
    }

    public function getLogs($user_id = null) {
        $condition = $user_id ? "user_id = $user_id" : "1=1";
        return $this->read($this->table, $condition);
    }

    public function deleteLog($id) {
        return $this->delete($this->table, "id = $id");
    }
}




?>
