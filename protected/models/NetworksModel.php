<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class NetworksModel extends CFormModel
{
    public $_connection;
    public $member_id;
    public $address1;
    public $mobile_no;
    public $telephone_no;
    public $email;
    public $spouse_contact_no;
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
    }
    
    public function rules()
    {
        return array(
                array('email, address1, mobile_no', 'required'),
                array('email', 'email'),
                array('spouse_contact_no, telephone_no, member_id', 'safe')
            );
    }
    
    public function attributeLabels()
    {
        return array('address1'=>'Address');
    }
    
    public function getProfileInfo($member_id)
    {
        $connection = $this->_connection;
        
        $sql = "SELECT a.member_id, a.date_created, a.username, a.password, b.last_name, b.first_name, b.middle_name, 
                CASE b.gender WHEN 1 THEN 'Male' WHEN 2 THEN 'Female' END AS gender,
                CASE b.civil_status WHEN 1 THEN 'Single' WHEN 2 THEN 'Married' WHEN 3 THEN 'Divorced'
                WHEN 4 THEN 'Separated' WHEN 5 THEN 'Widow' END AS civil_status,
                b.birth_date, b.spouse_name, b.spouse_contact_no, b.beneficiary_name,
                b.company, b.tin_no, b.email, b.address1, b.telephone_no, b.mobile_no, b.occupation,
                b.relationship, a.endorser_id, a.upline_id
                FROM members a
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id";
        
        $command = $connection->createCommand($sql);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function getContactInfo($member_id)
    {
        $connection = $this->_connection;
        
        $sql = "SELECT a.member_id, b.spouse_contact_no, b.email, b.address1, b.telephone_no, b.mobile_no
                FROM members a
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.member_id = :member_id";
        
        $command = $connection->createCommand($sql);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function updateContactInfo()
    {
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        
        try
        {
            $sql = "UPDATE member_details SET email = :email, spouse_contact_no = :spouse_contact_no,
                    address1 = :address1, telephone_no = :telephone_no, mobile_no = :mobile_no
                    WHERE member_id = :member_id";
            $command = $connection->createCommand($sql);
            $command->bindValue(':member_id', $this->member_id);
            $command->bindValue(':email', $this->email);
            $command->bindValue(':address1', $this->address1);
            $command->bindValue(':telephone_no', $this->telephone_no);
            $command->bindValue(':mobile_no', $this->mobile_no);
            $command->bindValue(':spouse_contact_no', $this->spouse_contact_no);
            $rowCount = $command->execute();
            
            if ($rowCount > 0) {
                    $beginTrans->commit();
                    return true;
            } else {
                $beginTrans->rollback();  
                return false;
            }
        }
        catch (CDbException $e)
        {
            $beginTrans->rollback();  
            return false;
        }
    }
    
    public function getDirectEndorse($member_id)
    {
        $connection = $this->_connection;
        
        $sql = "SELECT a.member_id, b.last_name, b.first_name, b.middle_name, a.date_created
                FROM members a
                INNER JOIN member_details b ON a.member_id = b.member_id
                WHERE a.endorser_id = :member_id
                ORDER BY b.last_name";
        $command = $connection->createCommand($sql);
        $command->bindParam(':member_id', $member_id);
        $result = $command->queryAll();
        
        return $result;
    }
}
?>
