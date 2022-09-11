<?php

class Techno_model extends CI_model
{
    public function login($id, $pw)
    {
        
        $data = $this->db->get_where('user', ['username'=> $id])->row_array();
        if($data){
            if(password_verify(htmlspecialchars($pw), $data['password'])){
                return $data;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getContact()
    {
        return $this->db->get('contact')->result_array();
    }

    public function getContactById($id)
    {
        $data = $this->db->get_where('contact', ['id'=> $id])->row_array();
        return $data;
    }

    public function save($dt)
    {
        $data = [
            'name' => htmlspecialchars($dt['name']),
            'no_hp' => htmlspecialchars($dt['no_hp']),
        ];

        $this->db->insert('contact', $data);
        return $this->db->affected_rows();
    }

    public function update($dt)
    {
        $data = [
            'name' => htmlspecialchars($dt['name']),
            'no_hp' => htmlspecialchars($dt['no_hp']),
        ];

        $this->db->update('contact', $data, ['id'=>$dt['id']]);
        return $this->getContact();
    }

    public function delete($id)
    {
        $this->db->delete('contact',['id'=>$id]);
        return $this->db->affected_rows();
    }
}