<?php
class DBConnect{

    const dbUser = 'root';
	const dbPassword = '';
	const dbHost = 'localhost';
	const dbName = 'cars';

    private $dbc;

    function __construct() {
			$this->dbc = @mysqli_connect(
				self::dbHost,
				self::dbUser,
				self::dbPassword,
				self::dbName
			)
			OR die(
				'Could not connect to MySQL: ' . mysqli_connect_error()
			);

			mysqli_set_charset($this->dbc, 'utf8');
		}

		function prepare_string($string) {
			$string = strip_tags($string);
			$string = mysqli_real_escape_string($this->dbc, trim($string));
			return $string;
		}

		function get_dbc() {
			return $this->dbc;
		}

        function register_user($name, $email, $phone, $province){
            
            $name_clean = $this->prepare_string($name);
            $email_clean = $this->prepare_string($email);
            $phone_clean = $this->prepare_string($phone);
            $province_clean = $this->prepare_string($province);

            $query = "INSERT INTO users(name , email, phone, province) VALUES (?,?,?,?)";
        
            $stmt = mysqli_prepare($this->dbc, $query);

            mysqli_stmt_bind_param(
                $stmt,
                'ssss',
                $name_clean,
                $email_clean,
                $phone_clean,
                $province_clean
            );

            $result = mysqli_stmt_execute($stmt);

            return $result;
        }
        
        function get_users() {
            $query = 'SELECT * FROM users;';
            $result = @mysqli_query($this->dbc,$query);
            return $result;
        }
        
        function get_user_by_email($user_email) {
            $user_email_clean = $this->prepare_string($user_email);
            $query = "SELECT * FROM users WHERE email = ?;";
            $stmt = mysqli_prepare($this->dbc, $query);
            mysqli_stmt_bind_param(
                $stmt,
                's',
                $user_email_clean
            );
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return $result;
        }
}

?>