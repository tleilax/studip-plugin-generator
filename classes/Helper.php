<?
class Helper
{
    function get_email($user_id)
    {
        $query = "SELECT Email FROM auth_user_md5 WHERE user_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($user_id));
        return $statement->fetchColumn();
    }    
}
